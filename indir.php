<?php
/*
*
* Tayfun Duran	tayfunduran@gmail.com
* Bandit File and Image Manager
* October 2011
* 
*/

include('config.php');
include('lib/mimes.php');
include('lib/download_helper.php');

$yol = $_GET['yol'];
$veri = file_get_contents($yol);
$isim = basename($yol);
force_download($isim, $veri);
?>
