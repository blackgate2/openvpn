<?php

// ----------------------------------- class формы -----------------------------------
class forms {

    public $str;
    public $url;
    private $nameform = 'theform';
    public $method = 'POST';
    private $fields;
    public $hiddens;
    private $is_cheked = false;
    private $is_send_ajax = false;
    private $ajax_tag_respons = '';
    private $is_form_In_dialog = 1;
    private $is_submit_button = 1;
    private $dialog_name = 'dialog_modal';
    private $dialog_is_modal = true;
    private $dialog_title;
    private $dialog_width;
    private $dialog_height;
    private $autosubmit = false;
    public $before_std_submit;
    public $additional_submit_buttons;
    private $list_files;
    private $ignore_dir = array('.', '..', 'cgi-bin');
    private $loadingImg = '/images/loading.gif';
    private static $msg;
    private $target;
    private $count_view_col = 1;

    public function __construct($localize) {
        //$this->submit_button =$this->set_submit_button($localize['submit_button']);
        $this->set_msg($localize);
        $this->additional_submit_buttons = array();
        $this->before_std_submit = '';
        $this->target = '_self';
    }

    private function set_msg($localize) {
        foreach ($localize as $key => $value) {
            self::$msg[$key] = $value;
        }
    }

    public function set_dialog_params($d) {

        foreach ($d as $key => $value) {
            if ($value)
                $this->__set($key, $value);
        }
    }

    public function set_ajax_sent_form($tag_name) {
        $this->is_send_ajax = true;

        $this->ajax_tag_respons = $tag_name;
    }

    public function __set($key, $value) {
        $this->$key = $value;
    }

    public function __get($key) {
        return $this->$key;
    }

    public function __call($method, $args) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $args);
        } else {
            throw new Exception(sprintf('The required method "%s" does not exist for %s', $method, get_class($this)));
        }
    }

