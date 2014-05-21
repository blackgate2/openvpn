<?

include('../protected/class.define.conts.php');
$str = read_all_files_from_dir_and_subdir(commonConsts::path_img_admin);

$str = substr($str, 0, strlen($str) - 2);
echo "var tinyMCEImageList = new Array(
	// Name, URL
	$str

	);";

function read_all_files_from_dir_and_subdir($pathToDir) {
    global $str;
    $handle = opendir($pathToDir);
    $flarray = array();

    while ($file = readdir($handle)) {
        if ($file != "." && $file != ".." && filetype($pathToDir . $file) == 'file') {
            array_push($flarray, $pathToDir . $file);
        } else if (filetype($pathToDir . $file) == 'dir' && $file != "." && $file != "..") {
            read_all_files_from_dir_and_subdir($pathToDir . $file . '/');
        }
    }

    for ($i = 0; $i < count($flarray); $i++) {
        $str.= '["' . substr($flarray[$i], strlen(commonConsts::path_img_admin)) . '", "' . substr($flarray[$i], 2) . '"],' . "\n";
    }

    closedir($handle);

    return $str;
}

?>