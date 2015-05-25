<?php

require(commonConsts::path_admin . '/vars_show.php');
require(commonConsts::path_cammon . '/strDate.class.php' );
require(commonConsts::path_cammon . '/forms.class.php' );
require(commonConsts::path_cammon . '/show_from_db.class.php' );
require commonConsts::path_modules . '/robokassa.params.php';
require(commonConsts::path_protect . '/class.user_row.php' );
require(commonConsts::path_admin . '/vars_db.class.php' );
require(commonConsts::path_cammon . '/class.common.php');
require commonConsts::path_modules . '/success.php';
require(commonConsts::path_admin . '/vars_runtime.php');

//require(commonConsts::path_protect . '/class.alink.php');




/* 
 * после факта оплаты обязательно 
 * редиректим что бы не накручивали дату конца оплаты
 * 
 */
if (isset($_REQUEST["InvId"])){
    redirect('/user');
}

if ($is_login) {


    $msg['checkAll'] = '';
    $show = new show_from_db($msg, $tables['user_orders_show']);


    $sql = 'Select ' . $show->obj['fields_sql'] .
            ' From ' . $show->obj['table_view'] . '  
        ' . $show->obj['where'] . ' 
         WHERE   u.id=' . $_SESSION['auth_user_id'] . '
        ' . (($show->obj['group']) ? 'Group by ' . $show->obj['group'] : '') . ' 
        ' . (($show->obj['order']) ? 'Order by ' . $show->obj['order'] : '') . ' ' . $show->obj['order_dir'];

    //echo $sql;
    $show->dataAll = $q->fetch_data_to_array($sql);

    foreach ($show->dataAll as $v) {
        $show->dataAll['total']['price']+=0;
    }

    $content['css'] = '
        <link type="text/css" href="/css/table.css" rel="stylesheet" /> 
        <link type="text/css" href="/css/users.css" rel="stylesheet" />';

    $content['name'] = $msg['user_orders'];
    /*
     * если был факт оплаты выводим сообщение
     */
    if (isset($_SESSION['payment_msg_allert'])){
    $content['content_page'] .= '<p class="alert" >'.$_SESSION['payment_msg_allert'].'</p>';
        unset($_SESSION['payment_msg_allert']);
    }
    if (!empty($show->dataAll)) {

        
        $content['content_page'].='<div class="actions">' . $show->actions_buttons() . '</div>';
        $content['content_page'].=$show->show();
    } else {
        $content['content_page'].=$msg['user_no_orders'];
    }
    $content['content_page'].='
    <div id="dialog_modal" title=""></div>
    <div id="dialog_alert" title=""></div>
    <script>
    $(document).ready(function() {
    
                
    //$(\'.formselect\').prop(\'selectedIndex\',0);
    //$(".formselect").val($("#select option:first").val());

    //alert(lang);
    var url_go = \'/order_ext_pay.php\';
    //alert(url_go);
    /*
     * переносим сегеренную кнопку туда куда надо */

    $("tr#total th:last").append($(\'div.actions\').html());
    $(\'div.actions\').html(\'\');
    $(\'th.title0\').html(\'\');
    //$(".formselect").val($(".formselect option:first").val());
    $(\'#maincheck\').remove();

    /*
     * обработка селекта: подсчет цены, общей суммы, покрашивает цену
     * 
     */
    $(".formselect").change(function() {
        id = $(this).attr("id").replace("ext_pay_select_", "");

        $.getJSON(url_go, {
            orderID: id,
            periodID: $(this).val()
        }, function(data) {
            $.each(data, function(key, val) {
                if (key == \'OutSum\') {
                    $("td span#price" + id).text(val)
                    $("td span#price" + id).addClass(\'price_select\');
                }
            });
            $("tr#total th.total").text(calc());
            $("tr#total th.total").addClass(\'price_select\');
        });

        return false;
    });

    $(".Checkboxes").change(function() {
        id = $(this).val();

        $.getJSON(url_go, {
            orderID: id,
            periodID: $(\'#ext_pay_select_\' + id).val()
        }, function(data) {
            $.each(data, function(key, val) {
                if (key == \'OutSum\') {
                    $("td span#price" + id).text(val)
                    $("td span#price" + id).addClass(\'price_select\');
                }
            });
            $("th.total").text(calc());
            $("th.total").addClass(\'price_select\');
        });

        return false;
    });

    /*
     * 
     */
    $(\'.ext_pay\').click(function() {

        var id = $(this).attr("id").replace("ext_pay_button_", "");
        var myarray = [];
        var myJSON = "";

        var item = {
            "oid": id,
            "pid": $(\'#ext_pay_select_\' + id).val()
        };

        myarray.push(item);

        myJSON = JSON.stringify(myarray)
        //alert(myJSON);


        $.getJSON(url_go, {
            order_params: myJSON
        }, function(data) {

            var url = \'\';
            $.each(data, function(key, val) {
                if (key != \'url\') {
                    url += \'&\' + key + \'=\' + val;
                } else {
                    url += val + \'?\';
                }
            });
            //console.log(url);
            window.location = url;
        });


        return false;
    });

    $(\'#order_ext_all\').click(function() {

        var id;
        var myarray = [];
        var myJSON = "";
        if ($(\'.Checkboxes\').is(\':checked\')) {
            $(\'td span.price\').each(function() {
                id = $(this).attr("id").replace("price", "");
                //order_params += id + \'_\' + $(\'#ext_pay_select_\' + id).val() + \',\';
                if ($(\'#checkbox_\' + id).is(\':checked\')) {
                    var item = {
                        "oid": id,
                        "pid": $(\'#ext_pay_select_\' + id).val()
                    };
                    myarray.push(item);
                }
            });

            myJSON = JSON.stringify(myarray)
            //alert(myJSON);


            $.getJSON(url_go, {
                order_params: myJSON
            }, function(data) {

                var url = \'\';
                $.each(data, function(key, val) {
                    if (key != \'url\') {
                        url += \'&\' + key + \'=\' + val;
                    } else {
                        url += val + \'/?\';
                    }
                });
                window.location = url;
                //$("#order_ext_all").attr("href", url);

            });

        } else {
            $("#dialog_alert").dialog({
                title: "!",
                height: 200
            });
            $("#dialog_alert").html(\'<p>\'+((lang==\'ru\')?\'Пожалуйста выберите строки\':\'Please select the rows\')+\'</p>\');
            $("#dialog_alert").dialog(\'open\');
        }
        return false;
    });


    function calc() {
        var sum = 0;

        $(\'td span.price.price_select\').each(function() {
            id = $(this).attr("id").replace("price", "");

            if ($(\'#checkbox_\' + id).is(\':checked\')) {
                sum += parseFloat($(this).text().replace(/\s+/g, \'\'));

            }


        });

        return ToMoneyFormat(sum);
    }

    $(\'input.numeric\').keyup(function() {
        this.value = this.value.replace(/[^0-9\.$]/g, \'\');
    });
    $(\'input.to_money\').keyup(function() {
        ToMoney(this);
    });

    function ToMoney(item) {
        if (item.value == undefined)
        {
            item = document.getElementById(item);
        }
        if (item) {
            item.style.backgroundColor = \'#FFFFFF\';
            if (item.value == \'\') {
            }
            else if (isNaN(parseFloat(item.value.replace(/^[^\d\.]*/, \'\'))))
            {
                item.style.backgroundColor = \'#FF0000\';
            }
            else {
                item.value = ToMoneyFormat(item.value);
            }
        }
    }

    function ToMoneyFormat(value) {
        value = String(value);
        var decimal_point = \'.\';
        var cur = value.split(decimal_point);
        value = number_format(cur[0], 2, decimal_point, \' \');
        if (cur[1] != undefined && cur[1] == \'\')
            value = value + decimal_point;
        if (cur[1] != undefined && cur[1] != \'\')
            value = value + decimal_point + (cur[1] + \'\').replace(/[^0-9+\-Ee.]/g, \'\');//number_format(cur[1],0,\'\',\'\');
        return value;
    }
    function number_format(number, decimals, dec_point, thousands_sep) {
        // *     example 1: number_format(1234.56);
        // *     returns 1: \'1,235\'
        // *     example 2: number_format(1234.56, 2, \',\', \' \');
        // *     returns 2: \'1 234,56\'
        // *     example 3: number_format(1234.5678, 2, \'.\', \'\');
        // *     returns 3: \'1234.57\'
        // *     example 4: number_format(67, 2, \',\', \'.\');
        // *     returns 4: \'67,00\'
        // *     example 5: number_format(1000);
        // *     returns 5: \'1,000\'
        // *     example 6: number_format(67.311, 2);
        // *     returns 6: \'67.31\'
        // *     example 7: number_format(1000.55, 1);
        // *     returns 7: \'1,000.6\'
        // *     example 8: number_format(67000, 5, \',\', \'.\');
        // *     returns 8: \'67.000,00000\'
        // *     example 9: number_format(0.9, 0);
        // *     returns 9: \'1\'
        // *    example 10: number_format(\'1.20\', 2);
        // *    returns 10: \'1.20\'
        // *    example 11: number_format(\'1.20\', 4);
        // *    returns 11: \'1.2000\'
        // *    example 12: number_format(\'1.2000\', 3);
        // *    returns 12: \'1.200\'
        // *    example 13: number_format(\'1 000,50\', 2, \'.\', \' \');
        // *    returns 13: \'100 050.00\'
        // Strip all characters but numerical ones.
        number = (number + \'\').replace(/[^0-9+\-Ee.]/g, \'\');
        var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === \'undefined\') ? \',\' : thousands_sep,
                dec = (typeof dec_point === \'undefined\') ? \'.\' : dec_point,
                s = \'\',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return \'\' + Math.round(n * k) / k;
                };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : \'\' + Math.round(n)).split(\'.\');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || \'\').length < prec) {
            s[1] = s[1] || \'\';
            s[1] += new Array(prec - s[1].length + 1).join(\'0\');
        }
        return s.join(dec);
    }
});

    </script>
    ';
}
?>