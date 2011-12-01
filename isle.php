<?php
/*
*
* Tayfun Duran	tayfunduran@gmail.com
* Bandit File and Image Manager
* October 2011
* 
*/
include('config.php');
include('lib/file_helper.php');
include('lib/mimes.php');
include('lib/download_helper.php');

function addFolderToZip($dir, $zipArchive, $zipdir = ''){
	if(is_dir($dir)) {
		if ($dh = opendir($dir)) {
			if(!empty($zipdir)) $zipArchive->addEmptyDir($zipdir);
			while (($file = readdir($dh)) !== false) {
				if(is_dir($dir . $file)){
					if( ($file !== ".") && ($file !== "..")){
						addFolderToZip($dir . $file . "/", $zipArchive, $zipdir . $file . "/");
					}
				}else{
					$zipArchive->addFile($dir . $file, $zipdir . $file);
				}
			}
		}
	}
}

function klasorkopya($yoldan, $yola){
	if(is_dir($yoldan)){
		@mkdir($yola);
		if($d = opendir($yoldan)) {
			while (($dosya = readdir($d)) !== false) {
				$y1 = $yoldan . '/' . $dosya;
				$y2 = $yola . '/' . $dosya;
				if(is_dir($y1)){
					if($dosya != '.' && $dosya != '..') klasorkopya($y1, $y2);
				}else copy($y1, $y2);
			}
		}
		
	}
}

function klasortasi($yoldan, $yola){
	if(is_dir($yoldan)){
		@mkdir($yola);
		if($d = opendir($yoldan)) {
			while (($dosya = readdir($d)) !== false) {
				$y1 = $yoldan . '/' . $dosya;
				$y2 = $yola . '/' . $dosya;
				if(is_dir($y1)){
					if($dosya != '.' && $dosya != '..') klasortasi($y1, $y2);
				}else rename($y1, $y2);
			}
		}
		
	}
}


if(isset($_POST['liste'])) $liste = $_POST['liste']; else $liste = null;
$islem = $_POST['islem'];
$klasor = $_POST['klasor'];

if(is_array($liste)){
	switch ($islem){
		case 'sil':
			foreach($liste as $l){
				$yol = $klasor . $l;
				if(file_exists($yol)){
					if(is_dir($yol)){
						delete_files($yol, true);
						@rmdir($yol);
					}else unlink($yol);
					foreach($sizes as $s){
						$gd = "cache/$s" . "_" . str_replace(" ", "_", str_replace("/", "_", $yol));
						if(file_exists($gd)) unlink($gd);
					}
				}
			}
			echo _('Selected file(s) deleted.');
			break;
		case 'zip':
			$dosya = tempnam("cache", "zip"); 
			$zip = new ZipArchive;
			$zip->open( $dosya, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE );
			foreach($liste as $l){
				$yol = $klasor . $l;
				if(file_exists($yol)){
					if(is_dir($yol)) addFolderToZip($yol . '/', $zip, $l . '/');
					else $zip->addFile($yol, $l);
				}
			}
			$zip->close();
			$veri = file_get_contents($dosya);
			$isim = 'bandit_' . date('Y-m-d_His') . '.zip';
			unlink($dosya);
			force_download($isim, $veri);
			break;
		case 'cop':
			$klasorden = $_POST['klasorden'];
			foreach($liste as $l){
				$yoldan = $klasorden . $l;
				$yola = $klasor . $l;
				if(is_dir($yoldan)) klasorkopya($yoldan, $yola);
				else copy($yoldan, $yola);
			}
			echo _('File(s) copied.');
			break;
		case 'cut':
			$klasorden = $_POST['klasorden'];
			foreach($liste as $l){
				$yoldan = $klasorden . $l;
				$yola = $klasor . $l;
				if(is_dir($yoldan)) klasortasi($yoldan, $yola);
				else rename($yoldan, $yola);
			}
			echo _('File(s) copied.');
			break;
	}
}else echo _('There is no file selected!');
?>
