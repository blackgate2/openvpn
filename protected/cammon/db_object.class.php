<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of queryObject
 *
 * @author Oleg
 */
class db_object {

    public $obj;
    private $msg_alert;
    private $q;
    private $id;
    private $table;

    public function __construct($q, $obj, $table = '', $id = '') {
        $this->q = $q;
        $this->obj = $obj;
        $this->table = $table;
        $this->id = $id;
    }

    public function getMess() {
        return $this->msg_alert;
    }

    public function get_fields() {

        $array_fields = array();

        foreach ($this->obj as $v) {
            if (isset($v['name']) && !isset($v['no_sql']) && !preg_match('/\_ids/', $v['name'])) {
                array_push($array_fields, $v['name']);
            }
        }
        return $array_fields;
    }
    public function set_val_by_name($name,$value) {

        
        for ($i = 0; $i < count($this->obj); $i++) {
            if ($this->obj[$i]['name']==$name) {
                $this->obj[$i]['value']=$value;
                break;
            }
        }
        
    }
    private function sql_obj() {
        //print_r($this->get_fields());
        return 'Select ' . implode(',', $this->get_fields('name')) . ' From ' . $this->table . ' ' . (($this->id) ? ' Where id=' . $this->id : 1);
    }

    public function get_data_obj() {

        foreach ($this->q->get_fetch_data($this->sql_obj()) as $k => $val) {
            foreach ($this->obj as $i => $v) {

                if ($v['name'] == $k) {
                    //echo $v['name'] . " == $k <br>";
                    if (($v['form'] == 'select' || $v['form'] == 'select_simple') && is_array($this->obj[$i]['value'])) {
                        $val = explode(",", $val);
                    } elseif ($v['form'] == 'text') {
                        $val = common::unesc($val);
                    } elseif ($v['form'] == 'date_form' && $v['format']) {
                        $val = strDate::Date($val, $v['format']);
                    }
                    if ($v['form'] == 'checkbox') {
                        $this->obj[$i]['checked'] = ($val) ? 1 : '';
                    } else {
                        $this->obj[$i]['value'] = $val;
                    }
                }
            }
        }

        //print_r($this->obj);
    }

    public function post_data_form() {

        $arr_fields_vals = array();

        //print_r($arr_fields);
        //require_once('fileupload.class');
        // -------- цикл по полям объекта ------
        foreach ($this->obj as $v) {
            if (strstr($v['name'], '_ids')) {
                unset($v);
            }
            if ($v['form'] == 'date_form' && $_POST[$v['name']]) {
                //echo strDate::DateToSql($_POST[$v['name']]);
                $arr_fields_vals[$v['name']] = strDate::DateToSql($_POST[$v['name']]);
                // если файл
            } elseif ($v['form'] == 'file') {

                # путь к файлу
                $act_attachment_path = $v['path_img'];

                # имя файла
                $upload_file_name = $_FILES[$v['name']];

                # возможные типы файлов
                $acceptable_file_types = $v['acceptable_file_types'];


                # расширение по умолчанию
                $default_extension = "";

                # режим загрузки: 1 переписать, 2 скопировать(copy)
                $mode = $v['upload_mode_save'];

                # максимальный размер картинки
                $img_x = $v['img_x'];
                $img_y = $v['img_y'];

                # максимальный размер preview
                $preview_x = $v['preview_x'];
                $preview_y = $v['preview_y'];

                # поворот картинки превью
                $rotate_small = $v['rotate_small'];
                # поворот картинки большой
                $rotate_max = $v['rotate_max'];

                # обрезка
                $crop_small = $v['crop_small'];
                # обрезка
                $crop_max = $v['crop_max'];

                # создание класса uploader
                $my_uploader = new uploader;

                # задаем максимальный размер файла
                $my_uploader->max_filesize($v['upload_max_size']);

                # задаем максимальное разрешение которое можно заливать
                $my_uploader->max_image_size($v['upload_maxx'], $v['upload_maxy']);

                # вотермарк
                $upload_add_img = $v['upload_add_img'];


                if ($my_uploader->upload($upload_file_name, $acceptable_file_types, $default_extension)) {

                    $success = $my_uploader->save_file($act_attachment_path, $mode);
                }
                if ($success) {
                    // Successful upload!

                    if (preg_match("/\\.(jpg|gif|png?)$/", $my_uploader->file['name'])) {

                        if ($my_uploader->file['name'] != '' && $img_x && $img_y) {
                            createMiniImage($act_attachment_path . $my_uploader->file['name'], $act_attachment_path . $my_uploader->file['name'], $img_x, $img_y, $crop_max);
                        }
                        if ($my_uploader->file['name'] != '' && $preview_x && $preview_y) {

                            createMiniImage($act_attachment_path . $my_uploader->file['name'], $act_attachment_path . "small_" . $my_uploader->file['name'], $preview_x, $preview_y, $crop_small);
                        }
                    }

                    $this->msg_alert.= ( $my_uploader->file['name']) ? ' [' . $my_uploader->file['name'] . '] ! <br>' : '';

                    // добавляем в базу название файла
                    $arr_fields_vals[$v['name']] = $my_uploader->file['name'];
                } else {
                    // ERROR reporting...
                    if ($my_uploader->errors) {
                        while (list($key, $var) = each($my_uploader->errors)) {
                            $this->msg_alert.= $var . "<br>";
                        }
                    }
                }
            } elseif ($v['form'] == 'checkbox' && isset($_POST[$v['name']])) {
                $arr_fields_vals[$v['name']] = 1;
            } elseif ($v['form'] == 'checkbox' && !isset($_POST[$v['name']])) {
                $arr_fields_vals[$v['name']] = 0;
            } else {// -- остальные типы форм 
                foreach ($_POST as $key => $value) {

                    if ($key == $v['name']) {

                        if (is_array($value)) {
                            $value = implode(',', $value);
                        } else {
                            $value = mysql_real_escape_string(trim($value));
                        }
                        $arr_fields_vals[$key] = $value;
                    }
                }
            }
        }
        //print_r($arr_fields_vals);
        return $arr_fields_vals;
    }

}

