<?php




if ($_GET['term']){
    require('vars_db.class.php');
    $term=trim($_GET['term']);
    $where =' UPPER(name) LIKE  UPPER(\'%'.$term.'%\')  '
            . ' or  UPPER(icq) LIKE  UPPER(\'%'.$term.'%\')  '
            . ' or  UPPER(email) LIKE  UPPER(\'%'.$term.'%\')  '
            . ' or  UPPER(skype) LIKE  UPPER(\'%'.$term.'%\')  '
            . ' or  UPPER(jabber) LIKE  UPPER(\'%'.$term.'%\')  '
            . ' or  UPPER(login) LIKE  UPPER(\'%'.$term.'%\')  '
            . ' or  UPPER(notes) LIKE  UPPER(\'%'.$term.'%\')  ';
    
   // $where =' and (UPPER(name) LIKE  UPPER(\'%'.$term.'%\')   )' ;
    $arra['myData']=  vars_db::order_users_filter('WHERE  '.$where);
    
    $json = json_encode($arra);
    echo $json;
}


