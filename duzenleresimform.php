<?php
/*
*
* Tayfun Duran	tayfunduran@gmail.com
* Bandit File and Image Manager
* October 2011
* 
*/

$temp = gecici($klasor . $isim);

$boyut = explode('×', trim($_GET['b']));
if(count($boyut) < 2){
	$boyut[0] = '';
	$boyut[1] = '';
	$oran = 1;
}else $oran = $boyut[0] / $boyut[1];
?>

<script language="javascript" type="text/javascript">
	var iw = <?=$boyut[0]?>, ih = <?=$boyut[1]?>, kw = <?=$boyut[0]?>, kh = <?=$boyut[1]?>, ko = <?=$oran?>, bancro;
	$(document).ready(function(){
		$('#fancy').val('duz');

		$('#dufo').height($(window).height() - 100);
		$('#dufo').width($(window).width() - 100);
		$('#cnvs').height($(window).height() - 170);
		$('#cnvs').width($(window).width() - 110);

		$('#duzkapat').click(function(){
			$('#bandit').jqKill();
			$('#bandit').imgAreaSelect({
				disable: true,
				hide: true
			});
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

		$('#saveimage').click(function(){
			$("#duzenleform").submit();
		});

		$('#res').click(function(){
			$('#bandit').jqResize(null, true);
			$('#bandit').addClass('isaretle');
			$('#olcekle').show();
		});

		$('#ratio').change(function(){
			$('#bandit').jqKill();
			if($(this).is(':checked')) $('#bandit').jqResize(null, true); else $('#bandit').jqResize(null, false);
		});

		$('#bwi').blur(function(){
			boyhesap($(this).val(), 'w');
		}).keyup(function(e){
			if(e.keyCode == '13') {
				e.preventDefault();
				boyhesap($(this).val(), 'w');
			}
		});

		$('#bhe').blur(function(){
			boyhesap($(this).val(), 'h');
		}).keyup(function(e){
			if(e.keyCode == '13') {
				e.preventDefault();
				boyhesap($(this).val(), 'h');
			}
		});

		$('#resizecancel').click(function(){
			$('#bandit').jqKill();
			$('#bandit').width(iw).height(ih).removeClass('isaretle');
			$('#olcekle').hide();
			$('#bwi').val(kw);
			$('#bhe').val(kh);
			$('#ratio').attr('checked', true);
		});

		$('#resizedone').click(function(){
			iw = $('#bwi').val();
			ih = $('#bhe').val();
			resimisle('re', iw, ih);
			$('#bandit').jqKill();
			$('#bandit').removeClass('isaretle');
			$('#olcekle').hide();
		});

		$('#cro').click(function(){
			$('#kesme').show();
			bancro = $('#bandit').imgAreaSelect({
				instance: true,
				handles: true,
				onSelectChange: function (img, selection) {
					$('#bcx1').val(selection.x1);
					$('#bcx2').val(selection.x2);
					$('#bcy1').val(selection.y1);
					$('#bcy2').val(selection.y2);
					$('#bcw').val(selection.width);
					$('#bch').val(selection.height);
				}
			});
		});

		$('#cratio').change(function(){
			if($(this).is(':checked')){
				ra = $('#bcw').val() + ':' + $('#bch').val();
				if(ra == ':') ra = '1:1';
				bancro.setOptions({ aspectRatio: ra });
			}else bancro.setOptions({ aspectRatio: null });
		});

/*
		$('#bcw').blur(function(){
			x2 = $('#bcx1').val() + $(this).val();
			cropguncelle($('#bcx1').val(), $('#bcy1').val(), x2, $('#bcy2').val());
		}).keyup(function(e){
			if(e.keyCode == '13') {
				e.preventDefault();
				x2 = $('#bcx1').val() + $(this).val()
				cropguncelle($('#bcx1').val(), $('#bcy1').val(), x2, $('#bcy2').val());
			}
		});

		$('#bch').blur(function(){
			y2 = $('#bcy1').val() + $(this).val();
			cropguncelle($('#bcx1').val(), $('#bcy1').val(), $('#bcx2').val(), y2);
		}).keyup(function(e){
			if(e.keyCode == '13') {
				e.preventDefault();
				y2 = $('#bcy1').val() + $(this).val();
				cropguncelle($('#bcx1').val(), $('#bcy1').val(), $('#bcx2').val(), y2);
			}
		});
*/

		$('#cropcancel').click(function(){
			$('#bandit').imgAreaSelect({
				disable: true,
				hide: true,
				parent: '#cnvs'
			});
			$('#kesme').hide();
			$('#bcx1').val('');
			$('#bcx2').val('');
			$('#bcy1').val('');
			$('#bcy2').val('');
			$('#bcw').val('');
			$('#bch').val('');
			$('#cratio').attr('checked', false);
		});

		$('#cropdone').click(function(){
			resimisle('cr', $('#bcw').val(), $('#bch').val(), $('#bcx1').val(), $('#bcy1').val());
			$('#bandit').imgAreaSelect({
				disable: true,
				hide: true
			});
			resimboyutguncelle($('#bcw').val(), $('#bch').val());
			$('#kesme').hide();
			$('#bcx1').val('');
			$('#bcx2').val('');
			$('#bcy1').val('');
			$('#bcy2').val('');
			$('#bcw').val('');
			$('#bch').val('');
			$('#cratio').attr('checked', false);
		});

		$('#ror').click(function(){
			resimisle('rr');
			resimboyutguncelle($('#bhe').val(), $('#bwi').val());
		});

		$('#rol').click(function(){
			resimisle('rl');
			resimboyutguncelle($('#bhe').val(), $('#bwi').val());
		});

		$('#flh').click(function(){
			resimisle('fh');
		});

		$('#flv').click(function(){
			resimisle('fv');
		});

		$('#cnvs').scroll(function(){
			if(bancro) bancro.update();
		});
	});

	function cropguncelle(x1, y1, x2, y2){
		bancro.setSelection(x1, y1, x2, y2);
		bancro.update();
	}

	function resimboyutguncelle(w, h){
		iw = w;
		ih = h;
		$('#bwi').val(w);
		$('#bhe').val(h);
		$('#bandit').width(w).height(h);
	}

	function boyhesap(d, n){
		if(n == 'w'){
			if($('#ratio').is(':checked')){
				h = parseInt( d / ko );
				$('#bhe').val(h);
			}
		}else{
			if($('#ratio').is(':checked')){
				w = parseInt( ko * d );
				$('#bwi').val(w);
			}
		}
		guncelle();
	}

	function guncelle(){
		$('#bandit').width($('#bwi').val()).height($('#bhe').val());
		kayit();
	}

	function kayit(){
		kw = $('#bandit').width();
		kh = $('#bandit').height();
		ko = kw / kh;
	}

	function crodone(){
		
	}

	function resimisle(islem, w, h, x, y){
		bilgi('<img src="resimler/bekle.gif" /> <?echo _('Processing, please wait...');?>', 15000);
		$.ajax({
			type	: "POST",
			url		: "resimisle.php",
			data	: "dosya=<?=$temp?>&islem=" + islem + "&w=" + w + "&h=" + h + "&x=" + x + "&y=" + y,
			dataType: "text",
			error	: function(nesne, sebep, hata){
				bilgi('<?echo _('Error!');?>');
			},
			success	: function(sonuc){
				d = new Date()
				$('#bandit').attr('src', '<?=$temp?>?t=' + d.getTime());
				bilgi(sonuc);
			}
		});
	}

</script>

<div id="dufo">
	<h1><?echo _('Edit Image : ') . $klasor . $isim;?>
		<img id="duzkapat" src="resimler/cancel.png" width="16" height="16" alt="<?echo _('Close')?>" title="<?echo _('Close')?>" class="pointer float-right" />
	</h1>
	<form action="duzenleresim.php" method="post" id="duzenleform">
		<input type="hidden" name="yol" value="<?=$klasor . $isim?>" />
		<input type="hidden" name="klasor" value="<?=$klasor?>" />
		<input type="hidden" name="temp" value="<?=$temp?>" />
		<p>
			<span id="olcekle" class="islempanel">
				<?echo _('Width');?> : <input type="text" class="f1 align-right" name="bwi" id="bwi" style="width: 40px;" value="<?=$boyut[0]?>" />
				&nbsp; &nbsp;
				<?echo _('Height');?> : <input type="text" class="f1 align-right" name="bhe" id="bhe" style="width: 40px;" value="<?=$boyut[1]?>" />
				&nbsp; &nbsp;
				<input type="checkbox" id="ratio" checked="checked" /><label for="ratio"><?echo _('Keep ratio');?></label> 
				&nbsp; &nbsp;
				<input type="button" class="f2" value="<?php echo _('Done');?>" id="resizedone" />
				<input type="button" class="f2" value="<?php echo _('Cancel');?>" id="resizecancel" />
			</span>
			<span id="kesme" class="islempanel">
				<input type="hidden" name="bcx2" id="bcx2" />
				<input type="hidden" name="bcy2" id="bcy2" />
				X: <input type="text" name="bcx1" id="bcx1" style="width: 25px;" value="" />
				&nbsp; 
				Y: <input type="text" name="bcy1" id="bcy1" style="width: 25px;" value="" />
				&nbsp; 
				<?echo _('Width');?>: <input type="text" name="bcw" id="bcw" style="width: 30px;" value="" />
				&nbsp; 
				<?echo _('Height');?>: <input type="text" name="bch" id="bch" style="width: 30px;" value="" />
				&nbsp; 
				<input type="checkbox" id="cratio" /><label for="cratio"><?echo _('Keep ratio');?></label> 
				&nbsp;
				<input type="button" class="f2" value="<?php echo _('Done');?>" id="cropdone" />
				<input type="button" class="f2" value="<?php echo _('Cancel');?>" id="cropcancel" />
			</span>
			<span class="bold underline"><?echo _('File Name');?>:</span> <input type="text" value="<?=$isim?>" class="f1" name="isim" style="width: 350px;" />
			<img src="resimler/disk.png" width="16" height="16" title="<?echo _('Save');?>" id="saveimage" border="0" class="pointer" />
			<span class="float-right">
				<img id="res" src="resimler/arrow_inout.png" width="16" height="16" title="<?echo _('Resize');?>" border="0" class="pointer" />
				<img id="cro" src="resimler/ruler_crop.png" width="16" height="16" title="<?echo _('Crop');?>" border="0" class="pointer" />
				<img id="ror" src="resimler/arrow_redo.png" width="16" height="16" title="<?echo _('Rotate Right');?>" border="0" class="pointer" />
				<img id="rol" src="resimler/arrow_undo.png" width="16" height="16" title="<?echo _('Rotate Left');?>" border="0" class="pointer" />
				<img id="flh" src="resimler/shape_flip_horizontal.png" width="16" height="16" title="<?echo _('Flip Horizontal');?>" border="0" class="pointer" />
				<img id="flv" src="resimler/shape_flip_vertical.png" width="16" height="16" title="<?echo _('Flip Vertical');?>" border="0" class="pointer" />
			</span>
		</p>
		<div style="overflow: auto; background: #eee;" class="f1" id="cnvs">
			<img src="<?=$temp?>?t=<?=time()?>" id="bandit" />
		</div>
	</form>
</div>

