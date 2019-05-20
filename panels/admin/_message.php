<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
	
	
	
	
	
	if($msg != '')
	{
		echo '
    	<div class="alert alert-danger alert-dismissable thanks">
    	  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    	  ' . $msg . '
    	</div>';
	}
?>