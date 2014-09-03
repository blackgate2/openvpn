<?php

class alink  {

    
   
    private $q;

    public function __construct($q,$type_id, $protocol_id, $portable,$os,$pre='') {
       // $this->q = $q;
        //parent::__construct($params);
        
       // $this->get_alink();
    }


    private function get_alink($type_id, $protocol_id, $portable,$os,$pre) {
        
        $this->q->qry('SELECT type_id,protocol_id,portable,os,url,yakor
                       FROM forbac_test.?params_pages_ids pp
                       JOIN ?menu m ON m.pages_id= pp.page_id
                       WHERE pp.type_id =?, pp.protocol_id =?, pp.portable =?,pp.os =?', $pre,$pre,$type_id, $protocol_id, $portable,$os);
        
    }


}

?>