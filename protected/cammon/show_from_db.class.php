<?php

/**
 * class  show_from_db строит табличку для отображения данных из DB 
 */
class show_from_db {

    private $defMaxRow;
    private $maxRow;
    private $initialRow;
    private $p;
    private $totalPages;
    private $totalRow;
    private $prefix_sufix_date_field;
    private $edit_ids;
    public $obj;
    private $class_row_color;
    private static $class_odd;
    private static $class_even;
    private static $class_save;
    public $dataAll;
    public static $url;
    public $is_filter; // присутсвие фильтров 
    public $str_filters;
    protected static $msg;
    protected static $db;
    private $nav_place;
    private $is_nav;
    private $is_nav_rows;
    private $is_nav_pages;

    public function __construct($localiza = 0, $obj = null, $db = null) {
        $this->set_msg($localiza);
        $this->edit_ids = array();
        $this->prefix_sufix_date_field = array('ts', 'date');
        self::$class_odd = "odd";
        self::$class_even = "even";
        self::$class_save = "save";
        $this->dataAll = array();
        $this->is_filter = false;
        $this->str_filters = '';

        if ($obj) {
            $this->obj = $obj;
        }
        $this->nav_place = ($this->obj['nav_place']) ? $this->obj['nav_place'] : 'top_bottom';
        $this->is_nav = (isset($this->obj['isNav'])) ? $this->obj['isNav'] : true;
        $this->is_nav_rows = (isset($this->obj['is_nav_rows'])) ? $this->obj['is_nav_rows'] : true;
        $this->is_nav_pages = (isset($this->obj['is_nav_pages'])) ? $this->obj['is_nav_pages'] : true;
        if ($this->obj['url'])
            self::$url = $this->obj['url'];
        if ($db)
            $this->db = $db;
    }

    public function set_data() {
        //exit()
        if ($this->obj['fields_sql'])
            $fields = $this->obj['fields_sql'];
        elseif (is_array($this->obj['fields']))
            $fields = implode(',', $this->obj['fields']);

        if ($fields)
            $this->dataAll = $this->db->select(
                    $this->obj['table_view'], $fields, $this->obj['where'], $this->obj['group'], $this->obj['order'] . ' ' . $this->obj['order_dir'], $this->obj['limit'], $this->obj['offset'], $this->obj['bind']);
    }

    public function data_reindex() {
        $this->dataAll = array_values($this->dataAll);
    }

