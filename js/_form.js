$(document).ready(function() {
    //------------- зависимые фильтры --------------------
    
    
    $('#type_id').change(function() {
        $.post('refresh_form_select_servers.php', {
            id: $('#id').val(),
            type_id: $('#type_id').val()
        }, function(data) {
            $('#order_server_ids').html(data);
        });
        
    });
    $('#order_server_ids').change(function() {
        $("#account_id option:selected").removeAttr("selected");
    });
    //$('#datetime_expire').val((date.getMonth() + 1));
});