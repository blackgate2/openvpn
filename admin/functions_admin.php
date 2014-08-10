<?php
// -----------------------------------  -----------------------------------
function displayHeaderAdmin($msg) {
    echo '
    <html>
    <head>
    <meta http-equiv="content-type" content="text/html; charset=' . commonConsts::charset . '">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="-1">
    
    <!-- Load jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
    
    <!-- Timer func -->
    <script type="text/javascript" src="/js/jquery.timer.js" language="JavaScript"></script>

    <link type="text/css" href="/css/ui-lightness/jquery-ui-1.8rc3.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="/js/launch.js" language="JavaScript"></script>
    
    <link type="text/css" href="/css/forms.css" rel="stylesheet" />
    <link type="text/css" href="/css/table.css" rel="stylesheet" />
    <link type="text/css" href="/css/styleadm.css" rel="stylesheet" />

    <script type="text/javascript" src="/admin/TinyMCEfull/jscripts/tiny_mce/plugins/filemanager/js/mcfilemanager.js"></script>

    <title></title>
    
        <script>
        $(function() {
        
                var timeout = ' . commonConsts::admin_refresh_page . ';
                var timer;
                timer = $.timer(timeout, function() {
                    window.location.href = window.location.href;
                    window.location.reload();
                });
                $("#dialog_modal").bind("dialogopen", function(event) {
                    timer.stop();
                });
                $("#dialog_modal").bind("dialogclose", function(event) {
                     timer.reset(timeout);
                });
                

function fixDiv() {
      var $cache = $(\'#getFixed\'); 
      if ($(window).scrollTop() > 100) 
        $cache.css({\'position\': \'fixed\', \'top\': \'10px\'}); 
      else
        $cache.css({\'position\': \'relative\', \'top\': \'auto\'});
    }
    $(window).scroll(fixDiv);
    fixDiv();

        });
        </script>


    <base target="_self">

    </head>
    <body>
   
 ';
}

function displayHeadlineAdm($headline, $str_filters='', $str_navigation='') {

    return (($headline != '')?'<h1 align="center">' . $headline . '</h1>':'').
           (($str_filters != '') ?'	<div id="effect" class="ui-widget-content ui-corner-all">

		' . (($str_filters != '') ? '' . $str_filters . '' : '') . '
                ' . (($str_navigation != '') ? '' . $str_navigation . '' : '') . '
                    </div><a href="#" id="button_toggle_filter" class="ui-state-default ui-corner-all">скрыть фильтры</a></br>' : '');
}

function displayFooterAdmin() {
    return '</div>
            <div id="dialog" title="' . $msg['dialog_title'] . '"></div>
            <div id="dialog_modal" title="' . $msg['dialog_modal_title'] . '"></div>
            <div id="dialog_confirm" title="' . $msg['dialog_confirm_title'] . '"></div>
            <div id="dialog_alert" title="' . $msg['dialog_alert_title'] . '"></div>
		</body>
	</html>';
}

// -----------------------------------  ok, error -----------------------------------
function ok($ok) {
    return '<div class="ui-state-highlight"> ' . $ok . '</div>';
}

function error($mes_error) {
    return '<div class="ui-state-error"> ' . $mes_error . '</div>';
}

function print_menu($a, &$id, $pid, &$str, $child = 0) {
    global $last_pid;

    for ($i = 0; $i < count($a); $i++) {
        if ($a[$i]['fold']) {

            $str.="d.add($id,$pid,'" . $a[$i]['title'] . "');\n";
            $last_pid[$child] = $pid;
            $pid = $id;
            $id++;
        } else {
            $str.="		d.add($id,$pid,'" . $a[$i]['title'] . "','" . $a[$i]['href'] . '?';
            $str1 = '';
            foreach ($a[$i] as $k => $v) {
                if ($k != 'href' && $k != 'title' && $k != 'fold') {
                    $v = str_replace("\n", ' ', $v);
                    $v = str_replace('  ', ' ', $v);
                    $str1.=$k . '=' . urlencode($v) . '&';
                }
            }
            $str.=trim($str1, '&');
            $str.="'); \n";
            $id++;
        }
        if (isset($a[$i]['childs'])) {

            print_menu($a[$i]['childs'], $id, $pid, $str, $child + 1);

            if (!$child) {
                $pid = (isset($a[$i]['pid'])) ? $a[$i]['pid'] : 0;
            } elseif ($pid >= 1) {
                $pid = $last_pid[$child];
            }
        }
    }

    return $str;
}

function trans($fileName) {
    $trans = array
        (
        ' ' => '_',
        '№' => 'No',
        'а' => 'a', 'А' => 'A',
        'б' => 'b', 'Б' => 'B',
        'в' => 'v', 'В' => 'V',
        'г' => 'g', 'Г' => 'G',
        'д' => 'd', 'Д' => 'D',
        'е' => 'e', 'Е' => 'E',
        'ё' => 'e', 'Ё' => 'E',
        'ж' => 'zh', 'Ж' => 'Zh',
        'з' => 'z', 'З' => 'Z',
        'и' => 'i', 'И' => 'I',
        'й' => 'i', 'Й' => 'I',
        'к' => 'k', 'К' => 'K',
        'л' => 'l', 'Л' => 'L',
        'м' => 'm', 'М' => 'M',
        'н' => 'n', 'Н' => 'N',
        'о' => 'o', 'О' => 'O',
        'п' => 'p', 'П' => 'P',
        'р' => 'r', 'Р' => 'R',
        'с' => 's', 'С' => 'S',
        'т' => 't', 'Т' => 'T',
        'у' => 'u', 'У' => 'U',
        'ф' => 'f', 'Ф' => 'F',
        'ч' => 'ch', 'Ч' => 'Ch',
        'х' => 'h', 'Х' => 'H',
        'ц' => 'c', 'Ц' => 'C',
        'щ' => 'sh', 'Щ' => 'Sh',
        'ш' => 'sh', 'Ш' => 'Sh',
        'э' => 'e', 'Э' => 'E',
        'ю' => 'u', 'Ю' => 'U',
        'я' => 'ya', 'Я' => 'Ya',
        'ы' => 'y', 'Ы' => 'Y',
        'ь' => '', "'" => '`',
        'ъ' => ''
    );


    $fileName = strtr(strtolower($fileName), $trans);
//  	$fileName = strtr($fileName,$trans);
    // if (eregi ('[^a-z0-9\._\-]',$fileName))  return false;
    return $fileName;
}


function log_off() {
    header("WWW-Authenticate: Basic realm=\"\"");
    header("HTTP/1.0 401 Unauthorized");
    echo '
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
        <html>
        <head>
        <title>Аминистративный интерфейс</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        </head>
        <body>
        To continue working with the system need  <a href="/admin">to login >></a>
        </body>
        </html>';
    exit();
}

function log_in() {
    echo '
        <html>
        <head>
                <title></title><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        </head>

        <!-- frames -->
        <frameset  cols="200,*">
            <frame name="menu" src="menu.php" marginwidth="0" marginheight="0" scrolling="auto"  frameborder="0">
            <frame name="main" src="index.php" marginwidth="0" marginheight="0" scrolling="auto" frameborder="1">
        </frameset>

        </html>
        ';
    exit();
}