<?php
	class alert
	{
		public function display($msg)
		{
			echo '<div class="alert alert-warning" style="margin:20px;">
					' . stripslashes($msg) . '
					<button data-dismiss="alert" class="close" type="button"><i class="fa fa-times fa-1x"></i></button>
				</div>';
		}
	}
	
?>