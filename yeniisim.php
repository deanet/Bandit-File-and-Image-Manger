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
$eskiisim = $_POST['eskiisim'];
$yeniisim = clean_file_name($_POST['yeniisim']);
if(@rename($k . $eskiisim, $k . $yeniisim)){
	echo _("The name changed : ") . $eskiisim . " => " . $yeniisim;
} else echo _("The name could not be changed!");
?>
