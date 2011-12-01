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
include('lib/file_helper.php');

function dirmi($d){
	return is_dir($d['server_path']);
}

function filemi($d){
	return is_file($d['server_path']);
}

if (isset($_POST["klasor"]) && $_POST["klasor"] != "") $klasor = $_POST["klasor"]; else $klasor = '';
if (isset($_POST["altklasor"]) && $_POST["altklasor"] != "") $altklasor = $_POST["altklasor"]; else $altklasor = '';
if (isset($_POST["filtre"]) && $_POST["filtre"] != "") $filtre = $_POST["filtre"]; else $filtre = '';


$tumu = get_dir_file_info($klasor . $altklasor, $filtre);
$klasordizi = array_filter($tumu, 'dirmi');
asort($klasordizi);
$dosyadizi = array_filter($tumu, 'filemi');
unset($tumu);
echo '{"k":' . json_encode($klasordizi) . ',"d":' . json_encode($dosyadizi) . '}';
//$tumu = array_merge($klasordizi, $dosyalar);
//echo json_encode($tumu);

?>
