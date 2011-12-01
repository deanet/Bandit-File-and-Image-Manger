<?php 

/*
* Tayfun Duran tayfunduran@gmail.com 
* Image Class
* November 2010
* 
* 
* 	source		: 
* 	width		: Width of the final image (int)
* 	height		: Height of the final image (int)
* 	ratio		: Whether the ratio is aspected (boolean)
* 	exact		: Whether the given sizes aplied exactly (boolean)
* 	stretch		: Whether the image is stretched to given sizes (even if smaller then the sizes) (boolean)
* 	direct		: Whether the image uploaded like a file (esp. animated gif) (boolean)
* 	name		: The new name of the file (string)
* 	overwrite	: If same named file is exist, whether it is deleted (boolean)
* 	type		: Type of the upload image (string)
* 	jpgQuality	: If image file is jpg, quality of the image (int)
* 	r			: Red value of the background color (int)
* 	g			: Green value of the background color (int)
* 	b			: Blue value of the background color (int)
* 	done		: Return value of the process (boolean)
* 	savedName	: Return full path name of the uploaded file, if upload precess is succeded (string)
* 
* 
*/


class imageClass{

	public $source 		= "";
	public $width 		= 0;
	public $height 		= 0;
	public $ratio 		= true;
	public $exact 		= true;
	public $strech 		= false;
	public $direct 		= false;
	public $name 		= "";
	public $overwrite 	= false;
	public $type 		= "";
	public $jpgQuality 	= 85;
	public $r 			= 255;
	public $g 			= 255;
	public $b 			= 255;
	public $done 		= 0;
	public $info 		= "";
	public $savedName 	= "";

	function __construct(){

	}

	function __destruct(){

	}


	public function run($klasor = "./"){
		if(file_exists($this->source)) {
			$gecici = $this->source;
			$isim_dizi = explode("/", $gecici);
			$isim = end($isim_dizi);
			$dizi = explode(".", $isim);
			$sayi = count($dizi);
			$hamtip = strtolower($dizi[$sayi-1]);
			if($this->name == "")
				$onisim = substr($isim, 0, strrpos($isim, "."));
			else
				$onisim = $this->name;
			
			if($this->type != "") $tip = $this->type; else $tip = $hamtip;
			
			$dosya = $klasor . $onisim . "." . $tip;
			if(!$this->overwrite){
				$ii = 1;
				while(file_exists($dosya)){
					$dosya = $klasor . $onisim . "-" . $ii . "." . $tip;
					$ii++;
				}
			}
			
			switch($hamtip){
				case "png":
					$im = @imagecreatefrompng($gecici);
					break;
				case "gif":
					$im = @imagecreatefromgif($gecici);
					break;
				default:
					$im = @imagecreatefromjpeg($gecici);
					break;
			}
			
			$olcu = getimagesize($gecici);
			if($this->width != 0 || $this->height != 0){
				if($this->ratio){
					if($this->width != 0 && $this->height ==0){
						$yeniGenislik = $this->width;
						$oran = $olcu[0] / $this->width;
						if(!$this->strech && $oran < 1){
							$oran = 1;
							$yeniGenislik = $olcu[0];
						}
						$yeniYukseklik = $olcu[1] / $oran;
						$x = 0;
						$y = 0;
						$resimGenislik = $yeniGenislik;
						$resimYukseklik = $yeniYukseklik;
					}else if($this->width == 0 && $this->height !=0){
						$yeniYukseklik = $this->height;
						$oran = $olcu[1] / $this->height;
						if(!$this->strech && $oran < 1){
							$oran = 1;
							$yeniYukseklik = $olcu[1];
						}
						$yeniGenislik = $olcu[0] / $oran;
						$x = 0;
						$y = 0;
						$resimGenislik = $yeniGenislik;
						$resimYukseklik = $yeniYukseklik;
					}else{
						if($olcu[0] / $this->width > $olcu[1] / $this->height)
							$oran = $olcu[0] / $this->width;
						else $oran = $olcu[1] / $this->height;
						if(!$this->strech && $oran < 1) $oran = 1;
						$resimGenislik = $olcu[0] / $oran;
						$resimYukseklik = $olcu[1] / $oran;
						if($this->exact){
							$x = round(($this->width - $resimGenislik) / 2);
							$y = round(($this->height - $resimYukseklik) / 2);
							$yeniGenislik = $this->width;
							$yeniYukseklik = $this->height;
						}else{
							$x = 0;
							$y = 0;
							$yeniGenislik = $resimGenislik;
							$yeniYukseklik = $resimYukseklik;
						}
					}
				}else{
					if($this->width == 0) $yeniGenislik = $olcu[0]; else $yeniGenislik = $this->width;
					if($this->height == 0) $yeniYukseklik = $olcu[1]; else $yeniYukseklik = $this->height;
					$x = 0;
					$y = 0;
					$resimGenislik = $yeniGenislik;
					$resimYukseklik = $yeniYukseklik;
				}
			}else{
				$yeniGenislik = $olcu[0];
				$yeniYukseklik = $olcu[1];
				$x = 0;
				$y = 0;
				$resimGenislik = $yeniGenislik;
				$resimYukseklik = $yeniYukseklik;
			}

			$yenisi = imagecreatetruecolor($yeniGenislik, $yeniYukseklik);
			$renk = imagecolorallocate($yenisi, $this->r, $this->g, $this->b);
			imagefill($yenisi, 0, 0, $renk);
			imagecopyResampled($yenisi, $im, $x, $y, 0, 0, $resimGenislik, $resimYukseklik, $olcu[0], $olcu[1]);
			imagedestroy($im);
			$yarat = touch($dosya);
			if($yarat){
				switch($tip){
					case "png":
						$yaz = imagepng($yenisi, $dosya, 9);
						break;
					case "gif":
						$yaz = imagegif($yenisi, $dosya);
						break;
					default:
						$yaz = imagejpeg($yenisi, $dosya, $this->jpgQuality);
						break;
				}
				@chmod($dosya, 0666);
				imagedestroy($yenisi);
				if($yaz){
					$this->done = 1;
					$this->info = _("Image saved : ") . basename($dosya);
					$this->savedName = $dosya;
				}else{
					$this->done = 0;
					$this->info = _("Image file cannot be writed!");
				}
			}else{
				$this->done = 0;
				$this->info = _("Image cannot be created!");
			}
		}else{
			$this->done = 0;
			$this->info = _("No file found!");
		}
		return true;
	}

}
?>
