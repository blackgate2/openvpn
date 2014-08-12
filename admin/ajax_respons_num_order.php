<?php




if ( is_numeric($_GET['term'])){
    require('vars_db.class.php');
    
   
    $term= trim($_GET['term']);
   // echo $term; 
    $where =' num_order  REGEXP \'^'.$term.'\'';
    
   // $where =' and (UPPER(name) LIKE  UPPER(\'%'.$term.'%\')   )' ;
    
    $params= array('table'=>'orders','fields'=>'id,num_order as name','where'=>$where);
    $arra['myData']=  vars_db::__table($params);
    
    $json = json_encode($arra);
    echo $json;
}


