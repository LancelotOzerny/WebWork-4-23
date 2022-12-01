<?php 
	class TextWorker
	{
		// Метод, который позволяет получить прямую речь ('p' начинающиеся с '-')
		static function GetDirectSpeech(string $text)
		{
			// Очищаем текст от header и footer
			$text = TextWorker::RemoveHeadersAndFooters($text);

			// Создаем DomDocument позволяющйи работать со страницей
			$dom = new DOMDocument();
			$dom->loadHTML(mb_convert_encoding( $text, 'HTML-ENTITIES', 'UTF-8' ));

			// ищем все теги p
			$pTags = $dom->getElementsByTagName('p');

			// Итог, который мы возвращаем
			$result = "";

			// Перебираем все элементы
			foreach ($pTags as $p) 
			{
				$value = mb_split('—', $p->nodeValue);
				
				if (trim($value[0]) == "")
				{
					$result .= $p->nodeValue . "<br/>";
				}
			}

			return $result;
		}

		// Метод, который позволяет убрать все шапки и подвалы
		static function RemoveHeadersAndFooters(string $str)
		{
			return preg_replace('/<(header|footer).*?>(.*?)<\/\1>/ism', "", $str);
		}
	}
?>