<?
$content['conten_front_imgs']='';
$q->query("Select name,img From imgs Where status ='1' Order by id  ");
while($d = $q->getrow()){
    $content['conten_front_imgs'].=  '<img src="/images/fornt_images/'.$d['img'].'">';
}
