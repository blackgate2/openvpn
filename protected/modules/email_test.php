<?
//require_once('./protected/class.define.conts.php');
//require_once('./protected/cammon/functions.php' );
//
//echo hesh_pass('cv5bk4hb', commonConsts::sol_pass1,commonConsts::sol_pass2);

echo date("F j, Y, g:i a");  
 send_mail('oleg@laweb.ru', 'oleg@laweb.ru','name', 'sss', 'test');
 
 function send_mail($to, $from, $namefrom, $subject, $message, $convert_from = '', $convert_to = '') {

    $header = "From: \"$namefrom\"<$from>\nReply-To: $email\nX-Mailer: PHP\nMime-Version: 1.0\nContent-Type: text/html; charset=\"utf-8\"\nReturn-Path: <$email>\n";
    mail($to, $subject, $message, $header);
}
?>