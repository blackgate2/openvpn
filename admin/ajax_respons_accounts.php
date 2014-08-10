<?php




if ($_GET['term']){
    require('vars_db.class.php');
    
   
    $term=trim($_GET['term']);
    $where =' UPPER(name) LIKE  UPPER(\'%'.$term.'%\')  ';
    
   // $where =' and (UPPER(name) LIKE  UPPER(\'%'.$term.'%\')   )' ;
    
    $params= array('table'=>'accounts','where'=>$where);
    $arra['myData']=  vars_db::__table($params);
    
    $json = json_encode($arra);
    echo $json;
}


