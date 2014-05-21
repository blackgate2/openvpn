$(document).ready(function() {
    //------------- зависимые фильтры --------------------
    
    $('#type_id').change(function() {
        id = $('#id').val()
        type_id = $('#type_id').val();
        
        $.post('refresh_form_select_servers.php', {
            id: id,
            type_id: type_id
        }, function(data) {
            $('#order_server_ids').html(data);
        });
        
    });
    //$('#datetime_expire').val((date.getMonth() + 1));
});