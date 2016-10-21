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
$files = [];
$dirIterator = new \RecursiveDirectoryIterator($temp_dir);
$fileIterator = new \RecursiveIteratorIterator($dirIterator);
foreach($fileIterator as $file) {
    /** @var $file \SplFileInfo */
    $fileName = trim($file->getFilename());
    if(empty($fileName) || '.' == $fileName{0})
        continue;

    if ('svg' != strtolower($file->getExtension()))
        continue;

//    if($options['--template'] == 'office') // офисные собираем по директориям с именами 16, 30, 40..
//    {
//        $dirName = array_pop(explode('/', dirname($file)));
//        $files[$fileName][$dirName] = $file;
//    }
//    else
//        $files[(string)$file] = $file;
//    echo $file->getPath(), ' | ', $file->getFilename(), PHP_EOL; //->getFilename()
}

//объявляем переменные, присваиваем им данные из формы

echo '<hr>';
$templateType = $_POST['template_type'];             // тип шаблона
$PromoFiles = array($_POST['promo_files_1'], $_POST['promo_files_2'], $_POST['promo_files_3']); //массив с адресами прикрепляемых промо-файлов
print_r($PromoFiles);
$CollectionName = $_POST['Collection_name'];        // имя коллекции
$LinkToCcollection = $_POST['Link_to_collection'];  // гиперссылка на коллекцию
$LinkToLicense = $_POST['Link_to_License'];         // гиперссылка на лицензию
$LicenseText = $_POST['License_text'];              // текст лицензии
$ZipComment = $_POST['zip_comment'];                // текст комментария


//4. сгенерировать preview.html
/*
 * название (тему) фриби
 * гиперссылка на icons8.com
 * гиперссылка на коллекцию, фичу или статью блога
 * гиперссылки на Twitter, Facebook, Google+
 * информация о лицензии
 * все иконки в HTML, на каждой иконке должен быть title с названием иконки
 *
 * скопировать файл шаблона в отдельную директорию +
 * заменить нужные места переменными, сохранить
 * добавить этот файл в архив
 */
$templateFile = 'templates/grid.phtml';
$name = basename($templateFile);
$newFile = "archives/new_temp_dir/$name";
//echo $templateFile.'<br>';
//echo $newFile.'<br>';
if (copy($templateFile, $newFile)) {
    echo "Файл $templateFile скопирован в $newFile\n";
} else {
    echo "Не удалось скопировать $templateFile в $newFile\n";
};

/*
 * как загнать все переменные из формы в html и отобразить его в виде чистого html
 */
$html_code = file_get_contents($newFile); //получить html-код шаблона
$zip->addFromString('template.html', $html_code); //добавить в архив файл шаблона с содержимым в переменной $html_code
// открываем файл, если файл не существует,
//делается попытка создать его
$fp = fopen("file.txt", "w");
// записываем в файл текст
fwrite($fp, $html_code);
// закрываем
fclose($fp);

//if ($zip->addFile($newFile, $name)) { //добавить файл grid.phtml в архив
//    echo "<br>Файл $name добавлен в архив";
//} else {
//    echo "<br>Не удалось добавить $name в архив";
//};

//5. Добавить в архив файл лицензии.
$zip->addFile('путь к файлу лицензии', 'имя файла лицензии');
//6. Добавить в архив коментарий.
$zip->setArchiveComment($ZipComment);
//7. сохранить готовый ZIP в другой директории
//8. дать пользователю ссылку на этот ZIP