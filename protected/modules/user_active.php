<?php
$content['name']=$msg['user_active'];
        
if (valid::isMD5($_GET['alink'])){
        $q->qry('Select * From users Where status="" and bilet = "?" ', $_GET['alink']);
        if ($q->numrows()) {
            $q->qry('Update users Set status="1" Where  bilet = "?" ', $_GET['alink']);
            $content['content_page'] = $msg['user_active_success'] ;
        }else{
            $content['content_page'] = $msg['user_active_fail'] ;
        }
}