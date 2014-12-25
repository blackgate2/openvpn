<?php

class navigation {

    private $out;
    private $actpid;
    
    protected $q;
    public static $lang;
    public static $pre;
    public static $page;
    public static $str_lang;
    private $right_part;
    private $str_main_menu;
    private $str_left_menu;
    private $str_bottom;
    private $data;
    private $is_add_dot_html_url;




    public function __construct() {
        $this->q = DB::Open();
        $this->out = array();
        $this->init_vars();
        $this->get_data();
        $this->set_data();
        $this->array_to_tree();
        $this->buildMenu($this->out);
        $this->is_add_dot_html_url=true;
    }

    private function init_vars() {
        $url_arr = $this->ParseUrl($_SERVER['REQUEST_URI']);
        
       
        for ($i = 0; $i < count($url_arr); $i++) {
            if ($url_arr[$i] == 'ru' || $url_arr[$i] == 'en') {
                $this->lang = $url_arr[$i];
                $_SESSION['lang']=$this->lang;
            } else {
                $this->page = $url_arr[$i];
            }
        }
        
        if ($this->lang=='' && !isset($_SESSION['lang'])){
            $this->lang='ru';
            $_SESSION['lang']=$this->lang;
        }elseif($this->lang=='' && $_SESSION['lang']){
            $this->lang=$_SESSION['lang'];
        }
        
        if ($this->page == 'index.php')
            $this->page = '';
        
        
        if ($this->lang == 'en') {
            $this->str_lang = '<span>En</span> <a class="ui-state-default ui-corner-all" href="' . (($this->page != '') ? '/' : '') . $this->page . '/ru">Ru</a>';
            $this->pre = 'en_';
            $_SESSION['pre']=$this->pre;
        }elseif ($this->lang == 'ru') {
            $this->pre = '';
            $this->lang = 'ru';
            $_SESSION['pre']=$this->pre;
            $this->str_lang = '<a class="ui-state-default ui-corner-all" href="' . (($this->page != '') ? '/' : '') . $this->page . '/en">En</a> <span>Ru</span>';
        }

    }
    
    protected  function get_data() {
        $this->q->qry('Select * From ?menu Where status = \'1\' Order by sort', $this->pre);
    }
    private function set_data() {

        $this->data = array();
        
        while ($d = $this->q->getrow()) {
            $url = '';

            if ($d['url'] != ''){
                $url.= (!preg_match('/^http/', $d['url'])) ? '/' . $d['url'].'.html' : $d['url'];

            }elseif ($d['modname'])
                $url.="/$d[modname]";
            elseif ($d['id_pages'] != 0)
                $url.='/?page=' . $d['id_pages'];
            else {
                $url = '/';
            }

            $actmenu = 0;
            if ($this->page == $d['url']) {
                $actmenu = $d['id'];
                $this->actpid = ($d['pid']) ? $d['pid'] : $d['id'];
            }


            $tmp = array(
                'id' => $d['id'],
                'pid' => $d['pid'],
                'title' => $d['name'],
                'place' => $d['place'],
                'url' => $url,
                'mod' => $d['modname'],
                'right_part' => $d['right_part'],
                'actmenu' => $actmenu,
            );
            array_push($this->data, $tmp);
        }
        
    }

    private function array_to_tree() {
        $hash = array();
        for ($i = 0; $i < count($this->data); $i++) {
            if ($this->data[$i]['id'] == $this->actpid || $this->data[$i]['pid'] == $this->actpid)
                $this->data[$i]['actpid'] = $this->actpid;
            $id = $this->data[$i]['id'];
            $hash[$id] = &$this->data[$i];
        }


        for ($i = 0; $i < count($this->data); $i++) {
            $pid = $this->data[$i]['pid'];
            if (isset($hash[$pid])) {
                if (!isset($hash[$pid]['childs']))
                    $hash[$pid]['childs'] = array();
                $hash[$pid]['childs'][] = &$this->data[$i];
            }else {
                $this->out[] = &$this->data[$i];
            }
        }
    }

    public function getLang() {
        return $this->lang;
    }

    public function getMainMenu() {
        return $this->str_main_menu;
    }
    public function getBottomMenu() {
        return $this->str_bottom;
    }
    public function getRightPart() {
        return $this->right_part;
    }

    public function getLeftMenu() {
        return $this->str_left_menu;
    }

    private function buildMenu($a) {
        //print_r($a);exit();
        for ($i = 0; $i < count($a); $i++) {

            if ($a[$i]['id'] != $a[$i]['actmenu'])
                $menu = '<A href="' . $a[$i]['url'] .'" class="menu">' . $a[$i]['title'] . '</A>';
            else {
                $menu = '<SPAN class=" menu ">' . $a[$i]['title'] . '</SPAN>';
                $this->right_part = $a[$i]['right_part'];
            }
            $this->str_main_menu.='<li>' . $menu;
            if (is_array($a[$i]['childs'])) {
                $this->str_main_menu.='<ul>' . "\n";
                $this->buildMenu($a[$i]['childs']);
                $this->str_main_menu.='</ul>' . "\n";
            }
            $this->str_main_menu.='</li>' . "\n";

            if ($a[$i]['pid'] && $a[$i]['pid'] == $a[$i]['actpid']) {

                $this->str_left_menu.='<li>' . $menu . '</li>' . "\n";
            }
            if (!$a[$i]['pid']){
                $menu =  str_replace('ui-state-default ui-corner-all', '', $menu);
                $menu =  str_replace('ui-state-hover', '', $menu);
                $this->str_bottom.='<li>' . $menu . '</li>' . "\n";
            }
            
        }
    }

    private function ParseUrl($str = "") {
        //echo $str."------Parse<br>";
        //$arr = 0;
        $str = str_replace('.html', '', $str);
        //$str=str_replace('?lang=ru','',$str);
        //$str=str_replace('?lang=eng','',$str);
        if ($str) {
            $arr = array();
            $tmp_arr = explode('/', $str);
            if ($tmp_arr && is_array($tmp_arr)) {
                foreach ($tmp_arr as $v) {
                    if (!$v && $v != '0')
                        continue;
                    if ($v == 'oppa')
                        continue;
                    if ($v{0} == '?')
                        return $arr;
                    $arr[] = $v;
                }
            }
            //if(!$arr) $arr=0;
            return $arr;
        }else {
            return 0;
        }
    }

}

?>