<?
$content['content_front_partners']='';
$q->query('Select img,name,url,descr From '.$nav->pre.'partners Where status =\'1\' Order by id desc');
$content['content_front_partners']= '
                            <span>'.(($nav->lang=='en')?'Our partners':'Наши партнеры').':</span>
                            <ul id="mycarousel" class="jcarousel-skin-tango">
                            ';

while($d = $q->getrow()){
	$content['content_front_partners'].= '
                <li>
                    <a href="'.$d['url'].'" title="'.strip_tags($d['descr']).'" target="_blank"><img src="/images/pages/partners_logos/'.$d['img'].'" alt="'.strip_tags($d['descr']).'"></a>
                </li>';

  }
$content['content_front_partners'].= '           </ul>';