<?
require(commonConsts::path_cammon.'/forms.class.php');
require(commonConsts::path_cammon.'/class.alert.php');
require(commonConsts::path_protect.'/functions_site.php');
$content['name']=($nav->lang=='en')?'FAQ':'Вопросы';
$url='/faq/'.$nav->lang;
if (isset($_REQUEST['p']))
    $p= $_REQUEST['p'];
if (isset($_REQUEST['maxRow']))
    $maxRow= $_REQUEST['maxRow'];

$q->query('Select f.id From '.$nav->pre.'faq f JOIN '.$nav->pre.'faq_group g ON g.id=f.group_id and f.status = \'1\' ');
$totalRow=$q->numrows();
list($numpages,$tmp_sel,$totalPages,$initialRow)=pages_nav($p,$maxRow,$totalRow,$url.'/?',100);
$sql= 'Select f.id,f.name,f.name_a, g.name as gname From '.$nav->pre.'faq f 
       JOIN '.$nav->pre.'faq_group g ON g.id=f.group_id and f.status = \'1\'
        '.(( is_numeric($_GET['id']))?' and f.id=?':'').'
       Order by gname,f.weight
       Limit '.$initialRow.', '.$maxRow;
$q->qry($sql,$_GET['id']);
$tmp = (!$_GET['id'] && $totalRow > $maxRow) ? "<div style='display: block; text-align:right'>$numpages</div>" : '';
$content['content_page'].=$tmp . '';

while($d = $q->getrow()){
	if (isset($_GET['id'])){
            $content['name']=$d['name'];
            $content['content_page'].= $d['name_a'];
            //forms::set_msg($msg) ;
           $content['content_page'] .= '<p> </p><p class="back"><a href="' . $url .  '" class="back">'.(($nav->lang=='en')?'back':'вернуться').'</a></p>';

        }else{
            
           
            $content['content_page'].= (($gname!=$d['gname'])?'<h3>'.$d['gname'].'</h3>':'').'
                  <div class="faq"><a href="' . $url . '/?id=' . $d['id'] . '"><span>' . $d['name'] . '</span></a></div>
		';
            $gname=$d['gname'];
        }
  }

$content['content_page'].=$tmp ;


