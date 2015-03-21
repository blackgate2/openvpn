<?php

function get_date($prefix) {

    $year = (isset($_POST[$prefix . 'year'])) ? $_POST[$prefix . 'year'] : "0000";
    $mon = (isset($_POST[$prefix . 'mon'])) ? $_POST[$prefix . 'mon'] : "00";
    $day = (isset($_POST[$prefix . 'day'])) ? $_POST[$prefix . 'day'] : "00";
    return "$year-$mon-$day";
}

function get_date_time($prefix) {

    $hour = (isset($_POST[$prefix . 'hour'])) ? $_POST[$prefix . 'hour'] : "00";
    $minute = (isset($_POST[$prefix . 'minute'])) ? $_POST[$prefix . 'minute'] : "00";
    $second = (isset($_POST[$prefix . 'second'])) ? $_POST[$prefix . 'second'] : "00";

    return get_date($fieldname, $prefix) . " " . " $hour:$minute:$second";
}




// ----------------------------------- ������ -----------------------------------
// ������ \" � " �� &quot;
function unesc($str) {
    $str = str_replace('"', "&quot;", $str);
    //$str=str_replace("'","&rsquo;",$str);
    //$str=str_replace('\\','\\ ',$str);
    return $str;
}

function censor($str) {
    $str = ereg_replace("<[^>]*>", "", $str);
    $str = str_replace("&", "&amp;", $str);
    $str = str_replace("\\\"", "&quot;", $str);
    return $str;
}

// ������� ��������� ������������ ������� email'a
function goodemail($email) {
    return ((ereg("([a-zA-Z0-9._-]{1,}@[a-zA-Z0-9._-]{1,}\\.[a-zA-Z0-9]{1,})", $email, $tmp)) && ($tmp[1] == $email));
}

// ----------------------------------- �����, ���������� -----------------------------------
// ������� �������� �����. ����� ��������� ��������� �� ������������� ����
function deletefile($file) {
    if (file_exists($file)) {
        unlink($file);
        return $file;
    }
}

function chomp($str) {
    while ((substr($str, strlen($str) - 2, strlen($str)) == "\r\n") || (substr($str, strlen($str) - 1, strlen($str)) == "\n")) {
        if (substr($str, strlen($str) - 2, strlen($str)) == "\r\n") {
            $str = substr($str, 0, strlen($str) - 2);
        } elseif (substr($str, strlen($str) - 1, strlen($str)) == "\n") {
            $str = substr($str, 0, strlen($str) - 1);
        }
    }
    return $str;
}

function copyfile($urlin, $urlto, $cmd = 0644) {
    if ((copy($urlin, $urlto)) && (chmod($urlto, $cmd)))
        return 1;
    else
        return 0;
}

function Expire() {
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Pragma: no-cache"); // HTTP/1.0
    header('content-type: text/html;charset=' . commonConsts::charset . " \r\n");
}

function check_email($Email) {
    if (ereg('^[_\.0-9a-z-]+@([0-9a-z][-0-9a-z\.]+)\.([a-z]{2,3}$)', $Email)) {
        return 1;
    } else {
        return 0;
    }
}

function save($file, $comtent) {
    $f = fopen($file, "w");
    flock($f, LOCK_EX);
    fputs($f, $comtent);
    flock($f, LOCK_UN);
    fclose($f);
}

function send_mail($to, $from, $namefrom, $subject, $message, $convert_from = '', $convert_to = '') {

    $header = "From: \"$namefrom\"<$from>\r\nReply-To: $from\r\nX-Mailer:PHP\r\nMime-Version: 1.0\r\nContent-Type: text/html; charset=\"utf-8\"\r\nReturn-Path:<$from>\r\n";
  // exit($header);
    mail($to, $subject, $message, $header);
}

function addArray(&$array, $key, $val) {
    $tempArray = array($key => $val);
    $array = array_merge($array, $tempArray);
}

function substr_last($str, $co) {
    $str = substr($str, 0, strlen($str) - $co);
    return $str;
}

function substr_first($str, $co) {
    $str = substr($str, $co);
    return $str;
}

function array_push_assoc(&$array, $key, $val) {
    $array[$key] = $val;
}

function createMiniImage($src, $dst, $width, $height, $crop) {

    if (!list($w, $h) = getimagesize($src))
        return "Unsupported picture type!";

    $type = strtolower(substr(strrchr($src, "."), 1));
    if ($type == 'jpeg')
        $type = 'jpg';
    switch ($type) {
        case 'bmp': $img = imagecreatefromwbmp($src);
            break;
        case 'gif': $img = imagecreatefromgif($src);
            break;
        case 'jpg': $img = imagecreatefromjpeg($src);
            break;
        case 'png': $img = imagecreatefrompng($src);
            break;
        default : return "Unsupported picture type!";
    }

    // resize
    if ($crop) {
        if ($w < $width or $h < $height)
            return "Picture is too small!";
        $ratio = max($width / $w, $height / $h);
        $h = $height / $ratio;
        $x = ($w - $width / $ratio) / 2;
        $w = $width / $ratio;
    }
    else {
        if ($w < $width and $h < $height)
            return "Picture is too small!";
        $ratio = min($width / $w, $height / $h);
        $width = $w * $ratio;
        $height = $h * $ratio;
        $x = 0;
    }

    $new = imagecreatetruecolor($width, $height);

    // preserve transparency
    if ($type == "gif" or $type == "png") {
        imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
        imagealphablending($new, false);
        imagesavealpha($new, true);
    }

    imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

    switch ($type) {
        case 'bmp': imagewbmp($new, $dst);
            break;
        case 'gif': imagegif($new, $dst);
            break;
        case 'jpg': imagejpeg($new, $dst);
            break;
        case 'png': imagepng($new, $dst);
            break;
    }

    return true;
}

function is_numeric1($n) {
    if (ereg("^[0-9]{1,5},?[0-9]{0,5}$", $n) && ereg("\,", $n)) {
        return(1);
    } else {
        return(0);
    }
}

function fsize($file) {
    $a = array("B", "KB", "MB", "GB", "TB", "PB");
    $pos = 0;
    $size = filesize($file);
    while ($size >= 1024) {
        $size /= 1024;
        $pos++;
    }
    return round($size, 2) . " " . $a[$pos];
}

function size_format($n) {
    $a = array("B", "KB", "MB", "GB", "TB", "PB");
    $pos = 0;

    while ($n >= 1024) {
        $n /= 1024;
        $pos++;
    }
    return round($n, 2) . " " . $a[$pos];
}

function redirect($url) {
    // Behave as per HTTP/1.1 spec for others
    header('Location: ' . $url);
    exit;
}

function hesh_pass($pass, $sol1, $sol2) {
    return md5($sol1 . $pass . $sol2);
}

?>