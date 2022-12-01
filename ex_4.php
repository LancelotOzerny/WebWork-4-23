<?php 
	// Подключаем работу с текстом (класс TextWorker) 
	require("modules/TextWorker.php");

	// Отключаем показ предупрежддений (с других сайтов, если подключаем)
	error_reporting(0);

	// Результат выполненной работы
	$resultText = "";

	// Если пишем preset то вызываем одну из страниц (первую, т. к. вторая заблокирована)
	if (isset($_GET['preset']))
	{
		if ($_GET['preset'] == 1)
		{
			$page = new DOMDocument();
			$page->loadHTMLFile("https://ru.wikipedia.org/wiki/%D0%9A%D0%B8%D0%BD%D0%BE%D1%80%D0%B8%D0%BD%D1%85%D0%B8");
			$_POST['start-text'] = $page->saveHTML();
		}

		if ($_GET['preset'] == 3)
		{
			$page = new DOMDocument();
			$page->loadHTMLFile("ex_4_exercises/ex_4.3.html");
			$_POST['start-text'] = $page->saveHTML();
		}
	}

	// Если нажата кнопка Задание 3
	if (isset($_POST['exercise-3']))
	{
		$result = TextWorker::GetDirectSpeech($_POST['start-text']);
	}


	// Если нажата кнопка Задание 6
	if (isset($_POST['exercise-6']))
	{

	}


	// Если нажата кнопка Задание 14
	if (isset($_POST['exercise-14']))
	{

	}


	// Если нажата кнопка Задание 18
	if (isset($_POST['exercise-18']))
	{

	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Задание 4</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" href="Styles/ex_4.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1 class="page-title">Лабораторная работа номер 4</h1>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<form class="page-form" method="Post" action="ex_4.php?go" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-12">
							<textarea name="start-text" class="start-text" cols="30" rows="10"><?php if (isset($_POST['start-text'])){echo $_POST['start-text'];}?></textarea>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-3">
							<div class="exercise-btn-wrapper">
								<input type="submit" class="exercise-btn" name="exercise-3" value="Задание 3">
							</div>
						</div>

						<div class="col-md-3">
							<div class="exercise-btn-wrapper">
								<input type="submit" class="exercise-btn" name="exercise-6" value="Задание 6">
							</div>
						</div>

						<div class="col-md-3">
							<div class="exercise-btn-wrapper">
								<input type="submit" class="exercise-btn" name="exercise-14" value="Задание 14">
							</div>
						</div>

						<div class="col-md-3">
							<div class="exercise-btn-wrapper">
								<input type="submit" class="exercise-btn" name="exercise-18" value="Задание 18">
							</div>
						</div>
					</div>
				</form>		
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="edited-text">
					<?php echo $result; ?>
				</div>
			</div>
		</div>
	</div>

	<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
</body>
</html>