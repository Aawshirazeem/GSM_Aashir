<?php
	class graphics
	{

		function random_color_part() {
		    return str_pad( dechex( mt_rand( 200, 255 ) ), 2, '0', STR_PAD_LEFT);
		}

		function random_color() {
		    return $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
		}

		function inverseHex( $color )
		{
		     $color       = TRIM($color);
		     $prependHash = FALSE;
		 
		     IF(STRPOS($color,'#')!==FALSE) {
		          $prependHash = TRUE;
		          $color       = STR_REPLACE('#',NULL,$color);
		     }
		 
		     SWITCH($len=STRLEN($color)) {
		          CASE 3:
		               $color=PREG_REPLACE("/(.)(.)(.)/","\\1\\1\\2\\2\\3\\3",$color);
		          CASE 6:
		               BREAK;
		          DEFAULT:
		               TRIGGER_ERROR("Invalid hex length ($len). Must be (3) or (6)", E_USER_ERROR);
		     }
		 
		     IF(!PREG_MATCH('/[a-f0-9]{6}/i',$color)) {
		          $color = HTMLENTITIES($color);
		          TRIGGER_ERROR( "Invalid hex string #$color", E_USER_ERROR );
		     }
		 
		     $r = DECHEX(255-HEXDEC(SUBSTR($color,0,2)));
		     $r = (STRLEN($r)>1)?$r:'0'.$r;
		     $g = DECHEX(255-HEXDEC(SUBSTR($color,2,2)));
		     $g = (STRLEN($g)>1)?$g:'0'.$g;
		     $b = DECHEX(255-HEXDEC(SUBSTR($color,4,2)));
		     $b = (STRLEN($b)>1)?$b:'0'.$b;
		 
		     RETURN ($prependHash?'#':NULL).$r.$g.$b;
		}

	
		public function icon($icon)
		{
			switch($icon)
			{
				case 'add':
					return ' <img src="' . CONFIG_PATH_IMAGES . 'skin/add.png" width="10" height="10" alt="" /> ';
					break;
				case 'back':
					return ' <img src="' . CONFIG_PATH_IMAGES . 'skin/back.png" width="10" height="10" alt="" /> ';
					break;
				case 'download':
					return ' <img src="' . CONFIG_PATH_IMAGES . 'skin/download.png" width="10" height="10" alt="" /> ';
					break;
				case 'credits':
					return ' <img src="' . CONFIG_PATH_IMAGES . 'skin/credits.png" width="10" height="10" alt="" /> ';
					break;
				case 'cross':
					return ' <img src="' . CONFIG_PATH_IMAGES . 'skin/cross_16.png" width="10" height="10" alt="" /> ';
					break;
				case 'edit':
					return ' <img src="' . CONFIG_PATH_IMAGES . 'skin/edit.png" width="10" height="10" alt="" /> ';
					break;
				case 'ok':
					return ' <img src="' . CONFIG_PATH_IMAGES . 'skin/ok_16.png" width="10" height="10" alt="" /> ';
					break;
				case 'lock':
					return ' <img src="' . CONFIG_PATH_IMAGES . 'skin/lock_10.png" width="10" height="10" alt="" /> ';
					break;
				case 'reseller':
					return ' <img src="' . CONFIG_PATH_IMAGES . 'skin/reseller.png" width="10" height="10" alt="" /> ';
					break;
				case 'search':
					return ' <img src="' . CONFIG_PATH_IMAGES . 'skin/search.png" width="10" height="10" alt="" /> ';
					break;
			}
			
		}
		public function status($status)
		{
			return ($status == '1') ? '<i class="fa fa-circle-o text-success"></i>' : '<i class="fa fa-circle-o text-danger"></i>';
		}
		public function visible($status)
		{
			return ($status == '1') ? '<i class="fa fa-eye text-success"></i>' : '<i class="fa fa-eye-slash text-danger"></i>';
		}
		public function messagebox($message)
		{
			echo '<div class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					' . $message . '
				</div>';
		}
		public function messagebox_normal($message)
		{
			echo '<div class="alert ">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					' . $message . '
				</div>';
		}
		public function messagebox_warning($message)
		{
			echo '<div class="alert alert-danger fade in">
					<h4><i class="icon-warning-sign"></i> Warning!!!</h4>
					' . $message . '
				</div>';
		}
		public function messagebox_danger($message)
		{
			echo '<div class="alert alert-danger">
					<h4><i class="icon-warning-sign"></i> Error!!!</h4>
					' . $message . '
				</div>';
		}
		public function warning()
		{
			$request = new request();
			$strMsg = $request->getStr('msg');
			echo '
			
			<div class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					' . stripslashes($strMsg) . '
				</div>';
		}
		public function button_script($caption, $script,$width = "w2")
		{
		echo '<button class="btn-submit ' . $width . '" type="button" onClick="' . $script . '">
				  <div class="btn-submit"><p class="btn-submit">' . $caption . '</p></div>
				</button>';
		}
		public function button_submit($caption, $value = "submit", $name = "submit", $width = "w2")
		{
		echo '<button class="btn-submit ' . $width . '" name="' . $name . '" value="' . $value . '" type="submit">
				  <div class="btn-submit"><p class="btn-submit">' . $caption . '</p></div>
				</button>';
		}
		
		public function button_red($caption, $url,$width = "w2")
		{
		echo '<button class="btn-red-submit ' . $width . '" type="button" onClick="javascript:window.location=\'' . $url . '\'">
				  <div class="btn-red-submit"><p class="btn-red-submit">' . $caption . '</p></div>
				</button>';
		}
		public function button_red_script($caption, $script,$width = "w2")
		{
		echo '<button class="btn-red-submit ' . $width . '" type="button" onClick="' . $script . '">
				  <div class="btn-red-submit"><p class="btn-red-submit">' . $caption . '</p></div>
				</button>';
		}
		public function button_red_submit($caption, $value = "submit", $name = "submit", $width = "w2")
		{
		echo '<button class="btn-red-submit ' . $width . '" name="' . $name . '" value="' . $value . '" type="submit">
				  <div class="btn-red-submit"><p class="btn-red-submit">' . $caption . '</p></div>
				</button>';
		}


	}
?>