//    public function set_submit_button($name) {
//        if (is_string($name) && $name != '') {
//            $this->submit_button = $name;
//        }
//    }

    public function add_submit_button($name, $action) {
        if (is_string($name) && $name != '' && is_string($action) && $action != '') {
            $this->additional_submit_buttons[] = array($name, $action);
        }
    }

    public function set_before_std_submit($action = '') {
        if (is_string($action) && $action != '') {
            $this->before_std_submit = $action;
        }
    }

    public static function back($url = '', $title = '', $ico = 'ui-icon-arrowreturnthick-1-w') {
        $url = ($url == '') ? $_SERVER['HTTP_REFERER'] : $url;
        $title = ($title != '') ? $title : self::$msg['back'];
        return self::button(array('url' => $url, 'css' => 'link_go ui-button-text-icon ', 'ico' => $ico, 'title' => $title));
    }

    public function open_form() {
        return ( $this->nameform) ? '<form action="' . $this->url . '"  name="' . $this->nameform . '" id="' . $this->nameform . '" method="' . $this->method . '" enctype="multipart/form-data" target="' . $this->target . '">' : '';
    }

    public static function text($field) {

        return '<input type="text"
            ' . ($field['disabled'] ? 'disabled' : '') . '
            name="' . $field['name'] . '" id="' . $field['name'] . '" 
            placeholder="' . $field['placeholder'] . '" 
            value="' . $field['value'] . '"  
            ' . ($field['readonly'] ? 'readonly' : '') . ' 
            class=" ' . ($field['readonly'] ? 'readonly' : '') . ' ' . $field['css'] . ' formtext ui-widget-content ui-corner-all"
            style="' . (($field['style'] ? $field['style'] : '')) . '">' . "\n";
    }

    public static function combobox($field) {

        return '
            <script language="JavaScript" src="/js/autocomplete.combobox.js"></script>
            <script>
            $(function() {
                $( "#' . $field['name'] . '" ).combobox();
                $( "#toggle" ).click(function() {
                    $( "#combobox" ).toggle();
                });
            });
            </script><!--button id="toggle">Show underlying select</button-->' . "\n" . self::select($field);
    }

    public static function autocomplete($field) {
        $str = '';
        //print_r($field);
        if (is_array($field['get_val_title'])) {
            $v = call_user_func($field['get_val_title'][0], $field['get_val_title'][1]);
            //exit($field['get_val_title'][1]);
            $field['value'] = $v['id'];
            $field['title'] = $v['name'];
        } elseif ($field['get_val_title']) {
            $field['title'] = call_user_func($field['get_val_title']);
        }
        $field['id'] = ($field['id']) ? $field['id'] : $field['name'];

        $str.= '
    <style>
    .ui-autocomplete-loading {
        background: white url(/images/elements/ajax_loader.gif) right center no-repeat;
    }
    </style>
        <script>
            $(function() {
                $("#' . $field['edit_name'] . '").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "' . $field['ajax_url'] . '",
                            data: {term: request.term,maxRows: ' . $field['maxRows'] . '},
                            dataType: "json",
                            success: function(data) {
                                response($.map(data.myData, function(item) {
                                    return {
                                        id: item.id,
                                        value: item.name

                                    }
                                }));
                            }
                        });
                    },
                    minLength: ' . $field['minLength'] . ',
                    select: function(event, ui) {
                        $("#' . $field['name'] . '").val(ui.item.id);
                        ' . $field['event_select'] . '
                    },
                    search: function() {
                        //reset every time a search starts.
                        $("#' . $field['name'] . '").val(0);
                    },
 
                    open: function() {
                        $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
                    },
                    close: function() {
                        $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
                    }
                });
            });
        </script>
        <input 
            id="' . $field['edit_name'] . '" 
            value="' . $field['title'] . '"
            name="' . $field['edit_name'] . '"
            placeholder="' . $field['placeholder'] . '"
            class=" ' . ($field['readonly'] ? 'readonly' : '') . ' ' . $field['css'] . ' formtext ui-widget-content ui-corner-all ui-autocomplete-input"
            />
            <input type="hidden" id="' . $field['id'] . '" name="' . $field['name'] . '" value="' . $field['value'] . ' ' . ($field['disabled'] ? 'disabled' : '') . '"  >
           ';

        if (is_array($field['button_new_dialog'])) {
            $str.=self::button_new_dialog($field['button_new_dialog']);
        }

        return $str;
    }

    public static function button_new_dialog($field) {
        return self::button($field) . '
            
            <script>
            $(function() {
                /* ---------------- модальне оконо  ---------------------------- */
                $("#' . $field['dialog_name'] . '").dialog({
                    autoOpen: false

                });
                /* ---------------- обработчик окрытия диалогвого окна   ---------------------------- */   
                $("#' . $field['name'] . '").click(function() {
                    var url_load = $(this).attr("href");
                    $("#' . $field['dialog_name'] . '").dialog("open");
                    $("#' . $field['dialog_name'] . '").dialog({
                        title: $(this).attr("title")
                    });
                    $("#' . $field['dialog_name'] . '").load(url_load);
                    return false;
                });        
            });
            </script>
                ';
    }

    public static function range($field) {
        return '<input type="text" name="min_' . $field['name'] . '" id="min_' . $field['name'] . '" value="' . $field['value']['min'] . '"   class="' . $field['css'] . ' ' . ($field['disabled'] ? 'disabled' : '') . ' ui-widget-content ui-corner-all range-form"> ' . (($field['sep']) ? $field['sep'] : '-') . "\n" . '
            <input type="text" name="max_' . $field['name'] . '" id="max_' . $field['name'] . '" value="' . $field['value']['max'] . '"   class="' . $field['css'] . ' ' . ($field['disabled'] ? 'disabled' : '') . ' ui-widget-content ui-corner-all range-form">' . "\n";
    }

    public static function text_fields($field) {
        if (isset($field['get_value'])) {
            if (is_array($field['get_value'])) {

                $values = call_user_func($field['get_value'][0], $field['get_value'][1]);
            } elseif ($field['get_value']) {
                $values = call_user_func($field['get_value']);
            } else {
                $values = array(0 => 0);
            }
            // print_r($values);
        }

        $str_multy = (!empty($values[0])) ? '[]' : '';
        $str_sep = ($field['sep']) ? $field['sep'] : '-';
        $i = 0;
        foreach ($values as $k => $value) {
            //<div class="input_cont"> 
            //<input type="text" name="summ[]" value="" class="text_fields_summ  numeric to_money 
            //ui-widget-content ui-corner-all range-form"></div>
            $str.='<div class="text_fields"> ';
            $str.=($field['is_num']) ? ' <div class="nn ss">' . ($i + 1) . '</div> ' : '';
            foreach ($field['name'] as $name) {
                $css = $field['form'] . '_' . $name;
                $str.='<span class="ss">' . $field['titles'][$name] . '</span> <input type="text" name="' . $name . '[]" 
                            value="' . $value[$name] . '"   
                            class="' . $css . '  ' . $field['css'] . ' ui-widget-content ui-corner-all range-form"> ' . $str_sep;
            }
            $str = trim($str, $str_sep);
            $str.=' </div>';
            $i++;
        }
        return $str;
    }

    public static function password($field) {
        return '<input type="password" name="' . $field['name'] . '" id="' . $field['name'] . '" ' . ($field['disabled'] ? 'disabled' : '') . ' value="' . $field['value'] . '"   class="text ui-widget-content ui-corner-all">' . "\n";
    }

    public static function checkbox($field) {
        if ($field['checked'] != '')
            $field['checked'] = 'checked';
        $id = (($field['id']) ? $field['id'] : $field['name']);
        if ($field['value'] === '')
            $field['checked'] = '';
        return '<input type="checkbox" name="' . $field['name'] . '" ' . ($field['disabled'] ? 'disabled' : '') . '  
            id="' . $id . '" 
            title="' . $field['title'] . '" 
            value="' . $field['value'] . '"  ' . (($field['prop']) ? $field['prop'] : '') . ' class="' . $field['css'] . ' Checkboxes"' . $field['checked'] . ' >' .
                (($field['label']) ? '<label for="' . $id . '">' . $field['label'] . '</label>' : '') . "\n";
    }

    public static function file($field) {
        if (preg_match("/\\.(jpg|gif|png|bmp?)$/", $field['value'])) {
            $img = '<a href="' . $field['path_img'] . "" . $field['value'] . '" target="_blank"><img src="' . $field['path_img'] . "small_" . $field['value'] . '" alt="" border="0"></a></small>';
            $s = '<br>' . $img . '
                                <br><small>(<font color="#FF0000">' . self::$msg['img_param'] . '</font>:
                                <br>' . self::$msg['img_param_size'] . ' ' . (size_format($field['upload_max_size'])) . ' , ' . self::$msg['img_param_res'] . ' ' . $field['upload_maxx'] . 'x' . $field['upload_maxy'] . 'px)';
        } else {
            $s.='<br><a href="' . $field['path_img'] . "" . $field['value'] . '">' . $field['value'] . '</a><br>' . self::$msg['img_param_size'] . ' ' . (size_format($field['upload_max_size'])) . '';
        }

        return '<input type="' . $field['form'] . '" name="' . $field['name'] . '"  ' . ($field['disabled'] ? 'disabled' : '') . ' id="' . $field['name'] . '" value="' . $field['value'] . '"  ' . $field['prop'] . ' ' . $class . ' class="text ui-widget-content ui-corner-all">' . "\n" . $s;
    }

    public static function title($title) {
        return '<div class="title">' . $title . '</div>' . "\n";
    }

    public function hidden_forms() {
        foreach ($this->hiddens as $k => $v) {
            $this->str.= '<input type="hidden" name="' . $k . '" value="' . $v . '" id="' . $k . '">' . "\n";
        }
    }

    public static function hidden($field) {

        return '<input type="hidden" 
                ' . ($field['disabled'] ? 'disabled' : '') . '
                ' . (($field['id']) ? 'id="' . $field['id'] . '"' : '') . '
                ' . (($field['name']) ? 'name="' . $field['name'] . '"' : '') . '
                value="' . $field['value'] . '">' . "\n";
    }

    public static function span($field) {

        return '<span 
                ' . (($field['css']) ? 'class="' . $field['css'] . '"' : '') . '
                ' . (($field['id']) ? 'id="' . $field['id'] . '"' : '') . '
                ' . (($field['name']) ? 'name="' . $field['name'] . '"' : '') . '
                ' . (($field['title']) ? 'title="' . $field['title'] . '"' : '') . '
                    >' . $field['value'] . '</span>' . "\n";
    }

    public static function img($field) {

        return '<a href="' . $field['url'] . '" 
            ' . (($field['a_id']) ? 'id="' . $field['a_id'] . '"' : '') . '
            ' . (($field['title']) ? 'title="' . $field['title'] . '"' : '') . '
            ' . (($field['a_css']) ? 'class="' . $field['a_css'] . '"' : '') . '
            ><img 
                src="' . $field['value'] . '" 
            ' . (($field['alt']) ? 'alt="' . $field['alt'] . '"' : '') . '
            ' . (($field['width']) ? 'width="' . $field['width'] . '"' : '') . '
            ' . (($field['height']) ? 'height="' . $field['height'] . '"' : '') . '
            ' . (($field['css']) ? 'class="' . $field['css'] . '"' : '') . '
            ' . (($field['id']) ? 'id="' . $field['id'] . '"' : '') . '
                /></a>' . "\n";
    }

    public static function radio($field) {
        $str = '';
        for ($k = 0; $k < count($field['values']); $k++) {

            $checked = ($field['value'] == $field['values'][$k]) ? ' checked ' : '';
            $str.= '<input type="radio" name="' . $field['name'] . '"  id="' . $field['name'] . $k . '" value="' . $field['values'][$k] . '" ' . $checked . ' ' . $field['prop'] . ' class="radio radio_' . $field['name'] . ' ' . $field['css'] . '">';
            $str .= '<label for="' . $field['name'] . $k . '">' . $field['titles'][$k] . '</label>' . ((!$field['is_br_cap']) ? '<br>' : '') . "\n";
        }
        return $str;
    }

    public static function textarea($field) {

        return '<textarea name="' . $field['name'] . '"  id="' . $field['name'] . '" class="' . $field['css'] . '  text ui-widget-content ui-corner-all">' . $field['value'] . '</textarea>' . "\n";
    }

    public static function select_simple($field) {
        $size = '';
        $str = '';
        //print_r($field['value']);
        if (!is_array($field['value'])) {
            $tmp = 'name="' . $field['name'] . '"';
        } else {
            $tmp = 'multiple name="' . $field['name'] . '[]" ';
            $size_num = 7;
            if (count($field['values']) < 7) {
                $size_num = count($field['values']);
            }
            $size = ' size="' . $size_num . '"';
        }
        $str.= '<select ' . $tmp . '  id="' . $field['name'] . '" ' . $field['prop'] . ' ' . ($field['disabled'] ? 'disabled' : '') . ' class="formselect select_' . $field['name'] . ' ' . $field['css'] . ' ui-widget-content ui-corner-all"' . $size . '>' . "\n";


        foreach ($field['values'] as $k => $v) {

            $selected = '';
            if (is_array($field['value'])) {
                if (count($field['value']) == 0) {
                    if ($v[0] == '' || $v == 0) {
                        $field['value'] = array($v);
                    }
                }

                if (in_array($k, $field['value'])) {
                    $selected = ' selected ';
                }
            } else {
                $selected = ($field['value'] == $k) ? ' selected ' : '';
            }
            $str.= '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>' . "\n";
        }
        $str.='</select>' . "\n";
        return $str;
    }

    public static function multy_checkbox($field) {
        $str = '';
        $i = 0;

        if ($field['data']) {
            if (is_array($field['data']))
                $field['values'] = call_user_func($field['data'][0], $field['data'][1]);
            else
                $field['values'] = call_user_func($field['data']);
        }
        if ($field['get_value']) {
            if (is_array($field['get_value'])) {
                $field['value'] = call_user_func($field['get_value'][0], $field['get_value'][1]);
            } else {
                $field['value'] = call_user_func($field['get_value']);
            }
        }
        //print_r($field['value']);
        foreach ($field['values'] as $k => $v) {
            $checked = '';
            if ($k && is_array($field['value']) && in_array($k, $field['value'])) {
                $checked = ' checked ';
            } elseif ($field['value'] == $k) {
                $checked = ' checked ';
            }
            if ($k === 0 && is_array($field['value']) && in_array($k, $field['value'], true)) {
                $checked = ' checked ';
            }




            if (!is_array($field['name'])) {
                $str.= self::checkbox(array('label' => $v, 'is_br' => $field['is_br'], 'value' => $k, 'name' => $field['name'] . '[]', 'title' => $field['title'], 'id' => $field['name'] . $i, 'css' => $field['css'], 'checked' => $checked, 'prop' => $prop));
            } else {
                $ii = 0;
                $str.= '<div class="multy_checkbox">';
                foreach ($field['name'] as $fn) {
                    $checked = '';
                    if (is_array($field['value']) && in_array($fn . $k, $field['value'])) {
                        $checked = ' checked ';
                    }
                    $str.= self::checkbox(array('is_br' => $field['is_br'], 'value' => $fn . $k, 'name' => $fn . '[]', 'title' => $field['title'][$ii], 'id' => $fn . $k, 'css' => $field['css'] . ' ' . $fn, 'checked' => $checked, 'prop' => $prop));
                    $ii++;
                }
                $str.= $v . '</div>';
            }
            $str.= (($field['is_br']) ? '</br>' : '');

            $i++;
        }

        return $str;
    }

    public static function select($field, $form_select = true) {
        $str = '';

        if (isset($field['data'])) {
            if (is_array($field['data']))
                $field['data'] = call_user_func($field['data'][0], $field['data'][1]);
            else
                $field['data'] = call_user_func($field['data']);
        }
        $values = array();

        $str_name = $field['name'];
        if (is_array($field['value'])) {
            $str_name.='[]';
            $str_is_multiple = 'multiple';
            $values = $field['value'];
            if (isset($field['get_value'])) {
                if (is_array($field['get_value'])) {
                    $values = call_user_func($field['get_value'][0], $field['get_value'][1]);
                } else {
                    $values = call_user_func($field['get_value']);
                }
                // print_r($values);
            }
        } else {
            $values[] = $field['value'];
        }
        if (is_array($field['data']) && !empty($field['data'])) {
            $str.= ( $form_select) ? '<select ' . $str_is_multiple . ' name="' . $str_name . '"  id="' . $field['name'] . '" ' . ($field['disabled'] ? 'disabled' : '') . ' class=" formselect' . $str_is_multiple . ' ui-widget-content ui-corner-all">' : '';

            if (isset($field['first_val']) && $field['first_val'] == '0') {
                $str.='<option value="0"></option>';
            } elseif (isset($field['first_val']) && $field['first_val'] == '') {
                $str.='<option value=""></option>';
            } elseif (isset($field['first_val']) && $field['first_val'] != 'no') {
                $str.='<option value="' . $field['first_val'] . '">' . $field['first_val'] . '</option>';
            } elseif (!isset($field['first_val'])) {
                $str.='<option value="">' . self::$msg['select_title_default'] . '</option>';
            }



            for ($i = 0; $i < count($field['data']); $i++) {
                if (!empty($field['bg_colors']))
                    $str_bg_color = 'style="background-color:#' . $field['bg_colors'][$i] . '"';
                $names = array_values($field['data'][$i]);
                $str_sel = (in_array($names[0], $values)) ? ' selected ' : '';

                $str_bg_color = '';
                if (!empty($field['bg_colors'])) {
                    foreach ($field['bg_colors'] as $color_val => $color_tit) {
                        //echo "$names[0] == $color_val<br>";
                        if ($names[0] == $color_val) {
                            $str_bg_color = 'style="background-color:#' . $color_tit . '"';
                            break;
                        }
                    }
                }
                $str.= '<option value="' . $names[0] . '" ' . $str_sel . ' ' . $str_bg_color . '>' . $names[1] . '</option>' . "\n";
            }
            $str.= ( $form_select) ? '</select>' : '';
            if (is_array($field['button_new_dialog'])) {
                $str.=self::button_new_dialog($field['button_new_dialog']);
            }
        }
        return $str;
    }

    public function select_from_dir($field) {
        $this->list_files = array();
        $this->get_files_from_dir($field['path'], $field['is_set_path']);
        $field['values'] = $this->list_files;
        $field['title'] = $this->list_files;
        $this->select_simple($field);
    }

    private function get_files_from_dir($path = '.', $is_set_path = true) {
        //echo $path.'<br>';

        $dh = @opendir($path);
        while (false !== ( $file = readdir($dh) )) {
            if (!in_array($file, $this->ignore_dir)) {
                if (is_dir("$path/$file")) {
                    $this->get_files_from_dir("$path/$file", $is_set_path);
                } else {
                    $this->list_files[] = ($is_set_path) ? $path . '/' . $file : $file;
                }
            }
        }
        closedir($dh);
    }

    public static function calc_form($field) {
        return '
        <script type="text/javascript">
        $(function () {
                $("#' . $field['name'] . '").calculator({
                        showOn: "button",
                        buttonImage: "' . (($field['button_image']) ? $field['button_image'] : '/images/elements/ico_calc.png') . '",
                        precision: 2,
                        showAnim: "slideDown",
                        showOptions: null,
                        constrainInput: false,
                        duration: "fast",
                        onOpen: function(value, inst) { 
                           $(this).val(value.replace(/\s+/g, "")); 
                        },
                        onClose: function(value, inst) {    
                            $("#amount_name").val(ToMoneyFormat( value ));
                        }

                });

        });
        </script>' . self::text($field);
    }

    public static function date_form($field) {


        return '
        <script type="text/javascript">
	$(function() {
		$("#' . $field['name'] . '").datepicker({
                    ' . (($field['minDate']) ? 'minDate:"' . $field['minDate'] . '",' : '') . '
                    ' . (($field['maxDate']) ? 'maxDate:"' . $field['maxDate'] . '",' : '') . '
                    dateFormat:"' . (($field['format']) ? $field['format'] : 'yyyy-mm-dd') . '"

                 });
                
	});
	</script>
        <input type="text" id="' . $field['name'] . '" name="' . $field['name'] . '" value="' . $field['value'] . '" class="' . $field['css'] . ' formtext ui-widget-content ui-corner-all">';
    }

    public static function date_range($field) {
        return '
        <script type="text/javascript">
        $(function() {
            $( "#min_' . $field['name'] . '" ).datepicker({
                dateFormat:"' . (($field['format']) ? $field['format'] : 'yy-mm-dd') . '",  
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 3,
                ' . (($field['minDate']) ? 'minDate:"' . $field['minDate'] . '",' : '') . '
                ' . (($field['maxDate']) ? 'maxDate:"' . $field['maxDate'] . '",' : '') . '
                onClose: function( selectedDate ) {
                    $( "#max_' . $field['name'] . '" ).datepicker( "option", "minDate", selectedDate );
                }
            });
            $( "#max_' . $field['name'] . '" ).datepicker({
                dateFormat:"' . (($field['format']) ? $field['format'] : 'yy-mm-dd') . '",
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 3,
                ' . (($field['minDate']) ? 'minDate:"' . $field['minDate'] . '",' : '') . '
                ' . (($field['maxDate']) ? 'maxDate:"' . $field['maxDate'] . '",' : '') . '
                onClose: function( selectedDate ) {
                    $( "#min_' . $field['name'] . '" ).datepicker( "option", "maxDate", selectedDate );
                }
            });
        });
	</script>
        <input type="text" id="min_' . $field['name'] . '" name="min_' . $field['name'] . '" value="' . $field['value']['min'] . '" class="text ui-widget-content ui-corner-all"> -
        <input type="text" id="max_' . $field['name'] . '" name="max_' . $field['name'] . '" value="' . $field['value']['max'] . '" class="text ui-widget-content ui-corner-all">
        
        ';
    }

    public static function alink($field) {
        return '
       
            <a 
            id="' . (($field['id']) ? $field['id'] : $field['name']) . '"
            name="' . $field['name'] . '"
                
            title="' . $field['hint'] . '"
            ' . (($field['url']) ? 'href="' . $field['url'] . '"' : '') . '     
            
            class="' . $field['css'] . '">

            ' . (($field['title']) ?  strip_tags($field['title']) : '') . ' 

            </a>  
';
    }
    public static function button($field) {
        return '
       
            <button 
            id="' . (($field['id']) ? $field['id'] : $field['name']) . '"
            name="' . $field['name'] . '"
                
            title="' . strip_tags(($field['hint']) ? $field['hint'] : $field['title']) . '"
            ' . (($field['url']) ? 'href="' . $field['url'] . '"' : '') . '     
            ' . (($field['confirm']) ? 'confirm="' . $field['confirm'] . '"' : '') . '  
            ' . (($field['action']) ? 'action="' . $field['action'] . '"' : '') . '
            
            class="' . $field['css'] . ' ui-button ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">

            ' . (($field['ico']) ? '<span class="ui-button-icon-primary ui-icon ' . $field['ico'] . '"></span>' : '') . ' 
            ' . (($field['title']) ? '<span class="ui-button-text">' . strip_tags($field['title']) . ' </span>' : '') . ' 

            </button>  
';
    }
    public static function date_time_form($field) {
        return '
        <script type="text/javascript">
	$(function() {
            $("#date_' . $field['name'] . '").datepicker({
                dateFormat:"' . (($field['format']) ? $field['format'] : 'yy-mm-dd') . '",
                ' . (($field['minDate']) ? 'minDate:"' . $field['minDate'] . '",' : '') . '
                ' . (($field['maxDate']) ? 'maxDate:"' . $field['maxDate'] . '",' : '') . '
                    
            });
            $("#time_' . $field['name'] . '").timepicker({
                timeOnlyTitle: \'' . self::$msg['timeOnlyTitle'] . '\',
                timeText: \'' . self::$msg['timeText'] . '\',
                hourText: \'' . self::$msg['hourText'] . '\',
                minuteText: \'' . self::$msg['minuteText'] . '\',
                secondText: \'' . self::$msg['secondText'] . '\',
                currentText: \'' . self::$msg['currentText'] . '\',
                closeText: \'' . self::$msg['closeText'] . '\'           
                });
          });                
	});
	</script>
        <input type="text" id="date_' . $field['name'] . '" name="date_' . $field['name'] . '" value="' . $field['value']['date'] . '" class="' . $field['css'] . ' formtext ui-widget-content ui-corner-all">
        <input type="text" id="time_' . $field['name'] . '" name="time_' . $field['name'] . '" value="' . $field['value']['time'] . '" class="text ui-widget-content ui-corner-all">
        ';
    }

    public function time_form($field) {
        return '

        <script type="text/javascript">
          $(function() {
            
            $(\'#' . $field['name'] . '\').timepicker({
                timeOnlyTitle: \'' . self::$msg['timeOnlyTitle'] . '\',
                timeText: \'' . self::$msg['timeText'] . '\',
                hourText: \'' . self::$msg['hourText'] . '\',
                minuteText: \'' . self::$msg['minuteText'] . '\',
                secondText: \'' . self::$msg['secondText'] . '\',
                currentText: \'' . self::$msg['currentText'] . '\',
                closeText: \'' . self::$msg['closeText'] . '\'           
    });
          });
	</script>

        <input type="text" id="' . $field['name'] . '" name="' . $field['name'] . '" value="' . $field['value'] . '" class="text ui-widget-content ui-corner-all">
        ';
    }

    public function time_range($field) {
        return '
         <script type="text/javascript">
          $(document).ready(function() {
            $(\'#min_' . $field['name'] . '\').timepicker({
                showPeriodLabels: true,
                showLeadingZero: false,
                onHourShow: tpStartOnHourShowCallback,
                onMinuteShow: tpStartOnMinuteShowCallback,
                timeOnlyTitle: \'' . self::$msg['timeOnlyTitle'] . '\',
                timeText: \'' . self::$msg['timeText'] . '\',
                hourText: \'' . self::$msg['hourText'] . '\',
                minuteText: \'' . self::$msg['minuteText'] . '\',
                secondText: \'' . self::$msg['secondText'] . '\',
                currentText: \'' . self::$msg['currentText'] . '\',
                closeText: \'' . self::$msg['closeText'] . '\'   
            });
            $(\'#max_' . $field['name'] . '\').timepicker({
                showPeriodLabels: true,
                showLeadingZero: false,
                onHourShow: tpEndOnHourShowCallback,
                onMinuteShow: tpEndOnMinuteShowCallback,
                timeOnlyTitle: \'' . self::$msg['timeOnlyTitle'] . '\',
                timeText: \'' . self::$msg['timeText'] . '\',
                hourText: \'' . self::$msg['hourText'] . '\',
                minuteText: \'' . self::$msg['minuteText'] . '\',
                secondText: \'' . self::$msg['secondText'] . '\',
                currentText: \'' . self::$msg['currentText'] . '\',
                closeText: \'' . self::$msg['closeText'] . '\'   
            });
        });

        function tpStartOnHourShowCallback(hour) {
            var tpEndHour = $(\'#max_' . $field['name'] . '\').timepicker(\'getHour\');
            // all valid if no end time selected
            if ($(\'#max_' . $field['name'] . '\').val() == \'\') { return true; }
            // Check if proposed hour is prior or equal to selected end time hour
            if (hour <= tpEndHour) { return true; }
            // if hour did not match, it can not be selected
            return false;
        }
        function tpStartOnMinuteShowCallback(hour, minute) {
            var tpEndHour = $(\'#max_' . $field['name'] . '\').timepicker(\'getHour\');
            var tpEndMinute = $(\'#max_' . $field['name'] . '\').timepicker(\'getMinute\');
            // all valid if no end time selected
            if ($(\'#max_' . $field['name'] . '\').val() == \'\') { return true; }
            // Check if proposed hour is prior to selected end time hour
            if (hour < tpEndHour) { return true; }
            // Check if proposed hour is equal to selected end time hour and minutes is prior
            if ( (hour == tpEndHour) && (minute < tpEndMinute) ) { return true; }
            // if minute did not match, it can not be selected
            return false;
        }

        function tpEndOnHourShowCallback(hour) {
            var tpStartHour = $(\'#min_' . $field['name'] . '\').timepicker(\'getHour\');
            // all valid if no start time selected
            if ($(\'#min_' . $field['name'] . '\').val() == \'\') { return true; }
            // Check if proposed hour is after or equal to selected start time hour
            if (hour >= tpStartHour) { return true; }
            // if hour did not match, it can not be selected
            return false;
        }
        function tpEndOnMinuteShowCallback(hour, minute) {
            var tpStartHour = $(\'#min_' . $field['name'] . '\').timepicker(\'getHour\');
            var tpStartMinute = $(\'#min_' . $field['name'] . '\').timepicker(\'getMinute\');
            // all valid if no start time selected
            if ($(\'#min_' . $field['name'] . '\').val() == \'\') { return true; }
            // Check if proposed hour is after selected start time hour
            if (hour > tpStartHour) { return true; }
            // Check if proposed hour is equal to selected start time hour and minutes is after
            if ( (hour == tpStartHour) && (minute > tpStartMinute) ) { return true; }
            // if minute did not match, it can not be selected
            return false;
        }
	</script>

        <input type="text" id="min_' . $field['name'] . '" name="min_' . $field['name'] . '" value="' . $field['value']['min'] . '" class="text ui-widget-content ui-corner-all">-
        <input type="text" id="max_' . $field['name'] . '" name="max_' . $field['name'] . '" value="' . $field['value']['max'] . '" class="text ui-widget-content ui-corner-all">
        ';
    }

    public function close_form() {
        return ( $this->nameform) ? '</form>' : '';
    }

    private function gen_js() {
        $this->str.='     var ';
        for ($i = 0; $i < count($this->fields); $i++) {
            $f = $this->fields[$i]['form'];
            $o = $this->fields[$i]['name'];
            $css = $f . '_' . $o;
            $c = $this->fields[$i]['caption'];
            if ($f == 'multy_checkbox') {
                $this->str.= $o . '=$(".' . $o . '"),';
            } elseif (is_array($o)) {
                foreach ($o as $value) {
                    $css = $f . '_' . $value;
                    $this->str.= $value . '=$(".' . $css . '"),';
                }
            } else {
                $this->str.= ( $o) ? $o . '=$("#' . $o . '"),' : '';
                $this->str.= ( $o && ($f == 'radio')) ? $css . '=$(".' . $css . '"),' : '';
            }
        }

        $this->str.='
                    allFields = $([])';
        for ($i = 0; $i < count($this->fields); $i++) {

            if ($this->fields[$i]['status'] && $this->fields[$i]['status'] != "0") {
                if (is_array($this->fields[$i]['name'])) {
                    foreach ($this->fields[$i]['name'] as $value) {
                        $this->str.='.add(' . $value . ')';
                    }
                } else {
                    $this->str.='.add(' . $this->fields[$i]['name'] . ')';
                }
            }
        }
        $this->str.=',
                tips = $(".validate_' . $this->nameform . '");
                function updateTips(t) {
                    tips
                    .text(t)
                    .addClass(\'ui-state-highlight\');
                    setTimeout(function() {
                        tips.removeClass(\'ui-state-highlight\', 1500);
                    }, 500);
                }
                function eqPass(pass,repass) {

                    if (pass.val() != repass.val()  ) {
                        //alert(pass.val() + " " +repass.val()  );
                        repass.addClass(\'ui-state-error\');
                        updateTips("' . self::$msg['valid_form_pass_re'] . '");
                        return false;
                    }else {
                        return true;
                    }

                }
                
                function checkLength(o,n,min,max,a) {
                     if (o.prop("disabled"))
                        return true;

                    if (o.val().length > max || o.val().length < min ) {
                        o.addClass(\'ui-state-error\');
                        a=(a!="")?a:"' . self::$msg['valid_form_text'] . '";
                        updateTips(a);
                        return false;
                    } else {
                        return true;
                    }

                }
                function checkEmpty(o,n,a) {

                    if ( o.val()=="" ) {
                        o.addClass(\'ui-state-error\');
                        a=(a!="")?a:"' . self::$msg['valid_form_date'] . '";
                        updateTips(a);
                        return false;
                    } else {
                        return true;
                    }

                }
                function oneOrMoreEmpty(o,a) {
                    var oneEmpty = false;

                    allFields.each(function() {
                       if  ($(this).val() != "") oneEmpty = true;
                    });
                    if (oneEmpty) {
                        return true;
                    } else {
                        o.addClass(\'ui-state-error\');
                        a=(a!="")?a:"' . self::$msg['valid_form_oneOrMore'] . '";
                        updateTips(a);
                        return false;
                        
                    }
                }
                function anyNoEmpty(o,a) {

                    var flg=false; 
                    o.each(function() {
                       if  ($(this).val() == "") {
                            flg = true;
                       }else{
                            flg = false;
                       }
                    });
                    
                    if(flg){
                        o.addClass(\'ui-state-error\');
                        a=(a!="")?a:"' . self::$msg['anyNoEmpty'] . '";
                        updateTips(a);
                        return false;
                    }else {
                        return true;
                    }
                     
                }
                function NotCheckedRadio(css,o,n,a) {
                    var flg=false;

                    css.each(function(){
                        if(this.checked){
                            flg = true;
                        }
                    });
                    if(!flg){
                        o.addClass(\'ui-state-error\');
                        a=(a!="")?a:"' . self::$msg['valid_form_radio'] . '";
                        updateTips(a);
                        return false;
                    }else {
                        return true;
                    }

                }
                function NotCheckedCheckbox(o,a) {

                    if (!o.is(":checked")) {
                        o.addClass(\'ui-state-error\');
                        a=(a!="")?a:"' . self::$msg['valid_form_radio'] . '";
                        updateTips(a);
                        return false;
                    }else {
                        return true;
                    }

                }
                function NotSelected(name_form,o,f,a,code_eval) {


                   if (typeof($("#"+name_form+" :selected").val()) === "undefined" || $("#"+name_form+" :selected").val()=="" || (code_eval!="" && eval(code_eval)) ) {

                        o.addClass(\'ui-state-error\');
                        a=(a!="")?a:"' . self::$msg['valid_form_select'] . '";
                        updateTips(a);
                        return false;
                    }else {
                        return true;
                    }
                }
                function checkRegexp(o,regexp,n) {

                    if ( !( regexp.test( o.val() ) ) ) {
                        o.addClass(\'ui-state-error\');
                        updateTips(n);
                        return false;
                    } else {
                        return true;
                    }

                }

';
    }

    private function gen_js1() {
        $str = '';
        for ($i = 0; $i < count($this->fields); $i++) {
            $o = $this->fields[$i]['name'];
            $f = $this->fields[$i]['form'];

            $a = strip_tags($this->fields[$i]['alert']);
            $code_eval = $this->fields[$i]['code_eval'];

            $css = $f . '_' . $o;
            $c = strip_tags($this->fields[$i]['caption']);

            if ($this->fields[$i]['status'] == 'X' && $o == 'login')
                $str.='
                    bValid = bValid && checkLength(login,"login",3,20,"");
                    bValid = bValid && checkRegexp(login,/^[a-z]([0-9a-z_])+$/i,"' . self::$msg['valid_form_login'] . '");';
            elseif ($this->fields[$i]['status'] == 'X' && $o == 'email' && $this->fields[$i]['status'] == 'X')
                $str.='
                    bValid = bValid && checkRegexp(email,/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,"' . self::$msg['valid_form_email'] . '");';
            elseif ($this->fields[$i]['status'] == 'X' && $f == 'password' && $o == 'passwd')
                $str.='
                    bValid = bValid && checkLength(' . $o . ',"' . $c . '",6,16,"' . self::$msg['valid_form_pass'] . '");';
            elseif ($this->fields[$i]['status'] == 'X' && $f == 'password' && $o == 'passwd_re')
                $str.='
                    bValid = bValid && eqPass(passwd,passwd_re);';

            elseif ($this->fields[$i]['status'] == 'X' && ($f == 'text' || $f == 'autocomplete' || $f == 'date_form' || $f == 'time_form'))
                $str.='
                    bValid = bValid &&  checkLength(' . $o . ',"' . $c . '",1,255,"' . $a . '");';

            elseif ($this->fields[$i]['status'] == 'X' && $f == 'textarea')
                $str.='
                    bValid = bValid && checkLength(' . $o . ',"' . $c . '",10,1000,"' . $a . '");';
            elseif ($this->fields[$i]['status'] == 'X' && $f == 'text_fields') {
                foreach ($o as $value) {
                    $str.='
                        bValid = bValid && anyNoEmpty(' . $value . ',"' . $a . '");';
                }
            } elseif ($this->fields[$i]['status'] == 'X0' && ($f == 'textarea' || $f == 'text'))
                $str.='
                    bValid = bValid && oneOrMoreEmpty(' . $o . ',"' . $a . '");';


            elseif ($this->fields[$i]['status'] == 'X' && ($f == 'date_form'))
                $str.='
                    bValid = bValid && checkEmpty(' . $o . ',"' . $c . '","' . $a . '");';

            elseif ($this->fields[$i]['status'] == 'X' && ($f == 'checkbox' || $f == 'multy_checkbox'))
                $str.='
                    bValid = bValid && NotCheckedCheckbox(' . $o . ',"' . $a . '");';

            elseif ($this->fields[$i]['status'] == 'X' && $f == 'radio')
                $str.='
                    bValid = bValid && NotCheckedRadio(' . $css . ',' . $o . ',"' . $c . '","' . $a . '");';

            elseif ($this->fields[$i]['status'] == 'X' && ($f == 'select' || $f == 'select_simple' ))
                $str.='
                    bValid = bValid && NotSelected("' . $o . '",' . $o . ',"' . $c . '","' . $a . '","' . $code_eval . '");';

            if ($this->fields[$i]['js_add_valid']) {
                $str.="\n" . $this->fields[$i]['js_add_valid'];
            }
        }
        return $str;
    }

    private function get_name_dialog_title() {
        foreach ($this->fields as $a) {
            foreach ($a as $k => $v) {
                if ($v == $this->dialog_title_field)
                    return $a['value'];
            }
        }
    }

    private function valid_submite_dialog_form() {
        $this->str.='

	<script>
           
            $(function() {
                $("#' . $this->dialog_name . '").dialog("destroy");
                var allVals = [];
                var withOutValid = false;
                sended_ajax = false;
                if ($(".Checkboxes").is(":checked")){
                    $(".Checkboxes:checked").each(function(){
                    allVals.push($(this).val());
                  });
            }

                
            
            ' . (($this->is_send_ajax) ? '
                    $( "#' . $this->nameform . '" ).submit(function( event ) {
                    
                            event.preventDefault();
                            var bValid = true;
                            ' . $this->gen_js1() . '
                             if (bValid) {
                                updateTips("");
                                form_send_dialog_by_ajax(bValid);
                             }
                   });  

                       ' : '') . '
        ';
        $this->gen_js();
        $this->str.='
            $("#' . $this->dialog_name . '").dialog({
                //autoOpen: false,
                ' . (($this->dialog_width != '') ? 'width: ' . $this->dialog_width . ',' : '') . '
                ' . (($this->dialog_height != '') ? 'height: ' . $this->dialog_height . ',' : '') . '
                ' . (($this->dialog_is_modal) ? 'modal: true,' : '') . '
                
                    
                ' . (($this->dialog_title != '') ? 'title: "' . $this->dialog_title . ' :: ' . $this->get_name_dialog_title() . '",' : '') . '

                buttons: [

                        {
                            id: "' . $this->nameform . '_button-ok",
                            text: "' . self::$msg['submit_button'] . '",
                            click: function() {                        
                                $("#' . $this->nameform . '_button-ok").button("enable");
                                allFields.removeClass(\'ui-state-error\');
                        var bValid = true;
                                
                            ' . $this->gen_js1() . '
                             if (bValid) {
                                    updateTips("");
                                
                                        /* если вдруг форма отправлена ajax(ом)*/              
        ' . (($this->is_send_ajax) ? '
                                            if (!sended_ajax) 
                                                form_send_dialog_by_ajax(bValid);
                                
        ' : '
            /* иначе просто submit*/      
            $("#' . $this->nameform . '").submit();

        ') . '                            
                            }
                             }

                        },
                        {
                            id: "' . $this->nameform . '_button-cancel",
                            text: " ' . self::$msg['button_cancel'] . '",
                            click: function() {
                                $(this).dialog("close");
                                $("#' . $this->dialog_name . '").html(\'<div class="loading"></div>\');
                        }
                    }
                    ],
                    close: function() {
                        allFields.val(\'\').removeClass(\'ui-state-error\');
                        $("#' . $this->dialog_name . '").html(\'<div class="loading"></div>\');
                        $("#dialog_alert").dialog("close");
                    },
                    open: function() {
                        $("#' . $this->nameform . '_button-cancel")
                        //.removeClass(\'ui-widget ui-widget-content ui-state-default\')
                        //.addClass(\'dialog-cancel-button\');
                        $("#' . $this->nameform . '_button-ok")
                        //.removeClass(\'ui-widget ui-widget-content ui-state-default\')
                        //.addClass(\'dialog-save-button\');
                    }



                });






            });
           function form_send_form_by_ajax(ajax_tag_respons,url_form,nameform,params_add){     
                //alert(url_form);
                
                
                params = $("#"+nameform).serialize()+\'&\'+params_add;
                
                $.ajax({type:"POST", url: url_form, data:params, success: function(response) {
                    
                    if (response != "") {
                        if (response.match(/^error/)) { 
                            $("#dialog_alert").html(response.replace("error", ""));
                            $("#dialog_alert").dialog({
                                    title: " ' . self::$msg['error'] . ' "
                            });
                            $("#dialog_alert").dialog("open");
                        } else {
                            //$("#globalLoader").hide();
                            $("#"+ajax_tag_respons).html(response).fadeIn("slow");
                            
                        }                        
                    }
                }});
        }    
        function form_send_dialog_by_ajax(is_close_dialog){     
                sended_ajax = true;
            
            if ("' . $this->dialog_name . '" != "' . $this->ajax_tag_respons . '") {
                    $("#' . $this->ajax_tag_respons . '").html(\'<img src="' . $this->loadingImg . '">\');
                }
                
                $.ajax({type:"POST", url: "' . $this->url . '", data:$("#' . $this->nameform . '").serialize(), success: function(response) {
                    
                    if (response != "") {
                        if (response.match(/^error/)) {
                            
                            $("#dialog_alert").html(response.replace("error", ""));
                            $("#dialog_alert").dialog({
                                    title: " ' . self::$msg['error'] . ' "
                            });
                            $("#dialog_alert").dialog("open");
                            is_close_dialog=false;
                            sended_ajax = false;
                        } else {
                            is_close_dialog = ' . (($this->autosubmit) ? "false" : "true") . ';
                            $("#' . $this->ajax_tag_respons . '").html(response).fadeIn("slow");
                        }                        
                    }
                    if(is_close_dialog)
                        $("#' . $this->dialog_name . '").dialog("close");

                }});
        }
	</script>
       
        ';
    }

    private function valid_submite_form() {

        $additional_submit_buttons = '';
        $additional_submit_buttons_counter = 1;
        $additional_submit_buttons_clicks_str = '';
        foreach ($this->additional_submit_buttons as $button_data) {
            $class_name = 'button_submit' . $additional_submit_buttons_counter;
            $additional_submit_buttons_ids_str .= ',#' . $class_name;
            $additional_submit_buttons .= '<a href="#" title="' . $button_data[0] . ' " id="' . $class_name . '" class="ui-state-default ui-corner-all">' . $button_data[0] . ' </a>';

            $additional_submit_buttons_clicks_str .= '
        	    $("#' . $class_name . '").click(function() {
		        	' . $button_data[1] . '
		    	});
        	';

            $additional_submit_buttons_counter++;
        }

        $this->str.='
	<script>
        $(function() {
           ';
        $this->gen_js();
        $this->str.='
        
		' . $additional_submit_buttons_clicks_str . '

		$("#button_submit").click(function() {
    		' . $this->before_std_submit . '
                });
		
		$("#button_submit").click(function() {
                    var bValid = true;
                    
        ';
        $this->gen_js1();

        $this->str.='
                     if (bValid || withOutValid) {
                                    //updateTips("ok");
                                    /* если вдруг форма отправлена ajax(ом)*/              
                                        ' . (($this->is_send_ajax) ? '
                                            $( "#' . $this->nameform . '" ).submit(function( event ) {
                                                event.preventDefault();
                                                var bValid = true;
                                                  
                                                    //$("#' . $this->ajax_tag_respons . '").html();
                                                    //    alert("' . $this->ajax_tag_respons . '")
                                                    // $("#' . $this->ajax_tag_respons . '").html(\'<div class="loading"></div>\').fadeIn("slow");
                                                    form_send_form_by_ajax("' . $this->ajax_tag_respons . '","' . $this->url . '","' . $this->nameform . '");
                                                 
                                            }); 
                                        ' : '
                                     /* иначе просто submit*/      
                         $("#' . $this->nameform . '").submit();

                                        ') . '  
                     }
                });


       });
        </script>
         <div class="submit_holder"> ' . self::button(array('name' => 'button_submit', 'title' => self::$msg['submit_button'], 'css' => 'ui-button-text-only ')) . '
            ' . $additional_submit_buttons . '
         </div>

        ';
    }

    private function msg_alert() {
        return '<style type="text/css">
                .validate_' . $this->nameform . ' { border: 1px solid transparent; padding: 0.3em; font-size:80%; color:red;}
        </style>
        <p class="validate_' . $this->nameform . '"></p>';
    }

    public function print_form_site() {
        if (is_array(($this->fields))) {
            $this->fields = array_values($this->fields);
            if ($this->count_view_col > 1 && $this->count_view_col % 2 == 0)
                $col_range = ceil(count($this->fields) / $this->count_view_col);
            else
                $col_range = count($this->fields);

            //echo $col_range;
            if ($this->count_view_col) {
                $col_range = intval(count($this->fields) / $this->count_view_col);
            } else {
                $col_range = count($this->fields);
            }
        }
        $this->str.= $this->open_form();
        $this->str.=($this->is_submit_button) ? $this->msg_alert() : '';
        $this->str.='
        <table border="0" cellspacing="0" class="tbl_form">
            <tr><td id="form_col0"><table>';

        for ($i = 0; $i < count($this->fields); $i++) {

            if ($this->fields[$i]['form'] == 'title' && $this->fields[$i]['status'] != '') {
                $this->str.= '<tr  id="tr' . $i . '" class="title" ><td valign="top" class="titles" colspan="2">' . $this->fields[$i]['caption'] . '</td></tr>';
            } else {
                $this->str.= '<tr id="tr' . $i . '">';

                $ss = ($this->fields[$i]['status'] == 'X') ? '<sup class="red">*</sup>' : '';


                if ($this->fields[$i]['status'] != '') {
                    $this->str.= (($this->fields[$i]['caption'] != '') ? '<td class="form_title" id="form_title' . $i . '" >' . $this->fields[$i]['caption'] . $ss . '</td>' : '');
                    $this->str.= '<td class="form_elem"  id="form_elem' . $i . '" ' . (($this->fields[$i]['caption'] == '') ? 'colspan=2' : '') . '>
                                  <span class="caption_add">' . $this->fields[$i]['caption_add'] . '</span>
                                  ';
                    if (method_exists($this, $this->fields[$i]['form'])) {
                        $this->str.='<div id="contener_elem_' . $i . '" class="contener_elem">' . $this->__call($this->fields[$i]['form'], array($this->fields[$i]));
                        $this->str.=($this->fields[$i]['is_disabled']) ? self::button(array(
                                    title => 'disabled/enabled field',
                                    name => '',
                                    ico => 'ui-icon-close',
                                    css => 'is_disabled ui-button-icon-only')) : '';
                        $this->str.= '</div>';
                    } else
                        $this->str.= 'method not exists';
                    $this->str.='</div>';
                    $this->str.='<span class="caption_add_bottom">' . $this->fields[$i]['caption_add_bottom'] . '</span>';
                    $this->str.='</td>';
                }
                $this->str.= '</tr>';
            }
            if ($this->count_view_col > 1 && $i > 1 && (($i) % $col_range) == 0)
                $this->str.= '</td></table><td  id="form_col' . $i . '"><table>';
        }
        $this->str.= '</td></tr></table>
            </table>';
        if ($this->is_submit_button) {
            if ($this->is_form_In_dialog) {
                $this->valid_submite_dialog_form();
            } else {
                $this->valid_submite_form();
            }
        }
        if (is_array($this->hiddens))
            $this->hidden_forms();

        $this->str.= $this->close_form();
    }

    public function print_form_filter($br_cat = false, $is_form_open = true, $is_close = true) {
        $this->str.= ($is_form_open) ? $this->open_form() : '';
        $this->str.=($this->is_submit_button) ? $this->msg_alert() : '';
        $this->str.='<div class="forms">';

        for ($i = 0; $i < count($this->fields); $i++) {
            $name = (is_array($this->fields[$i]['name']) ? implode('_', $this->fields[$i]['name']) : $this->fields[$i]['name']);
            if ($this->fields[$i]['form'] == 'title') {
                $this->str.= '<div class="title">' . $this->fields[$i]['caption'] . '</div>';
            }
            if ($this->fields[$i]['form'] != 'title' && $this->fields[$i]['status'] != '') {
                if ($this->fields[$i]['status'] == 'X' && $this->fields[$i]['caption']) {
                    $this->fields[$i]['caption'].='<sub clas="red">*</sub>';
                }

                $this->str.= '<div class="form ' . $this->fields[$i]['css_con'] . '" id="con_' . $name . '">';
                if ($this->is_cheked) {
                    $name = 'is_cheked_' . $name;
                    $this->str.= $this->checkbox(array('name' => $name, 'value' => 1, 'checked' => $this->fields[$i]['is_cheked'], 'prop' => 'class="is_cheked"'));
                }

                $this->str.=(($this->fields[$i]['caption']) ? '<span>' . $this->fields[$i]['caption'] . '</span>' : '');
                $this->str.=(($br_cat) ? '<br>' : '');

                $this->str.=(($this->fields[$i]['caption_add']) ? '<div class="caption_add">' . $this->fields[$i]['caption_add'] . '</div>' : '');
                $this->str.= $this->__call($this->fields[$i]['form'], array($this->fields[$i]));
                $this->str.='</div>';
            }
        }

        $this->str.='</div>';
        if ($this->is_submit_button) {
            if ($this->is_form_In_dialog) {
                $this->valid_submite_dialog_form();
            } else {
                $this->valid_submite_form();
            }
        }
        if (is_array($this->hiddens))
            $this->hidden_forms();

        if ($is_close) {
            $this->str.= $this->close_form();
        }
    }

}

?>