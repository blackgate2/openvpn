<?php
require(commonConsts::path_cammon.'/forms.class.php');
require(commonConsts::path_cammon.'/class.alert.php');
require(commonConsts::path_protect.'/functions_site.php');

$t=$nav->pre.'news';
$content['name']=($nav->lang=='en')?'News':'Новости';
$url='/news';
$fields="$t.id,$t.img, DATE_FORMAT($t.date, '%d.%m.%Y') as date, $t.name, $t.content_page";
$where="Where  $t.status = '1' ";
$where.=( is_numeric($_GET['id']))?' and id=?':'';

$q->qry("Select $t.id From $t $where ",$_GET['id']);

$totalRow=$q->numrows();
list($numpages,$tmp_sel,$totalPages,$initialRow)=pages_nav($p,$maxRow,$totalRow,$url,5);

$limit="Limit $initialRow, $maxRow";

$tmp = ($totalRow > $maxRow) ? "<div style='display: block;'>$numpages</div>" : '';
$content['content_page'].=$tmp . '';


$q->qry("Select $t.name, $t.content_page, date From $t $where ",$_GET['id']);
//
while($d = $q->getrow()){

	if (isset($_GET['id'])){
            $content['name']=$d['name'];
            $content['title']=$d['title'];

            $content['content_page'].= '
                    '.(($d['img'])?'<a href="/images/news/'.$d['img'].'"  title="'. strip_tags(unesc($d['name'])).'" class="lightbox">

                                        <img class=" class="folio"" alt="'.strip_tags(unesc($d['name'])).'"  src="/images/news/small_'.$d['img'].'" title="'.strip_tags(unesc($d['name'])).'"><br>

                                        </a>
                                         ':'').'                   
                    '.$d['content_page'].'
                    
            ';
            $content['content_page'] .= '<p> </p><p class="back"><a href="' . $url .  '" class="back">'.(($nav->lang=='en')?'all news':'все новости').'</a></p>';
        }else{
            $content['content_page'].= '

                    
                  <p><a href="' . $url . '/?id=' . $d['id'] . '"><h3>' . $d['name'] . '</h3></a></p>
								
		';
        }
  }

$content['content_page'].=$tmp . '';
