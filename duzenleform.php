<?php
/*
*
* Tayfun Duran	tayfunduran@gmail.com
* Bandit File and Image Manager
* October 2011
* 
*/

include('config.php');

$yol = explode('/', $_GET['yol']);
$isim = array_pop($yol);
$klasor = implode('/', $yol) . '/';
$uza = strtolower(substr(strrchr($isim, '.'), 1));
if(in_array($uza, $config['editabletexts'])){

	$veri = file_get_contents($klasor . $isim);
	?>
	<script language="javascript" type="text/javascript">
		$(document).ready(function(){
			$('#duzkapat').click(function(){
				$.fancybox.close();
			});

			$("#duzenleform").ajaxForm({
				dataType: "text",
				error: function(nesne, sebep, hata){
					bilgi('Hata!');
				},
				success: function(sonuc){
					listecek();
					bilgi(sonuc);
				}
			});
		});
	</script>

	<div>
		<h1><?echo _('Edit File : ') . $klasor . $isim;?>
			<img id="duzkapat" src="resimler/cancel.png" width="16" height="16" alt="<?echo _('Close')?>" title="<?echo _('Close')?>" class="pointer float-right" />
		</h1>
		<form action="duzenle.php" method="post" id="duzenleform">
			<input type="hidden" name="klasor" value="<?=$klasor?>" />
			<p>
				<span class="bold underline"><?echo _('File Name');?>:</span> <input type="text" value="<?=$isim?>" class="f1" name="isim" style="width: 400px;" />
				<input type="submit" value="<?echo _('Save');?>" class="f2" />
			</p>
			<textarea class="f1" name="ici" style="width: 600px; height: 320px;"><?=$veri?></textarea>
		</form>
	</div>
	<?
}else if(in_array($uza, $config['editableimages'])){
	if(GD) include('duzenleresimform.php');
	else '<p class="align-center">' . _('GD library is not loaded!') . '<br /><img src="resimler/cancel.png" width="16" height="16" alt="' . _('Close') . '" title="' . _('Close') . '" class="pointer" onclick="$.fancybox.close()" /></p>';
}
else echo '<p class="align-center">' . _('No edit!') . '<br /><img src="resimler/cancel.png" width="16" height="16" alt="' . _('Close') . '" title="' . _('Close') . '" class="pointer" onclick="$.fancybox.close()" /></p>';
?>
