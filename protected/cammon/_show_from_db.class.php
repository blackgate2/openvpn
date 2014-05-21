<?

/**
 * class  show_from_db строит табличку для отчета 
 */
class show_from_db {

    protected $str;
    private $defMaxRow;
    private $maxRow;
    private $initialRow;
    private $p;
    private $totalPages;
    private $totalRow;
    public $prefix_date = 'ts_';
    private $edit_ids = array();
    public $obj;
    private $class_row_color;
    private static $class_odd = "odd";
    private static $class_even = "even";
    private static $class_save = "save";
    public $dataAll = array();
    protected static $url;
    public $is_filter = false; // присутсвие фильтров для отчета на страничке если присутсвует от
    public $str_filters = '';
    private $msg;

    public function __construct($localiza = 0) {
        $this->set_msg($localiza);
    }

    public function set_url($v) {
        self::$url = $v;
    }
    public function set_str($str) {
        $this->str .= $str;
    }
    public function setEditID($v) {
        $this->edit_ids = $v;
    }

    public function set_class_odd($v) {
        self::$class_odd = $v;
    }

    public function set_class_even($v) {
        self::$class_even = $v;
    }

    public function set_class_save($v) {
        self::$class_save = $v;
    }

    private function set_msg($localize) {
        if (is_array($localize)) {
            foreach ($localize as $key => $value) {
                $this->msg[$key] = $value;
            }
        }
    }

    private function pages_nav() {

        if ($this->totalRow <= $this->maxRow)
            $this->totalPages = 1;
        elseif ($this->totalRow % $this->maxRow == 0)
            $this->totalPages = $this->totalRow / $this->maxRow;
        else
            $this->totalPages = ceil($this->totalRow / $this->maxRow);
        if ($this->p > $this->totalPages)
            $this->p = 1;

        $this->initialRow = $this->maxRow * $this->p - $this->maxRow;
        $url = self::$url.'&action=show';
        $url = preg_replace('/\&p\=[0-9]{1,9}/', '', $url);
        $url = preg_replace('/\&maxRow\=[0-9]{1,9}/', '', $url);

        if (!$this->is_filter) {
            $select_go_page = 'window.location = \'' . $url . '&maxRow=' . $this->maxRow . '&p=\'+this.value;';
            $select_maxRow_page = 'window.location = \'' . $url . '&maxRow=\'+this.value+\'&p=' . $this->p . '\'';
            $href_back = 'href="' . $url . '&maxRow=' . $this->maxRow . '&p=' . ($this->p - 1) . '"';
            $href_next = 'href="' . $url . '&maxRow=' . $this->maxRow . '&p=' . ($this->p + 1) . '"';
        } else {
            $select_go_page = 'document.form_filter.p.value=this.value;document.form_filter.submit();';
            $select_maxRow_page = 'document.form_filter.maxRow.value=this.value;document.form_filter.submit();';

            $href_back = 'onclick="document.form_filter.p.value=' . ($this->p - 1) . ';document.form_filter.submit();"';
            $href_next = 'onclick="document.form_filter.p.value=' . ($this->p + 1) . ';document.form_filter.submit();"';
        }



        //// -- ���������
        $back = ($this->p > 1) ? '<a ' . $href_back . ' class="page ui-corner-all">' . $this->msg['back'] . "</a>" : "";
        $next = ($this->p < $this->totalPages) ? '<a ' . $href_next . ' class="page ui-corner-all">' . $this->msg['forv'] . "</a>" : "";
        $max = ($this->totalPages < 10) ? $this->totalPages : 10;
        if ($this->p > $max && $this->p <= ($this->totalPages - $max)) {
            $b = $this->p;
            $e = $this->p + $max;
        } elseif ($this->p > ($this->totalPages - $max) && $this->p <= $this->totalPages) {
            $b = $this->totalPages - $max + 1;
            $e = $this->totalPages;
        } else {
            $b = 1;
            $e = $max;
        }

        $str_numbers = '';
        for ($i = $b; $i <= $e; $i++) {
            $href_numbers = (!$this->is_filter) ? 'href="' . $url . '&maxRow=' . $this->maxRow . '&p=' . $i . '"' : 'onclick="document.form_filter.p.value=' . $i . ';document.form_filter.submit();"';
            $str_numbers.= ( $this->p == $i ) ? '<span class="page_active">' . $i . '</span> ' : '<a ' . $href_numbers . ' class="page ui-corner-all">' . $i . '</a> ';
        }
        $srt_num_pages = $back . ' ' . $str_numbers . ' ' . $next;

        $html_select_rows = '<select class="maxRow ui-widget-content ui-corner-all" name=maxRow onchange="' . $select_maxRow_page . '"><!--option value=' . $this->totalRow . '>' . $this->msg['all_rec'] . '</option-->';
        $sel_val = '';
        $maxRowTotal = ($this->totalRow >= ($this->defMaxRow * $this->defMaxRow)) ? $this->defMaxRow * $this->defMaxRow : $this->totalRow;

        for ($i = $this->defMaxRow; $i <= $maxRowTotal; $i+=$this->defMaxRow) {
            $sel_val = ($this->maxRow == $i ) ? "selected" : "";
            $html_select_rows.="<option value='$i' $sel_val>$i</option>";
        }
        $sel_val = '';
        $html_select_rows.='</select> ';

        $html_select_pages = '<select class="go_page ui-widget-content ui-corner-all" name=go_page onchange="' . $select_go_page . '">';
        for ($i = 1; $i <= $this->totalPages; $i++) {
            $sel_val = ($this->p == $i) ? "selected" : "";
            $html_select_pages.="<option value='$i' $sel_val>$i</option>";
        }
        $html_select_pages.="</select>";

        return array($html_select_rows, $html_select_pages, $srt_num_pages);
    }

