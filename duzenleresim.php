<?php
/*
*
* Tayfun Duran	tayfunduran@gmail.com
* Bandit File and Image Manager
* October 2011
* 
*/

include('config.php');
include('lib/clean_file_name.php');

$isim = $_POST['isim'];
$klasor = $_POST['klasor'];
$yol = $_POST['yol'];
$temp = $_POST['temp'];

$isimd = explode('/', $isim);
$isim = array_pop($isimd);

$yeniyol = $klasor . $isim;
$sonuc = rename($temp, $yeniyol);

if($sonuc){
	foreach($sizes as $s){
		$gd = "cache/$s" . "_" . str_replace(" ", "_", str_replace("/", "_", $klasor . $isim));
		if(file_exists($gd)) unlink($gd);
	}
	echo ('File saved as ' . $yeniyol);
}
else echo _('File can not be saved!');
?>
