<?php
/*
*
* Tayfun Duran	tayfunduran@gmail.com
* Bandit File and Image Manager
* September 2011
* 
*/
	function clean_file_name($filename){
		$bad = array(
			"<!--",
			"-->",
			"'",
			"<",
			">",
			'"',
			'&',
			'$',
			'=',
			';',
			'?',
			'/',
			"%20",
			"%22",
			"%3c",		// <
			"%253c",	// <
			"%3e",		// >
			"%0e",		// >
			"%28",		// (
			"%29",		// )
			"%2528",	// (
			"%26",		// &
			"%24",		// $
			"%3f",		// ?
			"%3b",		// ;
			"%3d"		// =
		);

		$filename = str_replace($bad, '', $filename);

//	Tayfun ekleme {
		$specialLetters = array(
			'a' => array('á','à','â','ä','ã'),
			'A' => array('Ã','Ä','Â','À','Á'),
			'e' => array('é','è','ê','ë'),
			'E' => array('Ë','É','È','Ê'),
			'i' => array('í','ì','î','ï','ı'),
			'I' => array('Î','Í','Ì','İ','Ï'),
			'o' => array('ó','ò','ô','ö','õ'),
			'O' => array('Õ','Ö','Ô','Ò','Ó'),
			'u' => array('ú','ù','û','ü'),
			'U' => array('Ú','Û','Ù','Ü'),
			'c' => array('ç'),
			'C' => array('Ç'),
			's' => array('ş'),
			'S' => array('Ş'),
			'n' => array('ñ'),
			'N' => array('Ñ'),
			'y' => array('ÿ'),
			'Y' => array('Ÿ'),
			'G' => array('Ğ'),
			'g' => array('ğ')
		);

		foreach($specialLetters as $letter => $specials){
			foreach($specials as $s){
				$filename = str_replace($s, $letter, $filename);
			}
		}

		$fd = explode('.', $filename);
		$uzanti = strtolower(array_pop($fd));
		array_push($fd, $uzanti);
		$filename = implode('.', $fd);
//	} Tayfun

		return preg_replace("/[^a-zA-Z0-9\-\.]/", "_", stripslashes($filename));
	}

?>