    public function set_url($v) {
        self::$url = $v;
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

    protected function set_msg($localize) {
        if (is_array($localize)) {
            foreach ($localize as $key => $value) {
                self::$msg[$key] = $value;
            }
        }
    }

    public function url_nav() {
        $first = (preg_match('/\?/', self::$url)) ? '?' : '&';
        return self::$url . (($_SESSION[$this->obj['table']]['maxRow']) ? $first . 'maxRow=' . $_SESSION[$this->obj['table']]['maxRow'] : '') . (($_SESSION[$this->obj['table']]['p']) ? '&p=' . $_SESSION[$this->obj['table']]['p'] : '');
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

        if (!$this->is_filter) {
            $select_go_page = 'window.location = \'' . self::$url . '&maxRow=' . $this->maxRow . '&p=\'+this.value;';
            $select_maxRow_page = 'window.location = \'' . self::$url . '&maxRow=\'+this.value+\'&p=' . $this->p . '\'';
            $href_back = 'href="' . self::$url . '&maxRow=' . $this->maxRow . '&p=' . ($this->p - 1) . '"';
            $href_next = 'href="' . self::$url . '&maxRow=' . $this->maxRow . '&p=' . ($this->p + 1) . '"';

            $href_begin = 'href="' . self::$url . '&maxRow=' . $this->maxRow . '&p=1"';
            $href_end = 'href="' . self::$url . '&maxRow=' . $this->maxRow . '&p=' . $this->totalPages . '"';
        } else {
            $select_go_page = 'document.form_filter.p.value=this.value;document.form_filter.submit();';
            $select_maxRow_page = 'document.form_filter.maxRow.value=this.value;document.form_filter.submit();';

            $href_back = 'onclick="document.form_filter.p.value=' . ($this->p - 1) . ';document.form_filter.submit();"';
            $href_next = 'onclick="document.form_filter.p.value=' . ($this->p + 1) . ';document.form_filter.submit();"';
        }



        //// -- ���������
        $back = ($this->p > 1) ? '<a ' . $href_back . ' class="page ui-corner-all">' . self::$msg['back'] . "</a>" : "";
        $next = ($this->p < $this->totalPages) ? '<a ' . $href_next . ' class="page ui-corner-all">' . self::$msg['forv'] . "</a>" : "";

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
            $href_numbers = (!$this->is_filter) ? 'href="' . self::$url . '&maxRow=' . $this->maxRow . '&p=' . $i . '"' : 'onclick="document.form_filter.p.value=' . $i . ';document.form_filter.submit();"';
            $str_numbers.= ( $this->p == $i ) ? '<span class="page_active">' . $i . '</span> ' : '<a ' . $href_numbers . ' class="page ui-corner-all">' . $i . '</a> ';
        }

        $beg = ($this->p > 1) ? '<a ' . $href_begin . ' class="page ui-corner-all">' . self::$msg['beg'] . '</a>' : "";
        $end = ($this->p < $this->totalPages) ? '<a ' . $href_end . ' class="page ui-corner-all">' . self::$msg['end'] . '</a>' : "";
        $srt_num_pages = $beg . ' ' . $back . ' ' . $str_numbers . ' ' . $next . ' ' . $end;

        $html_select_rows = '<select class="maxRow ui-widget-content ui-corner-all" name=maxRow onchange="' . $select_maxRow_page . '"><!--option value=' . $this->totalRow . '>' . self::$msg['all_rec'] . '</option-->';
        $sel_val = '';
        $maxRowTotal = ($this->totalRow >= ($this->defMaxRow * $this->defMaxRow)) ? $this->defMaxRow * $this->defMaxRow : $this->totalRow;
        $maxRowTotal+=$this->defMaxRow;
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
            $this->maxRow = 10;
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
        if (!empty($this->dataAll))
            list($str_rows, $str_pages, $str_num_pages) = $this->pages_nav();
        // echo '<pre>'.$this->maxRow.'-'.  $this->defMaxRow.'--'.'</pre>';
//        $this->obj['limit'] = $this->maxRow;
//        $this->obj['offset'] = $this->initialRow;
        $nav_rows = ($this->is_nav_rows) ? '<div class="nav_rows" >' . $str_rows . ' </div> ' : '';
        $nav_pages = ($this->is_nav_pages) ? '<div class="nav_pages" >' . $str_num_pages . '</div>' : '';
        $ret = '<div class="nav_info">
                ' . ((self::$msg['total_pages']) ? self::$msg['total_pages'] . ':<strong>' . $this->totalPages . '</strong>&nbsp;&nbsp;' : '') . '
                ' . ((self::$msg['total_rec']) ? self::$msg['total_rec'] . ': <strong>' . $this->totalRow . ' </strong>' : '') . '
                </div>
                ' . $nav_rows . (($this->totalRow > $this->maxRow ) ? '<div class="nav_pages" >' . $nav_pages . '</div>' : '');
        return ($this->is_nav) ? $ret : '';
    }

    public function cals_total_sum() {
        if (is_array($this->dataAll))
            foreach ($this->dataAll as $v) {
                foreach ($this->obj['total_row'] as $k) {
                    $this->dataAll['total'][$k]+= $v[$k];
                }
            }
    }

    public function column_filter() {
        $i = 0;
        if (is_array($this->obj['column_filter'])) {
            foreach ($this->obj['fields'] as $v) {

                if (in_array($v, $this->obj['column_filter'])) {
                    // echo $v.'-'.$i;
                    unset($this->obj['fields'][$i]);
                    unset($this->obj['titles'][$i]);
                }
                $i++;
            }
            $this->obj['fields'] = array_values($this->obj['fields']);
            $this->obj['titles'] = array_values($this->obj['titles']);
        }
    }

