<?php
	class keyword
	{
		public function generate($count)
		{
			$str = "";
			$alpha_small = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
			$alpha_big = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			for($i=1;$i<=$count;$i++)
			{
				switch(rand(1,3))
				{
					case 1:
						$str .= $alpha_small[rand(0,count($alpha_small)-1)];
						break;
					case 2:
						$str .= $alpha_big[rand(0,count($alpha_big)-1)];
						break;
					case 3:
						$str .= rand(0,9);
						break;
					default:
						$str .= rand(0,9);
				}
			}
			return($str);
		}
	}	
?>