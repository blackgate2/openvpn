<?

//----------------
function log_err($msg) {
    return '<div class="log_form_info"><span class="log_error">' . $msg . '</span></div>';
}

function log_in($fio) {
    return '<div class="log_form_info"><span class="log_fio"><b>Здравствуйте:</b> </br></br><i>' . $fio . '</i></span></br></br><a href="/?exit">Выход</div>';
}

function pages_nav(&$p, &$maxRow, $totalRow, $url, $defMaxRow=30) {
    global $msg_show_rec, $msg_show_pages, $msg_back, $msg_forv;
    if (!isset($maxRow) || $maxRow == '')
        $maxRow = $defMaxRow;
    if (!isset($p) || $p == '')
        $p = 1;

    if ($totalRow <= $maxRow)
        $totalPages = 1;
    elseif ($totalRow % $maxRow == 0)
        $totalPages = $totalRow / $maxRow;
    else
        $totalPages = ceil($totalRow / $maxRow);
    if ($p > $totalPages)
        $p = 1;
    if ($totalRow == 0)
        $RowStart = 0;
    else
        $RowStart = $maxRow * $p - $maxRow + 1;
    if ($p == $totalPages)
        $RowEnd = $totalRow;
    else
        $RowEnd = $maxRow * $p;
    $initialRow = $maxRow * $p - $maxRow;


    $url = preg_replace('/\&p\=[0-9]{1,9}/', '', $url);
    $url = preg_replace('/\&maxRow\=[0-9]{1,9}/', '', $url);

    //// -- ���������
    $back = ($p > 1) ? "<a href=$url&maxRow=$maxRow&p=" . ($p - 1) . ">$msg_back</a> " : "";
    $next = ($p < $totalPages) ? " <a href=$url&maxRow=$maxRow&p=" . ($p + 1) . ">$msg_forv</a>" : "";
    $max = ($totalPages < 10) ? $totalPages : 10;
    if ($p > $max && $p <= ($totalPages - $max)) {
        $b = $p;
        $e = $p + $max;
    } elseif ($p > ($totalPages - $max) && $p <= $totalPages) {
        $b = $totalPages - $max + 1;
        $e = $totalPages;
    } else {
        $b = 1;
        $e = $max;
    }

    $tmp = '';
    for ($i = $b; $i <= $e; $i++) {
        if ($p == $i

            )$tmp.= "<b>$i</b>&nbsp; ";
        else
            $tmp.= "<a href=$url&maxRow=$maxRow&p=$i>$i</a>&nbsp; ";
    }
    $srt_num_pages = "$back $tmp $next";
    $sel_val = '';
    $str_select = "
			<script>
			function go_page(i){
			window.location = '$url&maxRow=$maxRow&p='+i;
			}
			function maxRow_page(i){
			window.location = '$url&p=$p&maxRow='+i;
			}

			</script>
            $msg_show_rec: <select name=maxRow onchange=\"maxRow_page(this.value)\">";
    $maxRowtotal = ($totalRow >= ($defMaxRow * $defMaxRow)) ? $defMaxRow * $defMaxRow : $totalRow;
    for ($i = $defMaxRow; $i <= $maxRowtotal; $i+=$defMaxRow) {
        $sel_val = ($maxRow == $i) ? "selected" : "";
        $str_select.="<option value='$i' $sel_val>$i</option>";
    }
    $sel_val = '';
    $str_select.="</select><br>
            $msg_show_pages: <select name=re onchange=\"go_page(this.value)\">";
    for ($i = 1; $i <= $totalPages; $i++) {
        $sel_val = ($p == $i) ? "selected" : "";
        $str_select.="<option value='$i' $sel_val>$i</option>";
    }
    $str_select.="</select>";

    return array($srt_num_pages, $str_select, $totalPages, $initialRow);
}

function get_left_right($id) {
  
    if (is_numeric($id))
        $q->query('Select  `left`,`right` From `cat_group` Where id = ' . $id);
    return $q->getrow();
}



function trans_text_search($text_search) {
    $text_search = trim($text_search);
    $text_search = preg_replace('/[^A-��-�\s\w\d_]/is', '', $text_search);
    $text_search = mysql_real_escape_string($text_search);
    $text_search = preg_replace('/\s+/is', ' ', $text_search);
    $text_search = "+" . preg_replace('/ /is', ' +', $text_search);
    return $text_search;
}






?>