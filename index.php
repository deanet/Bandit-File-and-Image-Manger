<?php
/*
*
* Tayfun Duran	tayfunduran@gmail.com
* Bandit File and Image Manager
* October 2011
* 
*/

include('config.php');
include('lib/file_helper.php');


$z = time();
$templer = get_dir_file_info('./temp');
foreach($templer as $t){
	if(is_file($t['server_path']) && $z - $t['time'] > 60 * 60) unlink($t['server_path']);
}

if (isset($_GET["klasor"]) && $_GET["klasor"] != "" && in_array($_GET["klasor"], $klasorler)) $klasor = $_GET["klasor"]; else $klasor = current($config['folders']);
if (isset($_GET["altklasor"]) && $_GET["altklasor"]) $altklasor = trim($_GET["altklasor"], '/') . '/'; else $altklasor = '';
if($altklasor == '/') $altklasor = '';
?>
<!DOCTYPE html>
<html lang="tr"><head>
	<!-- Ekim 2011 -->
	<title>Bandit File and Image Manager</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="file manager, image manager, web based" />
	<meta name="Description" content="Web based file manager and image manager" />
	<meta name="Title" content="Bandit File and Image Manager" />

	<meta name="robots" content="index,follow" />
	<meta name="Revisit" content="After 7 days" />
	<meta name="Copyright" content="Tayfun Duran" />
	<meta name="publisher" content="Tayfun Duran" />
	<meta name="coverage" content="Worldwide" />
	<meta name="language" content="" />
	<meta name="rating" content="General" />
	<meta name="author" content="Tayfun Duran http://bandit.tayfunduran.com" />

	<meta http-equiv="imagetoolbar" content="no" /> 
	<meta http-equiv="MSThemeCompatible" content="no" />
	<meta http-equiv="Resource-type" content="document" />
	<meta http-equiv="Distribution" content="global" />

	<link rel="stylesheet" type="text/css" href="css/reset.css" />
	<link rel="stylesheet" type="text/css" href="css/tayfun-format.css" />
	<link rel="stylesheet" type="text/css" href="css/jquery.fancybox-1.3.4.css" />
	<link rel="stylesheet" type="text/css" href="css/imgareaselect-default.css" />
	<link rel="stylesheet" type="text/css" href="css/bandit.css" />
	<link href='http://fonts.googleapis.com/css?family=Andika&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

	<script type="text/javascript" language="javascript" src="js/jquery-1.6.4.min.js"></script>
	<script type="text/javascript" language="javascript" src="js/jquery.cookie.js"></script> 
	<script type="text/javascript" language="javascript" src="js/jquery.form.js"></script> 
	<script type="text/javascript" language="javascript" src="js/jquery.fancybox-1.3.4.pack.js"></script> 
	<script type="text/javascript" language="javascript" src="js/jquery.MultiFile.pack.js"></script> 
	<script type="text/javascript" language="javascript" src="js/jqdnr.js"></script> 
	<script type="text/javascript" language="javascript" src="js/jquery.imgareaselect.pack.js"></script> 
	<script type="text/javascript" language="javascript" src="js/bandit.js"></script>

	<script language="javascript" type="text/javascript">
		var nklasor = '<?echo _('folder');?>';
		var dosyalar;
		var klasor = '<?php echo $klasor?>';
		var altklasor = '';
		var sira = 'name';
		var goru = 'det';
		var kilomega = new Array('b ', 'kb', 'mb', 'gb', 'tb', '?b');
		var pulyukle = false;
		var perpage = <?=$config['per_page']?>;

		if($.cookie('bandit_goru')) goru = $.cookie('bandit_goru');
		if($.cookie('bandit_sira')) sira = $.cookie('bandit_sira');
		$.cookie('bandit_goru', goru, { expires: 30, path: '/' });
		$.cookie('bandit_sira', sira, { expires: 30, path: '/' });

		jQuery.fn.log = function (msg) {
			console.log("%s: %o", msg, this);
			return this;
		};

		$(document).ready(function(){
			yukseklik();
			listecek();
			$('.kutula').fancybox({
				hideOnOverlayClick: false
			});
			$('#duzenle').fancybox({
				type: 'ajax',
				hideOnOverlayClick: false,
				showCloseButton: false
			});
			$("#isleform").ajaxForm({
				dataType: "text",
				error: function(nesne, sebep, hata){
					bilgi('Hata!');
				},
				success: function(sonuc){
					$('#onizle').html('');
					listecek();
					bilgi(sonuc);
				}
			});

			$("#tutform").ajaxForm({
				dataType: "text",
				error: function(nesne, sebep, hata){
					bilgi('Hata!');
				},
				success: function(sonuc){
					$('#onizle').html('');
					listecek();
					bilgi(sonuc);
				}
			});

			$('#' + goru).addClass('red');
			$('#' + sira).addClass('red');


			<?if($config['auth_type'] == 'i'){?>
				$('#lgo').click(function(){ location = './?cik'; });
				<?
			}?>
			$('#filtre').keydown(function(event){
				if (event.keyCode == '13') listecek();
			});
			$('#ref').click(function(){listecek();});
			$('#upf').click(function(){
				if(altklasor != ''){
					akdizi = altklasor.split('/');
					akdizi.pop();
					akdizi.pop();
					altklasor = akdizi.join('/') + '/';
					if(altklasor == '/') altklasor = '';
					listecek();
				}
			});
			$('#new').click(function(){
				$('#yk').toggle();
				$('#yeniklasorisim').focus();
			});
			$('#cut').click(function(){
				tutla();
				$('#tutislem').val('cut');
				bilgi('<?echo _('Selection cut.');?>');
			});
			$('#cop').click(function(){
				tutla();
				$('#tutislem').val('cop');
				bilgi('<?echo _('Selection copied.');?>');
			});
			$('#pas').click(function(){
				if(confirm('<?echo _('Same named files will be overwritten. Do you confirm?');?>')) $('#tutform').submit(); else return false;
			});
			$('#del').click(function(){
				if(confirm('<?echo _('Selected files will be deleted. Do you confirm?');?>')) $('#isleform').submit(); else return false;
			});
			$('#zip').click(function(){
				bilgi('<?echo _('Please wait...');?>');
				$("input.isaret:checked").each(function(){
					$('#zipic').append($('<input name="liste[]" type="text" value="' + $(this).val() + '" />'));
				});
				$('#zipform').submit();
				$('#zipic').html('');
			});
			$('#ire').click(function(){
				$('#ren').toggle();
				$('#yeniisim').focus();
			});
			$('.sira').click(function(){
				sirala($(this).attr('id'));
				$('.sira').removeClass('red');
				$(this).addClass('red');
				$.cookie('bandit_sira', $(this).attr('id'), { expires: 30, path: '/' });
			});
			$('.gorunum').click(function(){
				$('#dosyalistesi').removeClass(goru);
				goru = $(this).attr('id');
				$('#dosyalistesi').addClass(goru);
				pulla(0);
				$('.gorunum').removeClass('red');
				$(this).addClass('red');
				$.cookie('bandit_goru', goru, { expires: 30, path: '/' });
			});
			$('.sec').click(function(){
				secim = $(this).attr('id');
				if(secim == 'selectall') $('.isaret').attr('checked', true); else $('.isaret').attr('checked', false);
			});
			$('#klasorsec').change(function(){
				klasordegis($(this).val());
			});
			$('#islemmenu ul').delegate("li", "hover", function(){
				$(this).toggleClass("hov");
			});
			$('#islemmenu').mouseover(function(){ $(this).show();}).mouseout(function(){ $(this).hide();});
			$('.kapat').click(function(){
				$('#yk').hide();
				$('#ren').hide();
			});

			$("#yeniklasorform").ajaxForm({
				dataType: "text",
				error: function(nesne, sebep, hata){
					bilgi('Hata!');
				},
				success: function(sonuc){
					$('#yeniklasorisim').val('');
					$('#yk').hide();
					listecek();
					bilgi(sonuc);
				}
			});

			$("#yeniisimform").ajaxForm({
				dataType: "text",
				error: function(nesne, sebep, hata){
					bilgi('Hata!');
				},
				success: function(sonuc){
					$('#yeniisim').val('');
					$('#ren').hide();
					listecek();
					bilgi(sonuc);
				}
			});

		});

		$(window).resize(function(){yukseklik();});

		function yukseklik(){
			$("#sagic").height($(window).height() - 92);
		}

		function jsonsirala(nesne, ne){
			var dizi = new Array();
			i = 0;
			$.each(nesne, function(a, d){
				dizi[i] = new Array();
				dizi[i][0] = eval('d.' + ne) + d.name ;
				dizi[i][1] = d.name;
				dizi[i][2] = d.ext;
				dizi[i][3] = d.size;
				dizi[i][4] = d.date;
				dizi[i][5] = d.relative_path;
				dizi[i][6] = d.server_path;
				dizi[i][7] = d.dimension;
				i++;
			});
			dizi.sort();
			return dizi;
		}

		function listecek(){
			$('#goster').html('<img src="resimler/bekle.gif" width="16" height="16" border="0" />');
			$('#onizle').html('');
			filtre = $("#filtre").val();
			ky = klasor + altklasor;
			if(filtre != '') ky = ky + '*' + filtre + '*';
			$('#klasoryol').html(ky);
			$.ajax({
				type	: "POST",
				url		: "listele.php",
				data	: "klasor=" + klasor + "&altklasor=" + altklasor + "&filtre=" + filtre,
				dataType: "json",
				error	: function(nesne, sebep, hata){

				},
				success	: function(sonuc){
					dosyalar = sonuc;
					$('#yeniklasor').val(ky);
					$('#islemklasor').val(ky);
					$('#yeniisimklasor').val(ky);
					$('#zipklasor').val(ky);
					$('#tutklasor').val(ky);
					listele();
					pulyukle = false;
					pulla(0);
				}
			});
		}

		function listele(){
			v = '<ul id="dosyalistesi" class="' + goru + '">';
			dizi = jsonsirala(dosyalar.d, sira);

			ks = 0;
			$.each(dosyalar.k, function(i, d){
				v = v + '<li id="' + d.name + '" class="bold"><img src="resimler/klasor.png" class="resim pointer" /><input type="checkbox" class="isaret" name="liste[]" id="' + i + '" value="' + d.name + '" /><img src="resimler/textfield_rename.png" alt="' + d.name + '" id="rename' + i + '" class="ren" /><label for="' + i + '"><span class="altklasor pointer">' + d.name + '</span><span class="zaman">&nbsp;</span><span class="boyut">&nbsp;</span><span class="olcu">&nbsp;</span><span class="tip">' + nklasor + '</span></label><img src="resimler/bos.gif" width="1" height="1" class="clear block" /></li>';
				ks++;
			});
			ds = 0;
			db = 0;
			sf = 0;
			$.each(dizi, function(i, d){
				v = v + '<li id="' + d[1] + '"><input type="hidden" id="" value="' + d[1] + '|' + d[2] + '|' + d[3] + '|' + d[4] + '|' + d[5] + '|' + d[6] + '|' + d[7] + '" /><img src="resimler/bos.gif" width="90" height="90" rsrc="cache/?pul=90x90/' + (d[5] + d[1]).replace('/', '@@@@@') + '" alt="' + d[1] + '" class="resim sayfa' + sf + '" /><input type="checkbox" class="isaret" name="liste[]" id="' + i + '" value="' + d[1] + '" /><img src="resimler/cog.png" alt="' + d[1] + '" id="isl' + i + '" class="islem" /><label for="' + i + '"><span class="isim">' + d[1] + '</span><span class="zaman">' + d[4] + '</span><span class="boyut">' + nebyte(d[3]) + '</span><span class="olcu">' + d[7] + '</span><span class="tip">' + d[2] + '</span></label><img src="resimler/bos.gif" width="1" height="1" class="clear block" /></li>';
				ds++;
				db = db + d[3]
				if(ds % perpage == 0){
					sf++;
					v = v + '<li class="sayfala" title="' + sf + '"><br /><br /><?echo _('Please click<br />to load<br />the thumbnails.');?><br /><br /><img src="resimler/arrow_right.png" width="16" height="16" border="0" /></li>';
				}
			});
			v = v + '</ul><img src="resimler/bos.gif" class="block clear" width="1" height="1" />';
			$('#klasorbilgi').html(ks + ' <?echo _('Folder(s)');?>, ' + ds + ' <?echo _('File(s)');?>, ' + nebyte(db));
			$('#goster').html(v);

			$("#dosyalistesi").delegate("li", "hover", function(){
				$(this).toggleClass("hov");
			});
			$('.sayfala').click(function(){
				s = $(this).attr('title');
				$(this).remove();
				pulla(s);
			});
			$('.islem').click(function(){
				lipos = $(this).closest("li").position();
				tumu = $(this).closest("li").children('input').val();
				td = tumu.split('|');
				if(td[1] == 'png' || td[1] == 'jpg' || td[1] == 'jpeg' || td[1] == 'jpe' || td[1] == 'gif' || td[1] == 'swf'){
					yol = td[4] + td[0];
				}else if(td[1] == 'txt' || td[1] == 'css' || td[1] == 'html'){
					yol = td[4] + td[0];
				}else{
					yol = '#gorunumyok';
				}
				$('#buyukgoster').attr('href', yol).attr('title', td[0]);
				$('#duzenle').attr('href', 'duzenleform.php?yol=' + td[4] + td[0] + '&b= ' + td[6]).attr('title', td[0]);
				$('#indir').attr('href', 'indir.php?yol=' + td[4] + td[0]).attr('title', td[4] + td[0]);
				$('#yeniisim').val(td[0]);
				$('#eskiisim').val(td[0]);
				$('#iin').unbind('click');
				$('#iin').click(function(){
					b = td[6].split('×');
					insertfile(td[4] + td[0], b[0], b[1]);
				});

				pos = $(this).position();
				$('#ina').html($(this).attr('alt'));
				$('#ren')
					.css("top", (lipos.top + pos.top) + "px")
					.css("left", (lipos.left + pos.left) + "px");
				$('#islemmenu')
					.css("top", (lipos.top + pos.top - 10) + "px")
					.css("left", (lipos.left + pos.left - 10) + "px")
					.show();
			});
			$('.ren').click(function(){
				isi = $(this).attr('alt');
				$('#yeniisim').val(isi);
				$('#eskiisim').val(isi);
				pos = $(this).position();
				lipos = $(this).closest("li").position();
				$('#ren')
					.css("top", (lipos.top + pos.top - 10) + "px")
					.css("left", (lipos.left + pos.left - 10) + "px")
					.toggle();
				$('#yeniisim').focus();
			});
			$('.isim, .resim').click(function(){
				tumu = $(this).closest("li").children('input').val();
				td = tumu.split('|');
				if($(this).attr('src') == "resimler/klasor.png"){
					altklasor = altklasor + td[0] + '/';
					listecek();
					klacook();
				}else{
					yol = td[4] + td[0];
					linki = yol;
					if(td[1] == 'png' || td[1] == 'jpg' || td[1] == 'jpeg' || td[1] == 'jpe' || td[1] == 'gif'){
						ici = '<a href="' + linki + '" title="' + td[0] + '" class="kutula"><img src="cache/?pul=192x0/' + yol + '" border="0" class="block bekle" /></a>';
					}else if(td[1] == 'swf' || td[1] == 'txt' || td[1] == 'css' || td[1] == 'html'){
						ici = '<p class="grey9"><?echo _('Just view option');?></p>';
					}else{
						ici = '<p class="red"><?echo _('No preview!');?></p>';
						linki = '#gorunumyok';
					}
					ici = ici + yol + '<br />' + td[6] + ' &nbsp; ' + nebyte(td[2]) + '<div class="fs8">' + td[5] + '</div>';
					ici = ici + '<div class="align-right" style="margin-top: 10px;">';
					ici = ici + '<a href="' + linki + '" title="' + td[0] + '" class="kutula"><img src="resimler/find.png" title="<?echo _('View');?>" /></a> &nbsp; ';
					ici = ici + '<a href="indir.php?yol=' + yol + '" title="' + yol + '"><img src="resimler/disk.png" title="<?echo _('Download');?>" /></a> &nbsp; ';
					ici = ici + '<img src="resimler/textfield_rename.png" alt="' + td[0] + '" title="<?echo _('Rename');?>" border="0" id="rename" /> &nbsp; ';
					ici = ici + '<a href="duzenleform.php?yol=' + td[4] + td[0] + '&b= ' + td[6] + '" title="' + td[4] + td[0] + '" id="duzenle2"><img src="resimler/pencil.png" title="<?echo _('Edit');?>" /></a> &nbsp; ';
					ici = ici + '<img src="resimler/hand.png" title="<?echo _('Insert');?>" alt="' + td[4] + td[0] + '×' + td[6] + '" id="insert" class="pointer" />';
					ici = ici + '</div>';

					$('#onizle').html(ici);
					$('#onizle a.kutula').fancybox({
						hideOnOverlayClick: false
					});
					$('#onizle #duzenle2').fancybox({
						type: 'ajax',
						hideOnOverlayClick: false,
						showCloseButton: false
					});
					$('#rename').click(function(){
						isi = $(this).attr('alt');
						$('#yeniisim').val(isi);
						$('#eskiisim').val(isi);
						pos = $(this).position();
						$('#ren')
							.css("top", (pos.top - 10) + "px")
							.css("left", (pos.left - 10) + "px")
							.toggle();
						$('#yeniisim').focus();
					});
					$('#insert').click(function(){
						p = $(this).attr('alt').split('×');
						insertfile(p[0], p[1], p[2]);
					});
				}
			});
			$('.altklasor').click(function(){
				altklasor = altklasor + $(this).html() + '/';
				listecek();
				klacook();
			});

		}

		function klasordegis(k){
			klasor = k;
			altklasor = '';
			listecek();
			klacook();
		}

		function sirala(d){
			sira = d;
			listele();
		}

		function nebyte(d){
			if(isNaN(d)) return '';
			d = parseInt(d);
			i = 0;
			while(d > 1024){
				d = d / 1024;
				i++;
			}
			if(i == 0) return d.toFixed(0) + ' &nbsp; ' + kilomega[i];
			else return d.toFixed(1) + ' ' + kilomega[i];
		}

		function bilgi(m, s){
			$('#bilgikutusu').html(m);
			$('#bilgikutusu').css('left', (($(window).width() - $('#bilgikutusu').width()) / 2) + 'px');
			$('#bilgikutusu').show();
			if(isNaN(s)) s = 4000;
			setTimeout("$('#bilgikutusu').hide('slow')", s);
		}

		function tutla(){
			$('#tutic').html('');
			$("input.isaret:checked").each(function(){
				$('#tutic').append($('<input type="text" name="liste[]" value="' + $(this).val() + '" />'));
			});
			$('#tutklasorden').val(klasor + altklasor);
		}

		function pulla(s){
			if(goru == 'pul'){
				$('.sayfala').show();
				$('.sayfa' + s).addClass('yukle').each(function(){
					$(this).attr('src', $(this).attr('rsrc'));
				});
			}
		}

		function klacook(){
			$.cookie('bandit_alkl', altklasor, { expires: 1, path: '/' });
			$.cookie('bandit_klas', klasor, { expires: 1, path: '/' });
		}

		<?=$config['costum_function']?>
	</script>