    public function show() {
        $str_html = '';
        /*
         * формирование шапки 
         */

        /* суммы по колонкам */
        if (isset($this->obj['total_row']))
            $this->cals_total_sum($this->obj['total_row']);

        /* убираем колонки */
        if ($this->obj['column_filter']) {
            $this->column_filter();
        }



        /** ------ добавлям id в шапку ------------ */
        $titles = $this->obj['titles'];
        $names = $this->obj['fields'];
        $colspan = count($titles);
        $titles_table = array();
        if ($this->obj['isEdit']) {
            array_push($titles_table, self::$msg['edit']);
            $colspan++;
        }
        if ($this->obj['isCheck']) {
            self::$msg['checkAll'].=forms::checkbox(array('name' => 'maincheck'));
            array_push($titles_table, self::$msg['checkAll']);
            $colspan++;
        }
        for ($i = 0; $i < count($titles); $i++) {
            $names[$i] = trim($names[$i]);

            if ($this->obj['isSortbl'] && $names[$i] && !in_array($names[$i], $this->obj['nonSortblFields'])) {
                $orderfield = (!strstr($names[$i], " ")) ? $names[$i] : strrchr($names[$i], " ");
                //$orderfield = preg_replace ('/\_v\w\$/','',trim($orderfield));
                if (!$this->is_filter) {
                    $hrefup = 'href="' . self::$url . '&order=' . $orderfield . '&order_dir="';
                    $hrefdown = 'href="' . self::$url . '&order=' . $orderfield . '&order_dir=desc"';
                } else {
                    $hrefup = 'href="#" onclick="document.form_filter.order.value=\'' . $orderfield . '\';document.form_filter.order_dir.value=\'\';document.form_filter.submit();"';
                    $hrefdown = 'href="#" onclick="document.form_filter.order.value=\'' . $orderfield . '\';document.form_filter.order_dir.value=\'desc\';document.form_filter.submit();"';
                }


                if ($orderfield == $this->obj['order']) {
                    if ($this->obj['order_dir'] == 'desc') {
                        $titles[$i] = '<a ' . $hrefup . ' class="order">' . $titles[$i] . '</a>&nbsp;<span class="ui-icon ui-icon-arrowthick-1-n" style="float:left"></span>';
                    } else {
                        $titles[$i] = '<a ' . $hrefdown . ' class="order">' . $titles[$i] . '</a>&nbsp;<span class="ui-icon ui-icon-arrowthick-1-s" style="float:left"></span>';
                    }
                } else {
                    $titles[$i] = '<a ' . $hrefup . ' class="order">' . $titles[$i] . '</a>';
                }
            }
            array_push($titles_table, $titles[$i]);
        }
        if ($this->obj['isCopy']) {
            array_push($titles_table, self::$msg['copy']);
            $colspan++;
        }
        if ($this->obj['isDel']) {
            array_push($titles_table, self::$msg['dell']);
            $colspan++;
        }


        $str_nav = $this->nav(count($this->dataAll));
        $str_html.= '
        <table id="tables" class="display"><thead>';
        $str_html.= ( $this->str_filters || $str_nav) ? '<tr  class="title"><th colspan="' . $colspan . '"><div class="nav_filter">' . $this->str_filters . ' </div>' . ((preg_match('/top/', $this->nav_place) ) ? $str_nav : '') . '</th></tr>' : '';
        //$str_html.= '<tr  class="title1"><th>' . implode('</th><th class="title">', $titles_table) . '</th></tr>';
        $str_html.= '<tr  class="title1">';
        for ($i = 0; $i < count($titles_table); $i++) {
            $str_html.= '<th class="title" id="title' . $i . '">' . $titles_table[$i] . '</th>';
        }
        $str_html.= '</tr>';
        /**
         * total row
         */
        if (is_array($this->dataAll['total'])) {
            $str_html.= '<tr id="total" class="toRight">';
            $flag = 0;
            if ($this->obj['isCheck']) {
                $str_html.= '<th></th>';
            }
            foreach ($names as $key) {
                $flag = 0;
                foreach ($this->dataAll['total'] as $k => $v) {
                    if ($key == $k) {
                        $total = (in_array($k, $this->obj['money_format'])) ? common::to_money_format($v) : $v;
                        $str_html.= '<th class="total">' . $total . '</th>';
                        $flag = 1;
                    }
                }
                $str_html.= (!$flag) ? '<th></th>' : '';
            }

            $str_html.= '</tr>';
        }
        $str_html.= '
            
        </thead>
        <tbody>
        ';

        /**
         * data rows
         */
        //echo $this->initialRow.' ----------'. $this->maxRow
        $begin = $i = $this->initialRow;
        $end = $this->initialRow + $this->maxRow;
        //echo $begin,' ',$end; 
        for ($i = $begin; $i < $end; $i++) {
            if (is_array($this->dataAll[$i])) {
                $data = $this->dataAll[$i];

                /* --------    money_format    ----------- */
                if (is_array($this->obj['money_format'])) {
                    foreach ($data as $k => $v) {
                        if (in_array($k, $this->obj['money_format'])) {
                            $data[$k] = common::to_money_format($v);
                        }
                    }
                }
                /* --------    triple format    ----------- */
                if (is_array($this->obj['triple_format'])) {
                    foreach ($data as $k => $v) {
                        if (in_array($k, $this->obj['triple_format'])) {
                            $data[$k] = common::to_numder_format($v);
                        }
                    }
                }

                if ($this->obj['set_rows_colors'])
                    foreach ($this->obj['set_rows_colors'] as $k => $v) {
                        if (!isset($v['data']))
                            $v['data'] = $data;
                        $str_html.= call_user_func($k, $v);
                    }

                $this->change_class_row($data['id']);
                $str_html.= '<tr class="data row' . $data['id'] . ' ' . $this->class_row_color . '">';

                if ($this->obj['isEdit']) {
                    $str_html.= '<td align="center">' . $this->edit_button($data) . '</td>';
                }
                if ($this->obj['isCheck'] == 1)
                    $str_html.= '<td class="checkbox_in_table">' . forms::checkbox(array('name' => 'names_ids[]', 'id' => 'checkbox_' . $data['id'], 'value' => $data['id'], 'css' => 'rows_checked')) . '</td>' . "\n";
                elseif (preg_match('/::/', $this->obj['isCheck']))
                    $str_html.= '<td class="checkbox_in_table">' . call_user_func($this->obj['isCheck'], $data) . '</td>' . "\n";

                foreach ($this->obj['fields'] as $field) {

                    $css_class = '';
                    $v = $data[$field];
                    if (is_numeric(str_replace(' ', '', $v))) {
                        $css_class.=" toRight ";
                    }
                    if ($field == $this->obj['order'] || $field == $this->obj['order'] . '_vw') {
                        $css_class.=" order ";
                    }
                    $str_html.='<td class="' . $field . ' ' . $css_class . ' " id="' . $field . $data['id'] . '">';
                    if ($field == 'status') {
                        if (isset($this->obj['status_titles'])) {
                            $title = ((!$v) ? $this->obj['status_titles'][0] : $this->obj['status_titles'][1]);
                        } else {
                            $title = ((!$v) ? self::$msg['status_on'] : self::$msg['status_off']);
                        }
                        if (isset($this->obj['status_hints'])) {
                            $hint = ((!$v) ? $this->obj['status_hints'][0] : $this->obj['status_hints'][1]) . ' :: ' . $data['name'];
                        } else {
                            $hint = ((!$v) ? self::$msg['status_on'] : self::$msg['status_off']) . ' :: ' . $data['name'];
                        }
                        $str_html.= $this->rowButton(
                                array(
                                    'url' => self::$url . '&statusNEW=' . ((!$v) ? 1 : 0),
                                    'action' => 'changestatus',
                                    'title' => $title,
                                    'hint' => $hint,
                                    'css' => '  button_row button_status_' . ((!$v) ? 'on' : 'off') . '  link_go ui-button-text-only',
                                    //'ico' => ((!$v) ? 'ui-icon-circle-check' : 'ui-icon-circle-close' ),
                                    'data' => array('id' => $data['id'])
                        ));
                    } elseif ($this->is_ts_date($field) || (is_array($this->obj['date_format']) && in_array($field, $this->obj['date_format']))) {
                        $str_html.= $this->ts_date_time($v);
                    } elseif (preg_match('/img/', $field)) {
                        $str_html.= '<img src="' . $this->obj['path_foto'] . $v . '" alt="" border="0">';
                    } else {

                        $str_html.= '<span id="' . $field . $data['id'] . '" class="' . $field . '">' . ( (strlen($v) > 1000) ? substr($v, 0, 1000) : $v) . '</span>';
                    }

                    if ($this->obj['add_collumn_' . $field]) {
                        foreach ($this->obj['add_collumn_' . $field] as $k => $vv) {
                            if (!isset($vv['url']))
                                $vv['url'] = self::$url;
                            if (!isset($vv['data']))
                                $vv['data'] = $data;
                            if (strstr($k, '::')) {
                                $str_html.= call_user_func($k, $vv);
                            } elseif (strstr($k, 'button')) {
                                $str_html.= $this->rowButton($vv);
                            } elseif (strstr($k, 'text')) {
                                $str_html.= $this->rowText($vv, $data[$vv['key_val']]);
                            } elseif (strstr($k, 'span')) {
                                $str_html.= $this->rowSpan($vv, $i);
                            } elseif (strstr($k, 'checkbox')) {
                                $str_html.= $this->rowCheckbox($vv, $i);
                            }
                        }
                    }
                    $str_html.='</td>' . "\n";
                }


                if ($this->obj['isCopy'])
                    $str_html.= '<td>' . $this->rowButton(
                                    array('url' => ($this->obj['dialog_url_edit']) ? $this->obj['dialog_url_edit'] : self::$url,
                                        'action' => 'copy',
                                        'title' => self::$msg['copy'],
                                        'hint' => self::$msg['copy'] . ' :: ' . $data['name'],
                                        'css' => ' button_row ui-button-icon-only ' . (($this->obj['isDialog']) ? 'link_dialog_modal' : 'link_go'),
                                        'ico' => 'ui-icon-copy',
                                        'data' => array('id' => $data['id'])
                            )) . '</td>';

                if ($this->obj['isDel'])
                    $str_html.= '<td>' . $this->rowButton(
                                    array('url' => self::$url,
                                        'action' => 'del',
                                        'title' => self::$msg['dell'],
                                        'hint' => self::$msg['dell'] . ' :: ' . $data['name'],
                                        'css' => ' button_row  link_confirm ui-button-icon-only ',
                                        'ico' => 'ui-icon-closethick',
                                        'confirm' => self::$msg['confirm_del'],
                                        'data' => array('id' => $data['id'])
                            )) . '</td>';


                $str_html.= "</tr>\n";
            }
        }

        $str_html.= '<tr  class="title bottom"><td colspan="' . $colspan . '"> ' . ((preg_match('/bottom/', $this->nav_place) ) ? $str_nav : '') . '</td></tr>';
        $str_html.= '</tbody></table>';
        return $str_html;
    }

