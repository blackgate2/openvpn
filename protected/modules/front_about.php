<?
$content['conten_front_about']='';
$q->query('Select p.name,p.content_page
           From menu m  Left Join pages p On p.id = m.pages_id
           Where m.url="front" Limit 1');
$d = $q->getrow();
$content['conten_front_about']=  $d['content_page'];

