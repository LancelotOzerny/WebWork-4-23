<?php
	class DBWorker
	{
		private static $instance;

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

		static public function Query(string $query)
		{
			mysqli_query(DBWorker::GetInstance(), $query);
		}

		static public function QueryResult(string $query)
		{
			$query_result = mysqli_fetch_all(mysqli_query(DBWorker::GetInstance(), $query), MYSQLI_ASSOC);
			return $query_result;
		}
	}
?>