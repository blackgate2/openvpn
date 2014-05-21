$(document).ready(function() {

    // run the currently selected effect
    function runEffect() {
        // get effect type from
        var selectedEffect = 'blind';

        // most effect types need no options passed by default
        var options = {};
        // some effects have required parameters
        if (selectedEffect === "scale") {
            options = {
                percent: 0
            };
        } else if (selectedEffect === "size") {
            options = {
                to: {
                    width: 200,
                    height: 60
                }
            };

        }

        // run the effect
        $("#effect").toggle(selectedEffect, options, 500);

    }
    ;

    // set effect from select menu value
    $("#button_toggle_filter").click(function() {
        runEffect();
        //alert($('#button_toggle_filter').html() );
        $(this).html(($(this).html() == 'скрыть фильтры') ? 'показать фильтры' : 'скрыть фильтры');
        return false;
    });


    // open filemanager
    $(".openfilemanger").live("click",function() {
        $( "body" ).append( '<form name=example1><input type="hidden" name="url" value="Select file" size="80"></form>');
        mcFileManager.open('example1','url');
        return false;
    });





    /* ---------------- переменная для формированиея части url ----------------------------*/
    var href = '';

    // Accordion
    $("#accordion").accordion({
        header: "h3"
    });

    // Tabs
    $('#tabs').tabs();
    /* ----------------  ----------------------------*/
    $("a.list_to_dialog").click(function() {

        $("#dialog_alert").dialog({
            title: $(this).html()
        });
        $("#dialog_alert").html($(this).attr("title").replace(/\n/g, "</br>"));
        $("#dialog_alert").dialog("open");

        return false;
    });
        $('a.list_to_dialog').hover(
            
            function() {
                def = $(this).attr("title");
                var hov= $(this).attr("title").replace(/<\/?[^>]+>/gi, '');
                $(this).attr("title",hov);
                
            },
            function() {
                $(this).attr("title",def);
            }
    );
    /* ---------------- модальне оконо ----------------------------*/
    $('#dialog_modal').dialog({
        modal: true,
        autoOpen: false,
//        show: {
//            effect: "fade",
//            duration: 1000
//        },
//        hide: {
//            effect: "fade",
//            duration: 500
//        },
        buttons: {
            "Cancel": function() {
                $(this).dialog("close");
            },
            "Ok": function() {
                $(this).dialog("close");
            }

        }
    });

    /* ---------------- простое диалоговое оконо ----------------------------*/
    $('#dialog').dialog({
        modal: false,
        autoOpen: false,
        width: 730,
        height: 540,
//        show: {
//            effect: "fade",
//            duration: 500
//        },
//        hide: {
//            effect: "fade",
//            duration: 500
//        },
        buttons: {
            "Ok": function() {
                $(this).dialog("close");
            }
        }

    });

    /* ---------------- окно для предупреждения   ----------------------------*/
    $('#dialog_confirm').dialog({
        modal: true,
        autoOpen: false,
        width: 600,
        height: 300,
//        show: {
//            effect: "fade",
//            duration: 500
//        },
//        hide: {
//            effect: "fade",
//            duration: 500
//        },
        buttons: {
            "Ok": function() {
                window.location = href;
            },
            "Cancel": function() {
                $(this).dialog("close");
            }
        }

    });

    /* ---------------- окно для сообщения  ----------------------------*/
    $('#dialog_alert').dialog({
        modal: false,
        autoOpen: false,
        width: 350,
        height: 300,
//        show: {
//            effect: "fade",
//            duration: 500
//        },
//        hide: {
//            effect: "fade",
//            duration: 500
//        },
        buttons: {
            "Close": function() {
                $(this).dialog("close");
            }
        }

    });

    /* ---------------- обработчик окрытия простого диалогвого окна   ----------------------------*/
    $('.link_alert').click(function() {
        $("#dialog_alert").dialog('open');
        $("#dialog_alert").dialog({
            title: $(this).attr("title")
        });
        $("#dialog_alert").html('<br><center><img src="/images/loading.gif"></center>');
        $("#dialog_alert").load($(this).attr("href"));
        return false;
    });

    $('.link_dialog').click(function() {
        $("#dialog").dialog('open');
        $("#dialog").dialog({
            title: $(this).attr("title")
        });
        $("#dialog").html('<br><center><img src="/images/loading.gif"></center>');
        $("#dialog").load($(this).attr("href"));
        return false;
    });


    /* ---------------- обработчик окрытия модального окна   ----------------------------*/
    $('.link_dialog_modal').click(function() {
        $("#dialog_modal").dialog('open');
        $("#dialog_modal").dialog({
            title: $(this).attr("title")
        });
        
        $("#dialog_modal").html('<br><center><img src="/images/loading.gif"></center>');
        $("#dialog_modal").load($(this).attr("href"));
        return false;
    });
    
     /**/
    $('.link_group_dialog_modal').click(function() {
        var objects_ids = '';
        var href = $(this).attr("href");
        if ($('.rows_checked').is(':checked')) {
            var allVals = [];
            $(".rows_checked:checked").each(function() {
                allVals.push($(this).val());
            });
            objects_ids = allVals.join(',');

        }
        //alert(objects_ids);
        if (objects_ids != '') {
            href += '&objects_ids=' + objects_ids;
            $("#dialog_modal").dialog('open');
            $("#dialog_modal").dialog({
                title: $(this).attr("title")
            });
            $("#dialog_modal").html('<div class="loading"></div>');
            $("#dialog_modal").load(href);
        } else {
            $("#dialog_alert").dialog({
                title: $(this).attr("title")
            });
            $("#dialog_alert").html('<p>Пожалуйста выберите строки</p>');
            $("#dialog_alert").dialog('open');
            return false;
        }

        return false;
    });


    /* ---------------- обработчик выделить строку цветом   ----------------------------*/
    $('#tables tr.data').hover(
            function() {
                $(this).addClass('over');
            },
            function() {
                $(this).removeClass('over');
            }
    );
    /* ---------------- обработчик выделить строку цветом   ----------------------------*/
    $('.Checkboxes').click(function() {
        if ($(this).is(':checked'))
            $(this).parent().parent().addClass('save');
        else
            $(this).parent().parent().removeClass("save");

    });

    /* ---------------- обработчик выделить все чекбоксы   ----------------------------*/
    $("#maincheck").click(function() {
        if ($('#maincheck').attr('checked')) {
            $('.rows_checked').attr('checked', true);
        } else {
            $('.rows_checked').attr('checked', false);
        }
    });


    /* ---------------- обработчик для группового действия над строками   ----------------------------*/
    $('.link_group_anythink').click(function() {
        var objects_ids = '';
        if ($('.rows_checked').is(':checked')) {
            var allVals = [];

            $(".rows_checked:checked").each(function() {
                allVals.push($(this).val());
            });
            objects_ids = allVals.join(',');

        }
        //alert(objects_ids);
        if (objects_ids != '') {
            $("#dialog_confirm").dialog('open')
            $("#dialog_confirm").dialog({
                title: $(this).attr("title")
            });
            $("#dialog_confirm").html($(this).attr("confirm"));
            href = $(this).attr("href") + '&objects_ids=' + objects_ids;


        } else {
            $("#dialog_alert").dialog({
                title: $(this).attr("title")
            });
            $("#dialog_alert").html('<p>Пожалуйста выберите строки</p>');
            $("#dialog_alert").dialog('open');
        }

        return false;
    });

    /* для обработчик для одиночной кнопки */
    $('.link_confirm').click(function() {
        $("#dialog_confirm").dialog('open');
        $("#dialog_confirm").dialog({
            title: $(this).attr("title")
        });

        $("#dialog_confirm").html($(this).attr("confirm"));
        href = $(this).attr("href");
        return false;
    });
    $('.link_go').click(function() {
        window.location = $(this).attr("href");
    });
    //hover states on the static widgets
    $('.ui-button').hover(
            function() {
                $(this).toggleClass('ui-state-hover');
            }
    );


});
