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
					$result .= "<p>" . $p->nodeValue . "<p/>";
				}
			}

			return $result;
		}

		// Метод, который позволяет расставить запятые перед А и НО
		static function CommaPlacement($text)
		{
			// Очищаем текст от header и footer
			$text = TextWorker::RemoveHeadersAndFooters($text);

			$text = preg_replace("#([а-яА-Яa-zA-Z])(\s)?(<*>)*(\s)?(но) #iu", "$1$2$3, но ", $text);
			$text = preg_replace("#([а-яА-Яa-zA-Z])(\s)?(<*>)*(\s)?(а) #iu", "$1$2$3, а ", $text);
			$text = preg_replace("#\.\.\.#", "&#8230;", $text);

			return $text;
		}

		// Метод, который позволяет убрать все шапки и подвалы
		static function RemoveHeadersAndFooters(string $str)
		{
			return preg_replace('/<(header|footer).*?>(.*?)<\/\1>/ism', "", $str);
		}
	}
?>