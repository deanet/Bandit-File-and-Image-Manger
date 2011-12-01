<?php
/*
*
* Tayfun Duran	tayfunduran@gmail.com
* Bandit File and Image Manager
* October 2011
* 
*/

session_start();
error_reporting(E_ALL);
//set_time_limit(0);


$config = array(
	// Folders
	'folders'			=> array('Resim' => '../resim/', 'Dene' => '../dene/'),

	// Number of file thumbnails loaded in the thumbnail mode
	'per_page'			=> 10,

	// javascript code and function to insert the selected file
	'costum_function' 	=> '
function insertfile(path, width, height){
	 alert(path + ": " + width + "×" + height);
}
',

	// Authentication: i: Integrated , c: Custom , n: None
	'auth_type'			=> 'n',

	// Integrated authentication password. If authentication type is i
	'auth_pass'			=> 'bandit',

	// Custom authentication criteria and custom login url. If authentication type is c
	'auth_cri'			=> '',			// eg : $_SESSION['auth'],
	'auth_url'			=> '',			// eg : http://example.com/login.php

	// Editable text file extensions
	'editabletexts'		=> array('txt', 'css', 'html', 'json', 'js', 'php', 'text', 'shtml', 'htm', 'xml', 'xls'),

	// Editable image file extensions
	'editableimages' 	=> array('jpg', 'jpeg', 'jpe', 'png', 'gif'),
);



/* ################################################################# */

if($config['auth_type'] == 'c'){
	if(!$config['auth_cri']){
		if(basename($_SERVER['PHP_SELF']) == 'index.php') header('Location: ' . $config['auth_url']); else exit;
	}
}else if($config['auth_type'] == 'i'){
	if(isset($_GET['cik'])){
		unset($_SESSION['banditauth']);
		header('Location: ./');
	}
	else{
		if(!(isset($_SESSION['banditauth']) && $_SESSION['banditauth'] == true)){
			if(basename($_SERVER['PHP_SELF']) == 'index.php'){
				if(isset($_POST['sifre']) && $_POST['sifre'] == $config['auth_pass']){
					$_SESSION['banditauth'] = true;
					header('Location: ./');
				}
				include('giris.php');
			}
			exit;
		}
	}
}


$sizes = array(
	'90x90',
	'192x0'
);


$gd = true;
if (!extension_loaded('gd')) {
	if (!dl('gd.so')) {
		$gd = false;
	}
}
define('GD', $gd);

function gecici($d){
	$t = './temp/bt-' . mt_rand() . '.' . strtolower(substr(strrchr($d, '.'), 1));
	if(copy($d, $t)){
		@chmod($t, 0666);
		return $t;
	}else return false;
}
?>
