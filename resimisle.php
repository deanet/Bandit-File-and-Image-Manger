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
include('lib/Image_lib.php');


$islem = $_POST['islem'];
$temp = $_POST['dosya'];

$resim = new CI_Image();

$ayar['source_image'] = $temp;
switch($islem){
	case 're':
		$w = $_POST['w'];
		$h = $_POST['h'];
		$ayar['width'] 			= $w;
		$ayar['height'] 		= $h;
		$ayar['maintain_ratio'] = false;
		$resim->initialize($ayar);
		$s = $resim->resize();
		$resim->clear();
		if($s) echo _('Image resized.'); else echo _('There is a problem!');
		break;
	case 'cr':
		$w = $_POST['w'];
		$h = $_POST['h'];
		$x = $_POST['x'];
		$y = $_POST['y'];
		$ayar['width'] 			= $w;
		$ayar['height'] 		= $h;
		$ayar['x_axis'] 		= $x;
		$ayar['y_axis'] 		= $y;
		$ayar['maintain_ratio'] = false;
		$resim->initialize($ayar);
		$s = $resim->crop();
		$resim->clear();
		if($s) echo _('Image cropped.'); else echo _('There is a problem!');
		break;
	case 'rr':
		$ayar['rotation_angle'] = '270';
		$resim->initialize($ayar);
		$s = $resim->rotate();
		$resim->clear();
		if($s) echo _('Image rotated.'); else echo _('There is a problem!');
		break;
	case 'rl':
		$ayar['rotation_angle'] = '90';
		$resim->initialize($ayar);
		$s = $resim->rotate();
		$resim->clear();
		if($s) echo _('Image rotated.'); else echo _('There is a problem!');
		break;
	case 'fh':
		$ayar['rotation_angle'] = 'hor';
		$resim->initialize($ayar);
		$s = $resim->rotate();
		$resim->clear();
		if($s) echo _('Image flipped.'); else echo _('There is a problem!');
		break;
	case 'fv':
		$ayar['rotation_angle'] = 'vrt';
		$resim->initialize($ayar);
		$s = $resim->rotate();
		$resim->clear();
		if($s) echo _('Image flipped.'); else echo _('There is a problem!');
		break;
}
@chmod($temp, 0666);
?>
