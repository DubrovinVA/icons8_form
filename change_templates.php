<?php
//print_r($GLOBALS);

$file='templates/grid.phtml'; // Заменить grid на $template_name - имя шаблона
$temp=isset($_POST['temp'])?$_POST['temp']:'';
$edit=isset($_POST['edit'])?$_POST['edit']:'';
if ($edit=='Edit')
{
    file_put_contents($file,$temp);
}
$temp=file_get_contents($file);
echo "<form method='post'>";
echo "<textarea name='temp' cols='100%' rows='35'>".htmlspecialchars($temp)."</textarea><br>";
echo "<input type='submit' name='edit' value='save the changes to the template'>";
echo "</form>";
