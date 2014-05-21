<?
$t=$nav->pre.'news';
$content['name']=($nav->lang=='en')?'News':'Новости';
$url='/news/'.$nav->lang;
$fields="$t.id,$t.img, DATE_FORMAT($t.date, '%d.%m.%Y') as date, $t.name, $t.content_page";
$where="Where  $t.status = '1' ";
$where.=( is_numeric($_GET['id']))?' and id=?':'';

$q->qry("Select $t.id From $t $where ",$_GET['id']);
$totalRow=$q->numrows();
list($numpages,$tmp_sel,$totalPages,$initialRow)=pages_nav($p,$maxRow,$totalRow,$url,5);

$limit="Limit $initialRow, $maxRow";

$tmp = ($totalRow > $maxRow) ? "<div style='display: block;'>$numpages</div>" : '';
$content['content_page'].=$tmp . '';
$q->qry("Select $fields From $t $where Order by $t.date desc $limit",$_GET['id']);
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
                    
            <a href="'.$url.'">&laquo; '.(($nav->lang=='en')?'back':'вернуться').'</a>';

        }else{
            $content['content_page'].= '

                    
                  <p><a href="' . $url . '/?id=' . $d['id'] . '"><h3>' . $d['name'] . '</h3></a></p>
								
		';
        }
  }

$content['content_page'].=$tmp . '';
