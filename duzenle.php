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
include('lib/file_helper.php');

$isim = $_POST['isim'];
$ici = $_POST['ici'];
$klasor = $_POST['klasor'];
if(get_magic_quotes_gpc()) $ici = stripslashes($ici);
$yold = explode('/', $isim);
$isim = array_pop($yold);
$isim = clean_file_name($isim);
$yol = $klasor . $isim;
$sonuc = write_file($yol, $ici, 'w');
if($sonuc) echo ('File saved as ' . $yol);
else echo _('File can not be saved!');
?>
