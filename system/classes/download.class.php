<?php
	class download
	{
		public function downloadContent($content, $fileName)
		{
			if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off');	}
			
			header('Pragma: public'); 	// required
			header('Expires: 0');		// no cache
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Cache-Control: private',false);
			header('Content-Type: application/force-download');
			header('Content-Disposition: attachment; filename=' . $fileName);
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.strlen($content));	// provide file size
			echo $content;
			exit();
		}
	}
?>