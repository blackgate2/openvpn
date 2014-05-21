<?php

class order_row extends show_from_db {

    static function rowColors($a) {

        $inx = $a['data']['ostatus'];
        if ($inx) {/* registered */
            parent::set_class_odd($a[$inx]['odd']);
            parent::set_class_even($a[$inx]['even']);
        }
    }
    static function Buttons($a) {
        //print_r($a);
        //exit();
        
        $data= $a['data']['ptr_id'];
        if ($a[$inx]) {
            foreach ($a[$inx] as $v) {
                /* течто уже напечатанные*/
                if ($num_pr>0 && $v['action']=='print'){
                    $v['title']=  ($num_pr>0)?L_(273):L_(274);
                     $v['hint']= L_('common.print_duble_hint').' #'.($num_pr+1);
                }

                
                $v['url'] = ($v['url']) ? $v['url'] : $cur_url;
                $v['url'].='&action='.$v['action'];
                $v['url'].='&id='.$id;
                $v['id']='button_'.$v['action'].'_'.$id;
                $str.= '';// разделяем кнопочки 
                if ($v['action']=='pay_patron'){
                    
                }
                $str.= forms::hidden(array('id'=>'ptr'.$id,'value'=>$ptr_id));
                $str.= forms::button($v);
            }
            //exit($str);
            return $str;
        }
    }

}

?>