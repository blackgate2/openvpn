<?
$content['content_front_faq'] = '<h4>' . (($nav->lang == 'en') ? 'FAQ' : 'Вопросы') . ':</h4><ul>';
$url = "/faq/$nav->lang/?";

$q->query('Select f.id,   f.name
           From '.$nav->pre.'faq f 
           WHERE
               f.status = \'1\' and 
               f.is_front = \'1\'
           Order by rand() Limit 3');

while ($d = $q->getrow()) {
    $content['content_front_faq'].= '<li><a href="/faq/?id=' . $d['id'] . '">' . $d['name'] . '</a></li>
		';
}
$content['content_front_faq'] .= '<p class="back"><a href="' . $url .  '" class="back">'.(($nav->lang=='en')?'all questions':'все вопросы').'</a></p>';
$content['content_front_faq'] .= '</ul>';