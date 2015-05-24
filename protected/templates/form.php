<style>
    table.tbl_form{width:100%}
    td.form_elem{
        width: 200px;
    }
    td.form_elem:hover{
        background-color: #cbf69f;
    }
    td.form_elem div.contener_elem button{
        padding: 0;
    }
    div.contener_elem{
        display: inline; vertical-align: baseline;
    }
</style>    
<script>
    $(document).ready(function() {
        $('button.is_disabled').click(function(event) {
                event.preventDefault();
                $target = $(this).prev();
                //alert($target.attr("name"));        
                $target.toggleClass('disabled');
                $target.parent().parent().toggleClass('disabled');
                var disable = ($target.hasClass('disabled')) ? true : false;
                $target.attr("disabled", disable);
                if (disable) {
                    $(this).find('span').removeClass('ui-icon-check');
                    $(this).find('span').addClass('ui-icon-close');
                } else {
                    $(this).find('span').removeClass('ui-icon-close');
                    $(this).find('span').addClass('ui-icon-check');
                }

            });
<?php if ($table == 'users') { ?>
            $('button.is_disabled').click(function(event) {
                event.preventDefault();
                $('#is_check_portable').val(($('#portable').hasClass('disabled')) ? 1 : '');
            });
            $('div#contener_elem_1').find('input,select,textarea').attr("disabled", true);
            $('div#contener_elem_1').find('input,select,textarea').addClass('disabled');

            $('td#form_elem1').addClass('disabled');
            $('div#contener_elem_1').find('span').removeClass('ui-icon-check');
            $('div#contener_elem_1').find('span').addClass('ui-icon-close');
            

    <?php
}
?>
<?php if ($table == 'orders_params' || $table == 'users_groups' ) { ?>
            $('button.is_disabled').click(function(event) {
                event.preventDefault();
                $('#is_check_portable').val(($('#portable').hasClass('disabled')) ? 1 : '');
            });
            $('div.contener_elem').find('input,select,textarea').attr("disabled", true);
            $('div.contener_elem').find('input,select,textarea').addClass('disabled');

            $('td.form_elem').addClass('disabled');
            $('div.contener_elem').find('span').removeClass('ui-icon-check');
            $('div.contener_elem').find('span').addClass('ui-icon-close');
           

    <?php
}
?>
<?php if ($table == 'orders') { ?>
            //------------- зависимые формы для формы заказа  --------------------
            $('#type_id').change(function() {
                $.post('refresh_form_select_servers.php', {
                    id: $('#id').val(),
                    type_id: $('#type_id').val()
                }, function(data) {
                    $('#order_server_ids').html(data);
                });

            });
            $('#order_server_ids,#type_id,#protocol_id').change(function() {
                $("#account_id option:selected").removeAttr("selected");
                $("#action_id option:first").attr('selected', 'selected');
            });
    <?php if ($action == 'copy') { ?>
                $("#action_id option:first").attr('selected', 'selected');
        <?php
    }
    ?>

    <?php
}
?>
        $('input.numeric').keyup(function() {
            this.value = this.value.replace(/[^0-9\.$]/g, '');
        });
        $('input.to_money').keyup(function() {
            ToMoney(this);
        });
    });
</script>

<?=
$forms->str?>