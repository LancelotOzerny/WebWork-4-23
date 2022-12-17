<?php
	// Подключение класса
	require("modules/DBWorker.php");
	
	// Отключение предупреждений
	error_reporting(0);

	if (isset($_POST["import"]))
	{
		try
		{
			// Путь к файлу
			$filePath = $_POST['import-file-link'];	
			
			// Если формат файла не json то выдаем ошибку (строка 44)
			if (end(explode(".", $filePath)) == "json")
			{
				// Загрузка json файла
				$jsonString = file_get_contents($filePath);

				// Декодирование json файла
				$data = json_decode($jsonString, true);
				
				// Удаление записей из таблицы
				DBWorker::Query("DELETE FROM `artist`");
				
				// Количество записей
				$count_of_fields = 0;
				
				// Вставка записей
				foreach ($data as $field) 
				{
					$id = $field['id'];
					$image = $field['image'];
					$name = $field['name'];
					$biography = $field['biography'];
					$price = $field['price'];
					$category_id = $field['category_id'];

					DBWorker::Query("INSERT INTO `artist` (`id`, `image`, `name`, `biography`, `price`, `category_id`) 
									VALUES ('" . $id . "', '" . $image . "', '" . $name . "', '" . $biography . "', '" . $price . "', '" . $category_id . "')");	
					
					$count_of_fields++;
				}

				$import = true;
			}
			else
			{
				throw new Exception("Ошибка при считывании. Попробуйте проверить формат файла!");
			}
		}
		catch(ValueError $er)
		{
			echo "Ошибка при считывании. Попробуйте проверить путь к файлу!<br>";
		}
		catch(Throwable $th)
		{
			echo "Непредвиденная ошибка при считывании.<br/>";
		}
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

		<form action="import.php" method="Post" class="page-form">
			<input class="page-form-input" type="text" name="import-file-link" placeholder="Укажите ссылку на внешний файл" value="<?php echo (isset($importURL) == true ? $importURL : ''); ?>">
			<input class="page-form-button" type="submit" name="import" value="Импортировать">
		</form>

		<?php  if (isset($import)) {?>
			<div class="description">
				<br>
				<p>Файл с данными получен из <b><?php echo $_POST["import-file-link"]; ?></b> 
				и обработан. Обновлена таблица actors и <b><?php echo $count_of_fields; ?></b> записи(ей) в ней</p>
			</div>
		<?php } ?>

		<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
	</div>
</body>
</html>