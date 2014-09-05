<?php
ini_set('max_execution_time', 3600);
//http://confs3.openvpn.ru/create/inv.php?r=1

$url = 'http://support:Tp0VkeSc4@confs3.openvpn.ru/create/inv.php?r=1';
$content = get_content ($url);

$inv_cont = explode("<br>", $content); //create array separate by new line
$inv_accounts='';
if (count($inv_cont)){
    for ($i=0;$i<count($inv_cont);$i++)  
    {   
        list($account,$proto,$server)=explode(":", $inv_cont[$i]); 
        $account=trim($account);
        $inv_accounts.=($account)?'"'.$account.'",':'';
    }
    $inv_accounts=trim($inv_accounts,',');
    
}
//echo $inv_accounts;

function get_content($url)
{
    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_HEADER, 0);

    ob_start();

    curl_exec ($ch);
    curl_close ($ch);
    $string = ob_get_contents();

    ob_end_clean();
    
    return $string;     
}

?>