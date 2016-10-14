<?php

//глобальный массив с переданныи из формы архивом
$archive_dir = $_FILES['brous_for_file']['tmp_name'];

//папка в которой будут размещены файлы архива после разархивирования
$temp_dir = "d:/OpenServer/domains/icons8/archives/temp_dir/";

//открываем zip архив
$zip = new ZipArchive();

//имя файла архива
$fileName = $_FILES['brous_for_file']['tmp_name'];
if ($zip->open($fileName) !== true) {
    fwrite(STDERR, "Error while openning archive file");
    exit(1);
}

//извлекаем файлы
$zip->extractTo($temp_dir);

//закрываем архив
$zip->close();

//перебираем файлы в $temp_dir
//функция получения списка файлов в $temp_dir и вложенных папках
function scan_Dir($dir) {
    $arrfiles = array();
    if (is_dir($dir)) {
        if ($handle = opendir($dir)) {
            chdir($dir);
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (is_dir($file)) {
                        $arr = scan_Dir($file);
                        foreach ($arr as $value) {
                            $arrfiles[] = $dir."/".$value;
                        }
                    } else {
                        $arrfiles[] = $dir."/".$file;
                    }
                }
            }
            chdir("../");
        }
        closedir($handle);
    }
    return $arrfiles;
}
$files = scan_Dir($temp_dir);
foreach ($files as $file) {
    $type = new SplFileInfo($file);
    if (($type->getExtension()) !== "svg") {
        unlink($file);
//    } else {
//        $name = basename($file).PHP_EOL;
//        $newfile = "D:/OpenServer/domains/icons8/archives/new_temp_dir/$name";
//        if (rename($file, $newfile)) {
//            echo "Файл $ile переименован в $newfile\n";
//        } else {
//            echo "Не удалось переименовать $file в $newfile\n";
//        };
    };
    //echo '<br>';
};
$files1 = scan_Dir($temp_dir);
foreach ($files1 as $file) {
    $name = basename($file).PHP_EOL;
    $newfile = "D:/OpenServer/domains/icons8/archives/new_temp_dir/$name";
    echo $newfile.'<br>';
//    if (copy($file, $newfile)) {
//            echo "Файл $ile переименован в $newfile\n";
//        } else {
//            echo "Не удалось переименовать $file в $newfile\n";
//        };
}
