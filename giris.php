<?php
/*
*
* Tayfun Duran	tayfunduran@gmail.com
* Bandit File and Image Manager
* October 2011
* 
*/
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
	<meta name="language" content="tr-TR, Turkish, Türkçe, tr" />
	<meta name="rating" content="General" />
	<meta name="author" content="Tayfun Duran tayfunduran@gmail:com, taybo@taybo:net, yazılım, tasarım: http://taybo.net" />

	<meta http-equiv="imagetoolbar" content="no" /> 
	<meta http-equiv="MSThemeCompatible" content="no" />
	<meta http-equiv="Resource-type" content="document" />
	<meta http-equiv="Distribution" content="global" />

	<link rel="stylesheet" type="text/css" href="css/reset.css" />
	<link rel="stylesheet" type="text/css" href="css/tayfun-format.css" />
	<link rel="stylesheet" type="text/css" href="css/bandit.css" />

	<script type="text/javascript" language="javascript" src="js/jquery-1.6.4.min.js"></script>
	<script type="text/javascript" language="javascript" src="js/bandit.js"></script>
	<script language="javascript" type="text/javascript">
		$(document).ready(function(){
			$('#sifre').focus();
		});
	</script>

</head><body>
	<div id="kapsul">
		<div id="ust">
	<form action="./" method="post" style="margin: 40px;">
		<?echo _('Password')?> : 
		<input type="password" class="f1" style="width: 200px;" name="sifre" id="sifre" />
		<input type="submit" class="f2" value="Login" />
	</form>
		</div>
	</div>
</body></html>
