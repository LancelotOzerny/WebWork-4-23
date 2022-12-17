<?php
	// Подключение класса
	require("modules/DBWorker.php");

	$message = "";

	if (isset($_POST['export']))
	{
		// Кодируем таблицу в json
		$jsonString = json_encode(DBWORKER::Query("SELECT * FROM artist"));
		
		$uploadDir = 'export/';
		$filename = "artist_exported.json";
		$export_url = "http://localhost/LR5/Worker.php";

		file_put_contents($filename, $jsonString);

		$request = curl_init($export_url);

		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($request, CURLOPT_POSTFIELDS, 
			array(
				"file_exported" => new CURLFile($filename, null, $filename),
				"file_name" => $filename,
				"file_path" => $uploadDir,
				"file_data" => $jsonString
			)
		);
		$message = curl_exec($request);
		unlink($filename);
	}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="Styles/Exportimport.css">
	<title>Document</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poiret+One&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <?php include_once "navigation.html"; ?>
	<div class="container">
		<h1 class="page-title">Лабораторная работа №5</h1>

		<div class="description">
			<p class="description-title">Примечение*</p>
			<p class="description-element"><b>Вариант: </b>23</p>
			<p class="description-element"><b>Формат файла: </b>json</p>
			<p class="description-element"><b>Способ экспорта: </b>По внешнему скрипту </p>
			<p class="description-element"><b>Способ импорта: </b>По внешней ссылке</p>
		</div>

		<form action="export.php" method="Post" class="page-form">
			<input class="page-form-button" type="submit" name="export" value="Экспортировать">
		</form>

		<p style="margin-top: 50px;">
			<?php 
				echo $message;
			?>
		</p>


		<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
	</div>
</body>
</html>