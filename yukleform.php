<?php
/*
*
* Tayfun Duran	tayfunduran@gmail.com
* Bandit File and Image Manager
* October 2011
* 
*/
include('config.php');
?>
<script language="javascript" type="text/javascript">
	$(document).ready(function(){
		$('#dosyayukle').MultiFile({
			list: '#cokluyukle',
			STRING: {
				remove: '<img src="resimler/bin_closed.png" height="16" width="16" alt="x"/>'
			}
		});

		$("#yukleform").ajaxForm({
			dataType: "text",
			error: function(nesne, sebep, hata){
				bilgi('Hata!');
			},
			success: function(sonuc){
				$('#dosyayukle').MultiFile('reset')
				listecek();
				bilgi(sonuc);
			}
		});

		$('#yukleklasor').val(klasor + altklasor);
	});
</script>
<div style="width: 450px; padding: 5px;">
	<h1><?php echo _('File Upload');?></h1>
	<p>
		<?php echo _('Multiple files can be uploaded by selecting below. Maximum file size for a single file will be uploaded is ') . min(ini_get('post_max_size'), ini_get('upload_max_filesize')) . _(' and total maximum size for all files to upload is ') . ini_get('post_max_size') . '.';?>
	</p>
	<form action="yukle.php" method="post" id="yukleform" enctype="multipart/form-data">
		<input type="hidden" name="klasor" id="yukleklasor" value="" />
		<span class="bold underline float-left" style="margin: 4px 10px 0 0;"><?echo _('Add file')?></span>
		<input type="file" class="f1" id="dosyayukle" name="dosya[]" class="multi" maxlength="8" />
		<p id="cokluyukle"></p>
		<input type="submit" class="f2" value="<?echo _('Upload');?>" />
	</form>
</div>
