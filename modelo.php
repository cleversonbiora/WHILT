<?php
	session_start();
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <? include("header.php"); ?>
  </head>
  <body id="page-top" class="index">
	<? include("topo.php"); ?>
	<? include("footer.php"); ?>
    <script>
		function confirmar(form) {
			return confirm('Deseja realmente deletar este video?');
		}
		$.validate({
			lang: 'pt'
		});
	</script>
  </body>
</html>