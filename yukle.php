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

$k = $_POST['klasor'];
$d = $_FILES['dosya'];

$sayi = 0;
if(is_writable($klasor)){
	if(is_array($dosya)){
		foreach ($d['error'] as $i => $error) {
			if ($error == UPLOAD_ERR_OK) {
				$gecici = $d['tmp_name'][$i];
				$isim = clean_file_name($d['name'][$i]);

				$onisim = substr($isim, 0, strrpos($isim, "."));
				$uzanti = strtolower(substr(strrchr($isim, '.'), 1));

				$dosya = $k . $onisim . "." . $uzanti;
				$ii = 1;
				while(file_exists($dosya)){
					$dosya = $k . $onisim . "-" . $ii . "." . $uzanti;
					$ii++;
				}
				$tasi = move_uploaded_file($gecici, $dosya);
				@chmod($dosya, 0666);
				if($tasi) $sayi++;
			}
		}
		echo $sayi . _(' file(s) uploaded.');
	}else echo _('There is no uploaded file!');
}else echo _('The folder is not writable!');

?>