</head><body><a name="enust"></a><input type="hidden" id="fancy" />
	<div id="kapsul">
		<div id="bilgikutusu"></div>
		<div id="ust">
			<?php echo _("Selected Folder: ");?><span id="klasoryol" class="green"></span>
			<?if($config['auth_type'] == 'i'){?>
				<img id="lgo" class="pointer float-right" style="margin: 0 2px 2px 5px;" src="resimler/switch.png" alt="<?php echo _('Logout');?>" title="<?php echo _('Logout');?>" width="16" height="16" border="0" />
				<?
			}?>
			<span id="klasorbilgi"></span>
			<br class="clear" />
			<img src="resimler/funnel.png" alt="<?php echo _('Filter');?>" title="<?php echo _('Filter');?>" width="16" height="16" border="0" />
			<input type="text" id="filtre" class="f1" style="width: 100px;" title="<?php echo _('Filter');?>" />
			<img id="ref" class="pointer" src="resimler/arrow_refresh.png" alt="<?php echo _('Refresh');?>" title="<?php echo _('Refresh');?>" width="16" height="16" border="0" />
			<img id="upf" class="pointer" src="resimler/up.png" alt="<?php echo _('Parent Folder');?>" title="<?php echo _('Parent Folder');?>" width="16" height="16" border="0" />
			<span class="float-right" id="dugmeler">
				<a href="yukleform.php" class="kutula"><img src="resimler/rocket.png" alt="<?php echo _('Upload');?>" title="<?php echo _('Upload');?>" width="16" height="16" border="0" /></a>
				<img id="new" src="resimler/folder.png" alt="<?php echo _('New Folder');?>" title="<?php echo _('New Folder');?>" width="16" height="16" border="0" />
					<div id="yk">
						<form id="yeniklasorform" action="yeniklasor.php" method="post">
						<input type="hidden" name="klasor" id="yeniklasor" value="" />
						<input type="text" class="f1" name="isim" id="yeniklasorisim" style="width: 200px;" />
						<input type="submit" value="<?php echo _("OK");?>" class="f2" />
						<input type="button" value="x" class="f2 kapat" />
					</form></div>
				<img src="resimler/bos.gif" alt="<?php echo _('Separator');?>" width="1" height="16" border="0" class="ayrac" />
				<img id="cut" class="pointer" src="resimler/cut.png" alt="<?php echo _('Cut');?>" title="<?php echo _('Cut');?>" width="16" height="16" border="0" />
				<img id="cop" class="pointer" src="resimler/page_2_copy.png" alt="<?php echo _('Copy');?>" title="<?php echo _('Copy');?>" width="16" height="16" border="0" />
				<img id="pas" class="pointer" src="resimler/page_paste.png" alt="<?php echo _('Paste');?>" title="<?php echo _('Paste');?>" width="16" height="16" border="0" />
				<img src="resimler/bos.gif" alt="<?php echo _('Separator');?>" width="1" height="16" border="0" class="ayrac" />
				<img id="del" class="pointer" src="resimler/bin_closed.png" alt="<?php echo _('Delete');?>" title="<?php echo _('Delete');?>" width="16" height="16" border="0" />
				<img id="zip" class="pointer" src="resimler/compress.png" alt="<?php echo _('Zip');?>" title="<?php echo _('Zip');?>" width="16" height="16" border="0" />
				<img src="resimler/bos.gif" alt="<?php echo _('Separator');?>" width="1" height="16" border="0" class="ayrac" />
				<a href="bilgi.php" class="kutula"><img id="inf" src="resimler/exclamation.png" alt="<?php echo _('Information');?>" title="<?php echo _('Information');?>" width="16" height="16" border="0" /></a>
			</span>
		</div>
		<div id="sag"><div id="sagic">
			<form action="isle.php" id="isleform" method="post" style="display:inline;">
				<input type="hidden" name="islem" id="islem" value="sil" />
				<input type="hidden" name="klasor" id="islemklasor" value="" />
				<div id="goster"></div>
			</form>
		</div></div>
		<div id="sol"><div id="solic">
			<h2><?php echo "Folder";?>
				<select id="klasorsec" class="klasorsec">
					<?php $j = 0;
					foreach($config['folders'] as $i => $k){?>
						<option value="<?php echo $k;?>"<?if($j == 0) echo ' selected="selected"';?>><?php echo $i; ?>&nbsp;</option>
						<?php $j++;
					}?>
				</select>
				<img src="resimler/bos.gif" width="1" height="1" class="clear block" />
			</h2>
			<h2><?php echo "View";?> &nbsp; &nbsp; 
				<span class="fs10 normal float-right">
					<span id="det" class="pointer gorunum"><?php echo _('Details');?></span> &nbsp; &nbsp;
					<span id="lis" class="pointer gorunum"><?php echo _('List');?></span> &nbsp; &nbsp;
					<span id="pul" class="pointer gorunum"><?php echo _('Thumbnails');?></span>&nbsp;
				</span>
			</h2>
			<h2><?php echo "Sort";?> &nbsp; &nbsp; 
				<span class="fs10 normal float-right">
					<span id="name" class="pointer sira"><?php echo _('Name');?></span> &nbsp; &nbsp;
					<span id="ext" class="pointer sira"><?php echo _('Type');?></span> &nbsp; &nbsp;
					<span id="size" class="pointer sira"><?php echo _('Size');?></span> &nbsp; &nbsp;
					<span id="date" class="pointer sira"><?php echo _('Date');?></span>&nbsp;
				</span>
			</h2>
			<h2><?php echo "Select";?> &nbsp; &nbsp; 
				<span class="fs10 normal float-right">
					<span id="selectall" class="pointer sec"><?php echo _('All');?></span> &nbsp; &nbsp;
					<span id="selectnone" class="pointer sec"><?php echo _('None');?></span>&nbsp;
				</span>
			</h2>
			<h2><?php echo "Preview";?></h2>
			<div id="onizle"></div>
			<div id="islemmenu">
				<ul>
					<li id="ina" class="italic bold align-center fs9"></li>
					<li id="igo"><a href="resimler/bos.gif" id="buyukgoster" class="kutula"><img src="resimler/find.png" title="<?echo _('View');?>" /><?echo _('View');?></a></li>
					<li id="ido"><a href="" id="indir"><img src="resimler/disk.png" title="<?echo _('Download');?>" /><?echo _('Download');?></a></li>
					<li id="ire"><img src="resimler/textfield_rename.png" title="<?echo _('Rename');?>" border="0" /><?echo _('Rename');?></li>
					<li id="ied"><a href="resimler/bos.gif" id="duzenle"><img src="resimler/pencil.png" title="<?echo _('Edit');?>" /><?echo _('Edit');?></a></li>
					<li id="iin"><img src="resimler/hand.png" title="<?echo _('Insert');?>" /><?echo _('Insert');?></li>
				</ul>
			</div>
			<div id="ren">
				<form id="yeniisimform" action="yeniisim.php" method="post">
				<input type="hidden" name="klasor" id="yeniisimklasor" value="" />
				<input type="hidden" name="eskiisim" id="eskiisim" value="" />
				<input type="text" class="f1" name="yeniisim" id="yeniisim" style="width: 250px;" />
				<input type="submit" value="<?php echo _("OK");?>" class="f2" />
				<input type="button" value="x" class="f2 kapat" />
			</form></div>
			<form id="zipform" action="isle.php" target="_blank" method="post">
				<input type="hidden" name="islem" value="zip" />
				<input type="hidden" name="klasor" id="zipklasor" value="" />
				<div id="zipic"></div>
			</form>
			<form id="tutform" action="isle.php" method="post">
				<input type="hidden" name="islem" id="tutislem" value="kopya" />
				<input type="hidden" name="klasor" id="tutklasor" value="" />
				<input type="hidden" name="klasorden" id="tutklasorden" value="" />
				<div id="tutic"></div>
			</form>
			<div id="noview"><div id="gorunumyok"><?echo _('No View!');?></div></div>
		</div></div>
		<div id="alt"><div id="altic">
			Bandit File and Image Manager &bull; by Tayfun Duran 2011
		</div></div>
	</div>
</body></html>