    public function show_as_photo() {
        /**
         * data 
         */
        $str_html = '';
        $begin = $i = $this->initialRow - 1;
        $end = $this->initialRow + $this->maxRow;
        //echo $begin,' ',$end; 
        for ($i = $begin; $i < $end; $i++) {
            if (is_array($this->dataAll[$i])) {
                $data = $this->dataAll[$i];
                foreach ($this->obj['fields'] as $field) {
                    $v = $data[$field];
                    if (preg_match('/img/', $field)) {
                        $str_html.= '<div class="show_as_photo"><img src="' . $this->obj['path_foto'] . $v . '" alt="" border="0">';
                    } else {
                        $str_html.='<br>' . $v;
                    }
                    $str_html.= '</div>';
                }
            }
        }

        return $str_html;
    }

    private function is_ts_date($field) {
        foreach ($this->prefix_sufix_date_field as $value) {
            if (preg_match("/^$value/", $field) || preg_match("/\_$value$/", $field)) {
                return true;
            }
        }
        return false;
    }

    protected function ts_date_time($v) {
        if (valid::isTimeSTAMP($v)) {
            list($date_time, $hz) = explode('.', $v);
            return strDate::DateTime($date_time, $this->obj['date_format_mask']);
        } elseif (valid::isDate($v)) {
            return strDate::Date($v, $this->obj['date_format_mask']);
        } else {
            return $v;
        }
    }

