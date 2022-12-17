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
			        echo "Ошибка подключения к БД";
                    echo mysqli_error(self::$instance);
                    $instance = null;
                    return;
			    }
			}

			return DBWorker::$instance;
		}

		// Запрос к БД
		static public function Query(string $sql)
		{
			$query = mysqli_query(DBWorker::GetInstance(), $sql);

            if ($query === false)
            {
                die(mysqli_error(self::$instance));
            }

            return $query;
		}
	}
?>