<?php

$t=$nav->pre.'news';
$fields="$t.id,$t.img, DATE_FORMAT($t.date, '%d.%m.%Y') as date, $t.name";
$where="Where  news.status = '1'";
$content['content_front_news']='<h4>'.(($nav->lang=='en')?'News':'Новости').':</h4>';
$url="/news/?";

$q->query("Select $fields From $t Where status ='1' Order by $t.date desc Limit 3");
while ($d = $q->getrow()) {
    $content['content_front_news'].= '<li><a href="/news/?id=' . $d['id'] . '">' . $d['name'] . '</a></li>
		';
}

$content['content_front_news'] .= '</ul>';