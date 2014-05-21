<?php
session_start();
header('Content-type: application/javascript');
require('../protected/cammon/msg_array'.$_SESSION['pre'].'.php');
$redirect_url='/user';
$login_url ='/login_form.php';

?>
$(document).ready(function() {
    function mess(t,o) {
        o.html(t);
        o.show("slow");
        setTimeout( function() {
            o.html('');
            o.hide("slow");
        }, 3000 );
    }
    
    function auth_ajax(){
        var l=$('#ulogin').val();
        var p=$('#upasswd').val();
        $.post('<?=$login_url?>', {
            login: l,
            passwd: p
        }, function(response) {
            if(response == 'success'){
                mess("<?=$msg['login_ok']?>",$('#login_mess'));
                setTimeout( function() {
                    location='<?=$redirect_url?>'
                }, 3000 );
            }else{
               
                mess("<?=$msg['login_err']?>",$('#login_mess'));
            }
        //$('#objectsID_filter').html(data);
        });
		
    //return false;
    }
    
     
    $('#button_ulogin').click(function() {
        auth_ajax();

    });
    $('.text').keypress(function (e) {
        if (e.which == 13) {
            auth_ajax();
        }
    });

        
});