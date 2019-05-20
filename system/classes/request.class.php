<?php
	class request
	{
		public function PostStr($val)
		{
			//return htmlentities($val);
			return filter_input(INPUT_POST, $val, FILTER_SANITIZE_STRIPPED);
			//return filter_input(INPUT_POST, $val, FILTER_SANITIZE_SPECIAL_CHARS);
		}
		public function PostInt($val)
		{
			$value = filter_input(INPUT_POST, $val, FILTER_SANITIZE_NUMBER_INT);
			return (int)(($value != '') ? $value : 0);
		}
		public function PostFloat($val)
		{
			$value = filter_input(INPUT_POST, $val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
			return (float)(($value != '') ? $value : 0);
		}
		public function PostCheck($val)
		{
			return ((isset($_POST[$val])) ? 1 : 0);
		}
		public function GetStr($val)
		{
			//return htmlentities($val);
			return filter_input(INPUT_GET, $val, FILTER_SANITIZE_STRIPPED);
		}
		public function GetInt($val)
		{
			$value = filter_input(INPUT_GET, $val, FILTER_SANITIZE_NUMBER_INT);
			return (int)(($value != '') ? $value : 0);
		}
		public function GetFloat($val)
		{
			$value = filter_input(INPUT_GET, $val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
			return (float)(($value != '') ? $value : 0);
		}
		public function GetCheck($val)
		{
			return ((isset($_GET[$val])) ? 1 : 0);
		}


		public function test(){
			echo '<h2>POST</h2>';
			echo '<pre style="background-color:#FEE;padding:20px;margin:20px 10px;">';
				print_r($_POST);
			echo '</pre>';
			echo '<h2>GET</h2>';
			echo '<pre style="background-color:#EFE;padding:20px;margin:20px 10px;">';
				print_r($_GET);
			echo '</pre>';
			exit();
		}
		
	}
?>