<?php
	class xml{
		public function start()
		{
			return '<?xml version="1.0" encoding="UTF-8"?><result> ';
		}
		public function end()
		{
			return '</result>';
		}
		public function element($key, $value)
		{
			$value = (trim($value) == '') ? ' ' : $value;
			return '<' . $key . '>' . htmlentities(stripslashes($value)) . '</' . $key . '>';
		}
		public function parent($key, $value)
		{
			$value = (trim($value) == '') ? ' ' : $value;
			return '<' . $key . '>' . $value . '</' . $key . '>';
		}
		
		public function error($user_id, $errorMsg, $errorCode = 0, $logError = true)
		{
			if($logError == true)
			{
				$mysql = new mysql();
				$ip = $_SERVER['REMOTE_ADDR'];
				$sql = 'insert into ' . API_ERROR_LOG . '
					(error_code, user_id, ip, date_time, message)
					values(
						' . $mysql->quote($errorCode) . ',
						' . $mysql->quote($user_id) . ',
						' . $mysql->quote($ip) . ',
						now(),
						' . $mysql->quote($errorMsg) . '
					)';
				$mysql->query($sql);
			}
			
			$ret = $this->element('status', $errorCode);
			$ret .= $this->element('err', $errorMsg);
			$ret .= $this->end();
			return $ret;
		}
		
		function XMLtoARRAY($rawxml)
		{
			$xml_parser = xml_parser_create();
			xml_parse_into_struct($xml_parser, $rawxml, $vals, $index);
			xml_parser_free($xml_parser);
			$params = array();
			$level = array();
			$alreadyused = array();
			$x = 0;
			foreach ($vals as $xml_elem)
			{
				if ($xml_elem['type'] == 'open')
				{
					if (in_array($xml_elem['tag'], $alreadyused))
					{
						++$x;
						$xml_elem['tag'] = $xml_elem['tag'].$x;
					}
					$level[$xml_elem['level']] = $xml_elem['tag'];
					$alreadyused[] = $xml_elem['tag'];
				}
				if ($xml_elem['type'] == 'complete')
				{
					$start_level = 1;
					$php_stmt = '$params';
					while ($start_level < $xml_elem['level'])
					{
						$php_stmt .= '[$level['.$start_level.']]';
						++$start_level;
					}
					$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
					eval($php_stmt);
					continue;
				}
			}
			return $params;
		}
		
	}
?>