    protected function edit_button($data) {

        if ($this->obj['edit_dep_status'] && $data['status'] == 0)
            $ret = true;
        elseif (!$this->obj['edit_dep_status'])
            $ret = true;
        else
            $ret = false;

        return (($ret) ? $this->rowButton(
                                array(
                                    'url' => ($this->obj['dialog_url_edit']) ? $this->obj['dialog_url_edit'] : self::$url,
                                    'action' => 'edit',
                                    'title' => self::$msg['edit'] . ' :: ' . $data['name'],
                                    'hint' => self::$msg['edit'] . ' :: ' . $data['name'],
                                    'css' => '  button_row  ui-button-icon-only ' . (($this->obj['isDialog']) ? 'link_dialog_modal' : 'link_go'),
                                    'ico' => 'ui-icon-pencil',
                                    'data' => array('id' => $data['id'])
                        )) : '');
    }

    protected static function url($v, $url_pagams = array()) {
        $url = ($v['url']) ? $v['url'] : self::$url;
        
        $url.= ($v['table']) ? '&table=' . $v['table'] : '';
        if (preg_match('/action\=show/', self::$url)) {
            $url = str_replace('action=show', '', $url);
        }
        $url.= ($v['action']) ? '&action=' . $v['action'] : '';
        if (is_array($url_pagams))
            foreach ($url_pagams as $p) {
                $dpar = (!is_array($v['data'][$p])) ? $v['data'][$p] : implode(',', $v['data'][$p]);
                $url.= ($v['data'][$p]) ? '&' . $p . '=' . $dpar : '';
            }
        return $url;
    }

