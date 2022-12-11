<?php
	// Класс, который позволяет работать с базой данных
	class DBWorker
	{
		// поле - экземпляр подключения к базе данных
		private static $instance;

		// метод, который позволяет установить подключение к БД если его не было и возвращает подключение
		static public function GetInstance()
		{
			if (DBWorker::$instance == null)
			{
				DBWorker::$instance = mysqli_connect("localhost", "root", "", "actors");

				if (mysqli_connect_errno())
			    {
			        $instance = null;
			        echo "Ошибка подключения к БД";
			 		return;
			    }
			}

			return DBWorker::$instance;
		}

		// Запрос к БД
		static public function Query(string $query)
		{
			mysqli_query(DBWorker::GetInstance(), $query);
		}

		// Запрос к БД с получением результата
		static public function QueryResult(string $query)
		{
			$query_result = mysqli_fetch_all(mysqli_query(DBWorker::GetInstance(), $query), MYSQLI_ASSOC);
			return $query_result;
		}
	}
?>