    private function setMaxRow() {
        if ($_SESSION[$this->obj['table']]['maxRow'])
            $this->maxRow = $_SESSION[$this->obj['table']]['maxRow'];
        elseif ($this->obj['defMaxRow']) {
            $this->maxRow = $this->obj['defMaxRow'];
        } else {
            $this->maxRow = 50;
        }
    }

    private function setNumPage() {


        if (is_numeric($_SESSION[$this->obj['table']]['p']))
            $this->p = $_SESSION[$this->obj['table']]['p'];
        else
            $this->p = 1;
    }

    private function setDefMaxRow() {
        $this->defMaxRow = $this->obj['defMaxRow'];
    }

    public function getMaxRow() {
        return $this->maxRow;
    }

    public function getInitialRow() {
        return $this->initialRow;
    }

    public function nav($numrows) {
        $this->totalRow = $numrows;
        $this->setMaxRow();
        $this->setNumPage();
        $this->setDefMaxRow();
        if (count($this->dataAll))
        list($str_rows, $str_pages, $str_num_pages) = $this->pages_nav();
        // echo '<pre>'.$this->maxRow.'-'.  $this->defMaxRow.'--'.'</pre>';
//        $this->obj['limit'] = $this->maxRow;
//        $this->obj['offset'] = $this->initialRow;
        
        $ret = '<div class="nav_info">
                ' . (($this->msg['total_pages']) ? $this->msg['total_pages'] . ':<strong>' . $this->totalPages . '</strong>&nbsp;&nbsp;' : '') . '
                ' . (($this->msg['total_rec']) ? $this->msg['total_rec'] . ': <strong>' . $this->totalRow . ' </strong>' : '') . '
                </div>
                ' . (($this->totalRow > $this->maxRow ) ? '<div class="nav_rows" >' . $str_rows . ' </div> <div class="nav_pages" >' . $str_num_pages . '</div>' : '');
        return ($this->obj['isNav']) ? $ret : '';
    }

    public function calsSumCells($cells) {
        $co = count($this->dataAll);
        for ($i = 0; $i < $co; $i++) {
            foreach ($cells as $k) {
                $this->dataAll['total'][$k]+= str_replace(' ', '', $this->dataAll[$i][$k]);
            }
        }
    }

