<?php
/*
*
* Tayfun Duran	tayfunduran@gmail.com
* Bandit File and Image Manager
* October 2011
* 
*/

include('config.php');
include ('lib/clean_file_name.php');

$k = $_POST['klasor'];
$isim = clean_file_name($_POST['isim']);
if(@mkdir($k . $isim)){
	@chmod($k . $isim, 0777);
	echo _("Folder was created") . " : " . $isim;
} else echo _("Folder could not be created!");
?>
