<?
require_once('./protected/cammon/vadation.class.php' );
require_once('./protected/class.define.conts.php');
require_once('./protected/cammon/db.class.php' );
require_once('./protected/cammon/functions.php' );

$q = DB::Open();
$q->query('CALL deleteOrders()');
$severs = $q->fetch_data_to_accoc_array('Select id,name From servers Order by id', 'name', 'id');
//print_r($severs);
//exit('ddd');
$proto = array(tcp => 1, 'udp' => 2, 'V' => 3);

$handle = fopen("basev2.csv", "r");
$u = array();
$uk = array();
$o = array();
$a = array();
$order_id = 100;

while (($data = fgetcsv($handle)) !== FALSE) {


    $d = explode(';', $data[0]);
    //print_r($d);


    /* ------------- */
    $user_id = $d[0] + 100;

    $icq = $d[1];
    //$date_begin = $d[2];
    if (!$d[3]) {
        $d[3] = '01.12.2012';
        $d[9].=' add date 01.12.2012';
    }
    list($day, $m, $y) = explode('.', $d[3]);
    
    $date_end = trim($y).'-'.trim($m).'-'.trim($day);
    $date = new DateTime($date_end);
    $date->modify('-1 month');
    $date_begin = $date->format('Y-m-d');

    $portable = (preg_match('/portable/', $d[4])) ? '1' : '';
    $jabber = $d[5];
    $account = $d[6];
    $proto_id = $proto[$d[7]];
    $sever_id = $severs[$d[8]];
    $sever = $d[8];

    $memo = $d[9] . ' ' . $d[10];
    $email = (valid::isEmail($d[10]))?$d[10]:'';
    $price = $d[11];
    $type_id = $d[12];
    $pass=createPassword();


    if ($sever) {
        $q->begin();
        if (!in_array($user_id, $uk)) {
            $uk[] = $user_id;
            $u = array('id' => $user_id,
                'name' => 'user_' . $user_id,
                'login' => 'login_' . $user_id,
                'passwd' => hesh_pass($pass, commonConsts::sol_pass1,commonConsts::sol_pass2),
                'jabber' => $jabber,
                'icq' => $icq,
                'email'=>  $email,
                'bilet'=>md5(commonConsts::sol_pass2 . $user_id . commonConsts::sol_pass2),
                'notes'=>'pass: '.$pass
            
                );

               $q->insert('users', $u);
        }
        $a = array('id' => $order_id, 'name' => $account);
        $q->insert('accounts', $a);
        
        $o = array(
            'id' => $order_id,
            'type_id' => $type_id,
            'datetime_begin' => $date_begin,
            'datetime_expire' => $date_end,
            'num_order' => $order_id,
            'user_id' => $user_id,
            'price' => $price,
            'ammount' => 1,
            'portable' => $portable,
            'period_id' => 2,
            'action_id' => 2,
            'protocol_id' => $proto_id,
            'account_id' => $order_id,
            'os' => 'win',
            'notes' => $memo,
                //'server_id'=>$sever_id,
                //'server'=>$sever
        );
//        print_r($o);
//        exit();
        $q->insert('orders', $o);
        if ($sever_id && ($type_id == 1 || $type_id == 2)) {
            $q->query('CALL insertUpdateOrderServersIds(' . $order_id . ',' . $sever_id . ')');
        } elseif ($type_id == 3) { // тип мульти
            //  сервера для мульти 
            $q->query('CALL insertUpdateOrderMultiServersIds(' . $order_id . ', true, false)');
        } elseif ($type_id == 4) { // тип мультидабл
            //  сервера для мультидабл 
            $q->query('CALL insertUpdateOrderMultiServersIds(' . $order_id . ', false, true)');
        }
        $q->commit();
    }
    $order_id++;

    //exit();
}

function createPassword() {
    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
    srand((double) microtime() * 1000000);
    $i = 0;
    $pass = '';
    while ($i <= 7) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}

?>