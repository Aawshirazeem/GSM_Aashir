<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
	if (defined("DEMO"))
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "utl_db_backup.html?reply=" . urlencode('reply_com_demo'));
		exit();
	}
	
	backup_tables(CONFIG_DB_HOST,CONFIG_DB_USER,CONFIG_DB_PASS,CONFIG_DB_NAME, '*');


	/* backup the db OR just a table */
	function backup_tables($host,$user,$pass,$name,$tables = '*')
	{
		
		$link = mysql_connect($host,$user,$pass);
		mysql_select_db($name,$link);
		
		//get all of the tables
		if($tables == '*')
		{
			$tables = array();
			$result = mysql_query('SHOW TABLES');
			while($row = mysql_fetch_row($result))
			{
				$tables[] = $row[0];
			}
		}
		else
		{
			$tables = is_array($tables) ? $tables : explode(',',$tables);
		}
		//var_dump($tables);exit;
		$return = '';
		//cycle through
		foreach($tables as $table)
		{
			$result = mysql_query('SELECT * FROM '.$table);
			$num_fields = mysql_num_fields($result);
			
			$return.= 'DROP TABLE '.$table.';';
			$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			$return.= "\n\n".$row2[1].";\n\n";
			
			for ($i = 0; $i < $num_fields; $i++) 
			{
				while($row = mysql_fetch_row($result))
				{
					$return.= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<$num_fields; $j++) 
					{
						$row[$j] = addslashes($row[$j]);
						$row[$j] = preg_replace("/\n/","\\n",$row[$j]);
						if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
						if ($j<($num_fields-1)) { $return.= ','; }
					}
					$return.= ");\n";
				}
			}
			$return.="\n\n\n";
		}
		
		//save file
		//chmod(CONFIG_PATH_EXTRA_ABSOLUTE . "config/_settings.php", 0777);
		$keyword = new keyword();
		$fileName = time() .'-'.  date('Y-m-d') .'-'.$keyword->generate(6);
                $tempdir=CONFIG_PATH_EXTRA_ABSOLUTE . 'db_backup';
                if (!file_exists($tempdir) && !is_dir($tempdir)) { 
                    mkdir($tempdir);
                }
		$handle = fopen(CONFIG_PATH_EXTRA_ABSOLUTE . 'db_backup/' . $fileName . '.sql','w+');
               // var_dump($handle);exit;
		//chmod(CONFIG_PATH_EXTRA_ABSOLUTE . "config/_settings.php", 0666);
		fwrite($handle,$return);
		fclose($handle);
	}
	
	header("location:" . CONFIG_PATH_SITE_ADMIN . "utl_db_backup.html?reply=" . urlencode('repl_done'));
        
        // for push
?>