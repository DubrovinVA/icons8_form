<?php
//1. нужно распаковать ZIP, загруженный пользователем (но не удалять ZIP)
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

//2. найти SVG файлы среди всего, что распаковалось
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
    if (($type->getExtension()) == "svg") {
        ;
    };
};
//передать в preview.php данные про .svg

//3. сгенерировать preview.html
//4. засунуть preview.html в тот же самый архив
//5. сохранить готовый ZIP в временной директории
//6. дать пользователю ссылку на этот ZIP