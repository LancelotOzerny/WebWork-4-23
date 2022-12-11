<?php
	if (isset($_POST['file_name']))
	{
		if (!file_exists($_POST['file_path']))
		{
			mkdir($_POST['file_path']);
		}

		if (move_uploaded_file($_FILES['file_exported']['tmp_name'], $_POST['file_path'] . $_POST['file_name'])) {
		   echo  $_POST['file_name'] . " передан скрипту worker.php по протоколу HTTP методом POST. <a href=\"". $_POST['file_path'] . $_POST['file_name'] . "\" download=\"" . $_POST['file_name'] . "\">Ссылка для скачивания</a>";
		}
		else
		{
			echo "Возникла непредвиденная ошибка. Файл не был создан.";
		}
	}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>

	<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
</body>
</html>