    public function show() {

        // ------------------------- шапка -------------------------  //

        if (isset($this->obj['total_row']))
            $this->calsSumCells($this->obj['total_row']);
        /** ------ добавлям id в шапку ------------ */
        $titles = $this->obj['titles'];
        $names = $this->obj['fields'];
        $colspan = count($titles);
        $titles_table = array();
        if ($this->obj['isEdit']) {
            array_push($titles_table, $this->msg['edit']);
            $colspan++;
        }
        if ($this->obj['isCheck']) {
            $this->msg['checkAll'].=forms::checkbox(array('name' => 'maincheck'));
            array_push($titles_table, $this->msg['checkAll']);
            $colspan++;
        }
        for ($i = 0; $i < count($titles); $i++) {
            $names[$i] = trim($names[$i]);

            if ($this->obj['isSortbl'] && $names[$i] && !in_array($names[$i], $this->obj['nonSortblFields'])) {
                $orderfield = (!strstr($names[$i], " ")) ? $names[$i] : strrchr($names[$i], " ");
                $orderfield = trim(trim($orderfield), '_vw');
                if (!$this->is_filter) {
                    $hrefup = 'href="' . self::$url . '&action=show&order=' . $orderfield . '&order_dir="';
                    $hrefdown = 'href="' . self::$url . '&action=show&order=' . $orderfield . '&order_dir=desc"';
                } else {
                    $hrefup = 'href="#" onclick="document.form_filter.order.value=\'' . $orderfield . '\';document.form_filter.order_dir.value=\'\';document.form_filter.submit();"';
                    $hrefdown = 'href="#" onclick="document.form_filter.order.value=\'' . $orderfield . '\';document.form_filter.order_dir.value=\'desc\';document.form_filter.submit();"';
                }


                if ($orderfield == $this->obj['order']) {
                    if ($this->obj['order_dir'] == 'desc') {
                        $titles[$i] = '<a ' . $hrefup . ' class="order">' . $titles[$i] . '</a>&nbsp;<span class="ui-icon ui-icon-carat-1-n" style="float:left"></span>';
                    } else {
                        $titles[$i] = '<a ' . $hrefdown . ' class="order">' . $titles[$i] . '</a>&nbsp;<span class="ui-icon ui-icon-carat-1-s" style="float:left"></span>';
                    }
                } else {
                    $titles[$i] = '<a ' . $hrefup . ' class="order">' . $titles[$i] . '</a>';
                }
            }
            array_push($titles_table, $titles[$i]);
        }
        if ($this->obj['isCopy']) {
            array_push($titles_table, $this->msg['copy']);
            $colspan++;
        }
        if ($this->obj['isDel']) {
            array_push($titles_table, $this->msg['dell']);
            $colspan++;
        }


        $this->str.= '
            
        <table id="tables" class="display"><thead>';
        $this->str.= '<tr  class="title"><th colspan="' . $colspan . '"><div class="nav_filter">' . $this->str_filters . ' </div>' . $this->nav(count($this->dataAll)) . '</th></tr>';
        //$this->str.= '<tr  class="title1"><th>' . implode('</th><th class="title">', $titles_table) . '</th></tr>';
        $this->str.= '<tr  class="title1">';
        for ($i = 0; $i < count($titles_table); $i++) {
            $this->str.= '<th class="title" id="title' . $i . '">' . $titles_table[$i] . '</th>';
        }
        $this->str.= '</tr>';
        /**
         * total row
         */
        if (is_array($this->dataAll['total'])) {
            $this->str.= '<tr id="total" class="toRight">';
            $flag = 0;
            foreach ($names as $key) {
                $flag = 0;
                foreach ($this->dataAll['total'] as $k => $v) {
                    if ($key == $k) {
                        $this->str.= '<th class="total">' . html::to_money_format($v) . '</th>';
                        $flag = 1;
                    }
                }
                $this->str.= (!$flag) ? '<th></th>' : '';
            }
//            if ($this->obj['add_rows_form'])
//                foreach ($this->obj['add_rows_form'] as $v) {
//                    $this->str.= '<th></th>';
//                    ;
//                }
            $this->str.= '</tr>';
        }
        $this->str.= '
            
        </thead>
        <tbody>
        ';
        
        /**
         * data rows
         */
        //echo $this->initialRow.' ----------'. $this->maxRow
        $begin = $i = $this->initialRow - 1;
        $end = $this->initialRow + $this->maxRow;
        //echo $begin,' ',$end; 
        for ($i = $begin; $i < $end; $i++) {
            if (is_array($this->dataAll[$i])) {
                $data = $this->dataAll[$i];

                /* --------    money_format    ----------- */
                if ($this->obj['money_format']) {
                    foreach ($data as $k => $v) {
                        if (in_array($k, $this->obj['money_format'])) {
                            $data[$k] = html::to_money_format($v);
                        }
                    }
                }

                if ($this->obj['set_rows_colors'])
                    foreach ($this->obj['set_rows_colors'] as $k => $v) {
                        if (!isset($v['data']))
                            $v['data'] = $data;
                        $this->str.= call_user_func($k, $v);
                    }

                $this->change_class_row($data['id']);
                $this->str.= '<tr class="data row' . $data['id'] . ' ' . $this->class_row_color . '">';

                if ($this->obj['isEdit']) {
                    $this->str.= '<td align="center">' . $this->rowButton(
                                    array(
                                'url' => ($this->obj['isDialog']) ? str_replace('index.php', 'index1.php', self::$url) : self::$url,
                                'action' => 'edit',
                                'title' => $this->msg['edit'],
                                'hint' => $this->msg['edit'],
                                'css' => '  button_row  ui-button-icon-only ' . (($this->obj['isDialog']) ? 'link_dialog_modal' : 'link_go'),
                                'ico' => 'ui-icon-pencil'
                                    ), $data['id'], 'id', $data['name']) . '</td>';
                }
                if ($this->obj['isCheck'] == 1)
                    $this->str.= '<td>' . forms::checkbox(array('name' => 'names_ids[]', 'value' => $data['id'])) . '</td>' . "\n";
                elseif (preg_match('/::/', $this->obj['isCheck']))
                    $this->str.= '<td>' . call_user_func($this->obj['isCheck'], $data) . '</td>' . "\n";

                foreach ($this->obj['fields'] as $field) {
                    //foreach ($data as $field => $v) {
                    $css_class = '';
                    $v = $data[$field];
                    if (is_numeric( str_replace(' ', '', $v))) {
                        $css_class.="toRight";
                    }
                    if ($field == $this->obj['order'] || $field == $this->obj['order'] . '_vw') {
                        $css_class.="order";
                    }
                    $this->str.='<td class=" '.$css_class.' ">';
                    if ($field == 'status') {
                        $this->str.= $this->rowButton(
                                array('url' => self::$url . '&statusNEW=' . ((!$v) ? 1 : 0),
                                            'action' => 'changestatus',
                            'title' => ((!$v) ? $this->msg['status_on'] : $this->msg['status_off']),
                            'hint' => ((!$v) ? $this->msg['status_on'] : $this->msg['status_off']),
                            'css' => '  button_row  link_go ui-button-icon-only ',
                            'ico' => ((!$v) ? 'ui-icon-circle-check' : 'ui-icon-circle-close' ),
                                ), $data['id'], 'id', $data['name']);
                    } elseif ($this->prefix_date && preg_match("/^$this->prefix_date/", $field)) {
                        list($date_time, $hz) = explode('.', $v);
                        $this->str.= strDate::DateTime($date_time);
                    } elseif (preg_match('/date/', $field) && preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $v)) {
                        $this->str.= strDate::Date($v, 'all');
                    } elseif (preg_match('/img/', $field)) {
                        $this->str.= '<img src="' . $this->obj['path_foto'] . $v . '" alt="" border="0">';
                    } else {

                        $this->str.= '<span id="' . $field . $i . '">' . ( (strlen($v) > 1000) ? substr($v, 0, 1000) : $v) . '</span>';
                    }
                    $this->str.='</td>' . "\n";
                    //}
                }
                if ($this->obj['add_rows_form'])
                    foreach ($this->obj['add_rows_form'] as $k => $v) {
                        $this->str.= '<td align="center">';
                        // print_r($v);                        exit();

                        if (strstr($k, '::')) {
                            if (!isset($v['url']))
                                $v['url'] = self::$url;
                            if (!isset($v['data']))
                                $v['data'] = $data;

                            $this->str.= call_user_func($k, $v);
                        }elseif (strstr($k, 'button')) {
                            $this->str.= $this->rowButton($v, $data['id'], 'id', $data['name']);
                        } elseif (strstr($k, 'text')) {
                            $this->str.= $this->rowText($v, $i);
                        } elseif (strstr($k, 'span')) {
                            $this->str.= $this->rowSpan($v, $i);
                        }
                        $this->str.= '</td>' . "\n";
                    }


                if ($this->obj['isCopy'])
                    $this->str.= '<td>' . $this->rowButton(
                                    array('url' => ($this->obj['isDialog']) ? str_replace('index.php', 'index1.php', self::$url) : self::$url,
                                'action' => 'copy',
                                'title' => $this->msg['copy'],
                                'hint' => $this->msg['copy'],
                                'css' => ' button_row ui-button-icon-only ' . (($this->obj['isDialog']) ? 'link_dialog_modal' : 'link_go'),
                                'ico' => 'ui-icon-copy'
                                    ), $data['id'], 'id', $data['name']) . '</td>';

                if ($this->obj['isDel'])
                    $this->str.= '<td>' . $this->rowButton(
                                    array('url' => self::$url,
                                'action' => 'del',
                                'title' => $this->msg['dell'],
                                'hint' => $this->msg['dell'],
                                'css' => ' button_row  link_confirm ui-button-icon-only ',
                                'ico' => 'ui-icon-closethick',
                                'confirm' => $this->msg['confirm_del']
                                    ), $data['id'], 'id', $data['name']) . '</td>';


                $this->str.= "</tr>\n";
            }
        }
        $this->str.= '<tr  class="title bottom"><td colspan="' . $colspan . '"> ' .((count($this->dataAll)>10)? $this->nav(count($this->dataAll)):'') . '</td></tr>';
        $this->str.= '</tbody></table>';
        return $this->str;
    }

    protected static function url($v, $id = '', $id_name = 'id') {
        $url = ($v['url']) ? $v['url'] : self::$url;
        $url.= ($v['table']) ? '&table=' . $v['table'] : '';
        if (preg_match('/action\=show/', self::$url)) {
            $url = str_replace('action=show', '', $url);
        } 
        $url.= ($v['action']) ? '&action=' . $v['action'] : '';
        $url.= ($id) ? '&' . $id_name . '=' . $id : '';
        return $url;
    }

    protected function rowButton($v, $id=0, $id_name = 'id', $hint = '') {
        if ($id)
            $v['url'] = self::url($v, $id, $id_name);
        $v['hint'] .= ' :: ' . $hint;
        return forms::button($v);
    }

    protected function rowText($v, $i) {
        //if ($v['name']) $v['name'] = $v['name'].$i;
        if ($v['id'])
            $v['id'] = $v['id'] . $i;
        return forms::text($v);
    }

    protected function rowSpan($v, $i) {
        //if ($v['name'])$v['name'] = $v['name'].$i;
        if ($v['id'])
            $v['id'] = $v['id'] . $i;
        return forms::span($v);
    }

    public function actions_buttons() {

        $str = '';
        if (is_array($this->obj['actions_pannel']))
        foreach ($this->obj['actions_pannel'] as $v) {
                $v['url'] = self::url($v);
            $str.= forms::button($v);
        }

        return $str;
    }

    private function change_class_row($id) {


        if ($this->edit_ids[0] && in_array($id, $this->edit_ids)) {
            $this->class_row_color = self::$class_save;
        } elseif ($this->class_row_color == self::$class_odd) {
            $this->class_row_color = self::$class_even;
        } else {
            $this->class_row_color = self::$class_odd;
        }

        return $this->class_row_color;
    }

}

?>