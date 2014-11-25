<?php

class uploader {

    var $file;
    var $errors;
    var $accepted;
    var $max_filesize;
    var $max_image_width;
    var $max_image_height;
    var $uploaded_file;

    function max_filesize($size) {
        $this->max_filesize = $size;
    }

    function max_image_size($width, $height) {
        $this->max_image_width = $width;
        $this->max_image_height = $height;
    }

    function upload($filename, $accept_type = '', $extention = '') {
        // get all the properties of the file
//		$index = array("file", "tmp_name", "size", "type");
//		for($i = 0; $i < 4; $i++) {
//			$file_var = '$' . $filename . (($index[$i] != "file") ? "_" . $index[$i] : "");
//			eval('global ' . $file_var . ';');
//			eval('$this->file[$index[$i]] = ' . $file_var . ';');
//		}
//                print_r($this->file);
//                exit();
        $this->file = $filename;
        if ($this->file["tmp_name"] && $this->file["tmp_name"] != "none") {
            // test max size
            if ($this->max_filesize && $this->file["size"] > $this->max_filesize) {
                $this->errors[1] = "The maximal size of a file should be no more than  " . $this->max_filesize / 1024 . "KB (" . $this->max_filesize . " bytes).";
                return FALSE;
            }
            if (strstr($this->file["type"], "image")) {

                $image = getimagesize($this->file["tmp_name"]);
                $this->file["width"] = $image[0];
                $this->file["height"] = $image[1];

                // test max image size
                if (($this->max_image_width || $this->max_image_height) && (($this->file["width"] > $this->max_image_width) || ($this->file["height"] > $this->max_image_height))) {
                    $this->errors[2] = "The maximal width and height of a picture should be " . $this->max_image_width . " x " . $this->max_image_height . " px";
                    return FALSE;
                }
                switch ($image[2]) {
                    case 1:
                        $this->file["extention"] = ".gif";
                        break;
                    case 2:
                        $this->file["extention"] = ".jpg";
                        break;
                    case 3:
                        $this->file["extention"] = ".png";
                        break;
                    default:
                        $this->file["extention"] = $extention;
                        break;
                }
            } elseif (!preg_match("/(\\.)([a-z0-9]{3,5})$/", $this->file["name"]) && !$extention) { //preg_match("/\\.(jpg|gif|png|bmp?)$/"
                // add new mime types here
                switch ($this->file["type"]) {
                    case "text/plain":
                        $this->file["extention"] = ".txt";
                        break;
                    default:
                        break;
                }
            } else {
                $this->file["extention"] = $extention;
            }

            // check to see if the file is of type specified
            if ($accept_type) {

                if (strstr($accept_type, trim($this->file["type"], 'image/'))) {
                    $this->accepted = TRUE;
                } else {
                    $this->accepted = FALSE;
                    $this->errors[3] = "Only " . preg_replace("/\\|/", " or ", $accept_type) . " files may be uploaded";
                }
            } else {
                $this->accepted = TRUE;
            }
        } else {
            $this->accepted = FALSE;
            $this->errors[0] = "File not uploaded";
        }
        return $this->accepted;
    }

    function save_file($path, $mode = "3") {
        $this->path = $path;
        if ($this->accepted) {
            // very strict naming of file.. only lowercase letters, numbers and underscores
            $this->file["name"] = $this->trans($this->file["name"]);

            // check for extention and remove - we want to get JUST the
            // filename (without the extenstion) into the variable $name
            if (preg_match("/(\\.)([a-z0-9]{3,5})$/", $this->file["name"])) {
                $pos = strrpos($this->file["name"], ".");
                if (!$this->file["extention"]) {
                    $this->file["extention"] = substr($this->file["name"], $pos, strlen($this->file["name"]));
                }
                $name = substr($this->file["name"], 0, $pos);
            } else {
                $name = $this->file["name"];
                if ($this->file["extention"]) {
                    $this->file["name"] = $this->file["name"] . $this->file["extention"];
                }
            }
            $this->uploaded_file = $this->path . $this->file["name"];

            if (strstr($this->file["type"], "text")) {
                // If it's a text file, we may need to convert MAC and/or PC
                // line breaks to UNIX
                // chr(13)  = CR (carridge return) = Macintosh
                // chr(10)  = LF (line feed)       = Unix
                // Win line break = CRLF
                $new_file = '';
                $old_file = '';
                $fcontents = file($this->file["tmp_name"]);
                while (list ($line_num, $line) = each($fcontents)) {
                    $old_file .= $line;
                    $new_file .= str_replace(chr(13), chr(10), $line);
                }
                if ($old_file != $new_file) {
                    // Open the uploaded file, and re-write it
                    // with the new changes
                    $fp = fopen($this->file["tmp_name"], "w");
                    fwrite($fp, $new_file);
                    fclose($fp);
                }
            }

            switch ($mode) {
                case 1: // overwrite mode
                    $aok = copy($this->file["tmp_name"], $this->uploaded_file);
                    break;
                case 2: // create new with incremental extention
                    while (file_exists($this->path . $name . $copy . $this->file["extention"])) {
                        $copy = "_copy" . $n;
                        $n++;
                    }
                    $this->file["name"] = $name . $copy . $this->file["extention"];
                    $this->uploaded_file = $this->path . $this->file["name"];
//                                        echo $this->file["tmp_name"],' ', $this->uploaded_file;
//                                        exit();
                    $aok = copy($this->file["tmp_name"], $this->uploaded_file);
                    break;
                case 3: // do nothing if exists, highest protection
                    if (file_exists($this->uploaded_file)) {
                        $this->errors[4] = "File &quot" . $this->uploaded_file . "&quot already exists";
                    } else {
                        $aok = copy($this->file["tmp_name"], $this->uploaded_file);
                    }
                    break;
                default:
                    break;
            }

            if (!$aok) {
                unset($this->uploaded_file);
            }
            return $aok;
        } else {
            return FALSE;
        }
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
            'ь' => '', 'Ь' => '', 'Ъ' => '', "'" => '`',
            'ъ' => ''
        );



        $fileName = strtr(strtolower($fileName), $trans);
//  	$fileName = strtr($fileName,$trans);
        // if (eregi ('[^a-z0-9\._\-]',$fileName))  return false;
        return $fileName;
    }

}

?>