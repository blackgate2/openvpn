<?php
    if ($nav->page!=''){
        $sql = 'Select
                    p.title,
                    p.keywords,
                    p.description,
                    p.name,
                    p.content_page,p.content_page_left
                From '.$nav->pre.'menu m
                Left Join '.$nav->pre.'pages p On p.id = m.pages_id
                Where m.url="' . $nav->page . '" Limit 1';
        $q->query($sql);
    }
    if (is_numeric($page_id)){
        $sql = 'Select
                    p.title,
                    p.keywords,
                    p.description,
                    p.name,
                    p.content_page,p.content_page_left
                From '.$nav->pre.'pages p
                Where p.id="' . $page_id . '" Limit 1';
        $is_right=1;
        $q->qry($sql,$page_id);
    }
    
    if($q->numrows()>0){
        $content = $q->getrow();        
        if (strip_tags(trim($content['content_page']))=='' ){
            $content['content_page']='<p>'.$msg['page_under_construction'].'</p>';
        }
    }else{
        $content['name']='404';
        $content['content_page']='<p>'.$msg['page_not_found'].'</p>';
    }