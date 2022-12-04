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

		// Метод, который позволяет убрать все шапки и подвалы
		static function RemoveHeadersAndFooters(string $str)
		{
			return preg_replace('/<(header|footer).*?>(.*?)<\/\1>/ism', "", $str);
		}

		// Метод, который позволяет создать меню ссылок
		static function CreateLinkHeader(string &$text)
		{
			// Очищаем текст от header и footer
			$text = TextWorker::RemoveHeadersAndFooters($text);
			
			// Создаем DomDocument позволяющйи работать со страницей
			$dom = new DOMDocument();
			$dom->loadHTML(mb_convert_encoding( $text, 'HTML-ENTITIES', 'UTF-8' ));

			// ищем все теги a
			$aTags = $dom->getElementsByTagName('a');

			// Итог, который мы возвращаем
			$result = "";

			// Перебираем все элементы
			$result .= "<ul class=\"exercise-navigation\">";
			for ($i = 0; $i < $aTags->length; ++$i)
			{
				$aTags[$i]->setAttribute("name", "header-link-" . $i);
				$result .= "<li><a href=\"#" ."header-link-" . $i ."\">" . $aTags[$i]->nodeValue . "<a/></li>";
			}
			$result .= "</ul>";
			$text = $dom->saveHTML();

			return $result;
		}

		static function LightRepeats($text)
		{
			$result = "";
			// Очищаем текст от header и footer
			$text = TextWorker::RemoveHeadersAndFooters($text);

			// Находим все P теги
			preg_match_all("#<.*p.*>.*</p>#", $text, $pTags);	
			preg_match_all("#<.*p.*>.*</p>#", $text, $pStartTags);	
			
			for ($i = 0; $i < count($pTags[0]); $i++)
			{
				$pClearText = strip_tags($pTags[0][$i]);
				
				// выберем содержимое
				$pClearText = preg_replace("#[^[(a-zA-Zа-яА-Я)|\s]#ui", "", $pClearText);
				preg_match_all("#\b\S+\b#ui", $pClearText, $words);
				
				$repeatedWords = array();

				foreach ($words[0] as $word) 
				{
					preg_match_all("#\b" . $word . "\b#ui", $pClearText, $repeat);
					if (count($repeat[0]) > 2 && !in_array($word, $repeatedWords))
					{
						array_push($repeatedWords, $word);
					}
				}

				foreach ($repeatedWords as $word) 
				{
					$pTags[0][$i] = preg_replace("#(" . $word . ")#", "<mark>$1</mark>", $pTags[0][$i]);
					$result .= $pTags[0][$i];
				}
			}

			for ($i = 0; $i < count($pStartTags[0]); $i++)
			{
				$text = str_replace($pStartTags[0][$i], $pTags[0][$i], $text);			
			}

			return $text;
		}
	}
?>