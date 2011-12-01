<?php
/*
*
* Tayfun Duran	tayfunduran@gmail.com
* Bandit File and Image Manager
* October 2011
* 
*/

include('../config.php');


$thumb = strip_tags(htmlspecialchars(str_replace('@@@@@', '/', $_GET['pul'])));

if($thumb == '') hata('No resource.');

$thumb_array 	= explode('/', $thumb);
$size 			= array_shift($thumb_array);
$image 			= '../' . implode('/', $thumb_array);
list($width, $height) = explode('x', $size);

$thumb = str_replace("/", "_", $thumb);
$thumb = str_replace(" ", "_", $thumb);
if(!file_exists("./" . $thumb)){

	if (!in_array($size, $sizes)) hata('Invalid size.');
	if (!file_exists($image)) hata('No file.');

	$isim_dizi = explode('.', $thumb);
	$uzanti = strtolower(array_pop($isim_dizi));
	$isim = implode('.', $isim_dizi);

	if(GD && ($uzanti == "png" || $uzanti == "gif" || $uzanti == "jpg" || $uzanti == "jpeg")){
		require('image.class.php');
		$pul = new imageClass();
		$pul->source	= $image;
		$pul->width		= $width;
		$pul->height	= $height;
		$pul->name		= $isim;
		$pul->r			= 255;
		$pul->g			= 255;
		$pul->b			= 255;
		$pul->run("./");
	}else{
		header('Location: ../resimler/dosya.jpg');
		exit();
	}
}
header('Location: ' . dirname($_SERVER['SCRIPT_NAME']) . '/' . $thumb . '?new');


function hata($hata) {
	header("Content-type: text/html; charset=UTF-8");
	header("HTTP/1.0 404 Not Found");
	echo '<h1>' . _('Not found') . '</h1>';
	echo '<p>' . _('Requested file is not found.') . '</p>';
	echo '<p>' . _('Error') . ": <b>$hata</b></p>";
	exit();
}

?>
