<?php
/* UTF-8 
XMPP webi
http://webi.ru/webi_files/xmpp_webi.html

*/

include_once("xmpp.class.php");
include_once("config.ini.php");
$webi = new XMPP($webi_conf);

$webi->connect(); // установка соединения...

$webi->sendStatus('text status','chat',3); // установка статуса
$webi->sendMessage("openvpn@jabber.ru", "test тест test тест ".date("H:i:s")); // отправка сообщения
$webi->sendMessage("olambin@gmail.com", "test тест ".date("H:i:s")); // отправка сообщения

echo "sdf";
// так можно зациклить
/*

while($webi->isConnected)
{
	$webi->getXML();
}

*/


?>
