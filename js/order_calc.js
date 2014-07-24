$(document).ready(function() {
    var typeID;
    var country;
    var period;
    var portable;
    var amount;
    $('.type').click(function() {
        i = this.value;
        proc(i);
        

    });
    
    $('.type').each(function( ) {
      i = this.value;
      proc(i);
    });

    function proc(i){
        if ($('#type' + i).is(':checked')) {
            $("#country" + i).removeAttr('disabled');
            $("#period" + i).removeAttr('disabled');
            $("#portable" + i).removeAttr('disabled');
            $("#os" + i).removeAttr('disabled');
            $("#protocol" + i).removeAttr('disabled');
            $("#tr" + i).attr('bgcolor', '#b1fbe0');
            //$("#price"+i).removeAttr('disabled');
            $("#amount" + i).removeAttr('disabled');
            get_values(i);
            post_data();

        } else {
            $("#country" + i).attr('disabled', 'disabled');
            $("#period" + i).attr('disabled', 'disabled');
            $("#portable" + i).attr('disabled', 'disabled');
            $("#os" + i).attr('disabled', 'disabled');
            $("#protocol" + i).attr('disabled', 'disabled');
            $("#tr" + i).attr('bgcolor', '');
            $('#price' + i).val(0);
            $("#amount" + i).attr('disabled', 'disabled');
            $("#amount" + i).val(1);
            calc();
        }
    }

    $('.country,.period,.portable').change(function() {


        //alert('typeID:'+i+' countryID: '+country+' period:'+period+' portable:'+portable+' amount:'+amount );
        if ($(this).attr('typeID')) {
            get_values($(this).attr('typeID'));
            post_data();

        } else {
            alert('error: undefine typeID');
        }
    });
    function post_data() {
        
        $.post('/order_calc.php', {
            typeID: typeID,
            countryID: country,
            period: period,
            portable: portable,
            amount: amount
        }, function(data) {
            
            $('#price' + typeID).val(data);
            calc();
        });
    }
    function get_values(i) {
        typeID = i;
        country = $("#country" + i).val();
        period = $("#period" + i).val();
        portable = ($('#portable' + i).is(':checked')) ? 1 : 0;
        amount = $("#amount" + i).val();
    }
    $('.amount').keyup(function() {
        this.value = this.value.replace(/[^0-9$]/g, '');
        get_values($(this).attr('typeID'));
        post_data();

    });
    function calc() {
        $("#total").val(function() {

            var sum = 0;
            $('.price').each(function() {
                sum += Number($(this).val());

            });

            return sum;
        });

    }
    $('#button_submit,.list_countries').hover(
            function() {
                $(this).addClass('ui-state-hover');
            },
            function() {
                $(this).removeClass('ui-state-hover');
            }
    );
    $('#dialog_alert').dialog({
        modal: false,
        autoOpen: false,
        height: 300,
        width: 300,
        title: '!',
        buttons: {
            "Close": function() {
                $(this).dialog("close");
            }

        }

    });

    $('.list_countries').click(function() {            
            $("#dialog_alert").dialog( {
                title: 'Страны',
                width: 260,
                height: 260
            } );
            $("#dialog_alert").html( $(this).attr("title"));
            $("#dialog_alert").dialog('open');

    });

});
