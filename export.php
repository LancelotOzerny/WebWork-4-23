<?php
	
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
</head>
<body>
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

		<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
	</div>
</body>
</html>