    protected function rowButton($v, $hint = '', $url_pagams = array('id')) {

        $is = isset($v['data']['is_' . $v['name']]) ? $v['data']['is_' . $v['name']] : 1;
        if ($v['name'])
            $v['name'] = $v['name'] . $v['data']['id'];
        if ($v['id'])
            $v['id'] = $v['id'] . $v['data']['id'];

        $v['url'] = self::url($v, $url_pagams);
        $v['hint'] .= ' :: ' . (($hint) ? $hint : $v['data']['name']);
        //print_r($v);
        //echo $id;
        return ($is) ? forms::button($v) : '';
    }
    protected function alink($v, $hint = '', $url_pagams = array('id')) {

        
        if ($v['name'])
            $v['name'] = $v['name'] . $v['data']['id'];
        if ($v['id'])
            $v['id'] = $v['id'] . $v['data']['id'];

        $v['url'] = self::url($v, $url_pagams);
        
        return forms::alink($v);
    }
    protected function rowText($v, $value) {
        if ($v['name'])
            $v['name'] = $v['name'] . $v['data']['id'];
        if ($v['id'])
            $v['id'] = $v['id'] . $v['data']['id'];
        if ($value)
            $v['value'] = $value;
        return forms::text($v);
    }

    protected function rowSpan($v, $i) {
        //if ($v['name'])$v['name'] = $v['name'].$i;
        if ($v['id'])
            $v['id'] = $v['id'] . $i;
        return forms::span($v);
    }

    protected function rowCheckbox($v, $i) {
        //if ($v['name'])$v['name'] = $v['name'].$i;
        if (!$v['css']) {
            $v['css'] = 'rows_checked';
        }
        if ($v['id'])
            $v['id'] = $v['id'] . $i;
        return forms::checkbox($v);
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