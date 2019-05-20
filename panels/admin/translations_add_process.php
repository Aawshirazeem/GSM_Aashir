<?php



if (!defined("_VALID_ACCESS")) {

    define("_VALID_ACCESS", 1);

    require_once("../../_init.php");

}



/*if(isset($_POST['langCode']) && $_POST['langCode'] != "" && isset($_POST['oriWord']) && $_POST['oriWord'] != "" && isset($_POST['tranWord']) && $_POST['tranWord'] != ""){

	

	$langCode = $_POST['langCode'];

	$oriWord = $_POST['oriWord'];

	$tranWord = $_POST['tranWord'];



	$sql = 'insert into '. TRANSLATION_MASTER.' (lang_code,ori_word,tran_word) values("'.$langCode.'","'.$oriWord.'","'.$tranWord.'")';

	$mysql->query($sql);



	header("location:" . CONFIG_PATH_SITE_ADMIN . "translations.html?reply=" . urlencode('lbl_translations_add'));

	exit();

}else{

	header("location:" . CONFIG_PATH_SITE_ADMIN . "translations.html?reply=" . urlencode('lbl_translations_adding_failed'));

	exit();

}*/



/***** google api to transalte word *******/

if(isset($_POST['isAuto'])){

	$apiKey = CONFIG_TRANS_GOOGLE_KEY;



	$text = $_POST['word'];

	

	$sql= 'select * from ' . LANG_MASTER ;

    $query = $mysql->query($sql);

	$langList = $mysql->fetchArray($query);

	

	$autoTransArray = array();

	

	foreach($langList as $lang){

		if($lang['language_code'] == 'cn'){

			$lang['language_code'] = 'zh';

		}elseif($lang['language_code'] == 'se'){

			$lang['language_code'] = 'sv';

		}

		$url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($text) . '&source=en&target='.$lang['language_code'];

		

		$handle = curl_init($url);

		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);//We want the result to be saved into variable, not printed out

		$response = curl_exec($handle);                         

		curl_close($handle);

		

		$result = json_decode($response, true);

		$translatedWord = $result['data']['translations'][0];

		if($lang['language_code'] == 'en'){

			$translatedWord['translatedText'] = $text;

		}

		

		if($lang['language_code'] == 'zh'){

			$lang['language_code'] = 'cn';

		}elseif($lang['language_code'] == 'sv'){

			$lang['language_code'] = 'se';

		}

		$autoTransArray[] = array('lang'=>$lang['language_code'],'translatedText'=>$translatedWord['translatedText']);

	}

	/*if(count($autoTransArray) > 0){

		$autoTransArray['status'] = 1;

	}else{

		$autoTransArray['status'] = 0;

	}*/

	echo json_encode($autoTransArray);

	die;

}

/****************** end ***********************/

if(isset($_GET['q'])){

	$files = scandir(CONFIG_PATH_SITE_ABSOLUTE.CONFIG_PANEL_ALL);

	

	$files = array_diff($files, array('.', '..'));

	$finalFileList = array_values($files);

	

	

	$pendingWordList = array();

	foreach($finalFileList as $fList){

		if($fList == 'chat' || $fList == 'shop'){

			continue;

		}

		echo CONFIG_PATH_SITE_ABSOLUTE.CONFIG_PANEL_ALL.$fList.'/'."<br/>";

		$files = scandir(CONFIG_PATH_SITE_ABSOLUTE.CONFIG_PANEL_ALL.$fList.'/');

	

		$files = array_diff($files, array('.', '..'));

		$finalFileList = array_values($files);

		foreach($finalFileList as $fileName){

			$fileData = file_get_contents(CONFIG_PATH_SITE_ABSOLUTE.CONFIG_PANEL_ALL.$fList.'/'.$fileName);

			//$fileData = file_get_contents(CONFIG_PATH_SITE_ABSOLUTE.CONFIG_PANEL_ALL.$fileName);

			

		

			preg_match_all('/\$admin\-\>wordTrans\((.*?)\);/' , $fileData , $matches );

			$matches = $matches[1];

			foreach($matches as $m){

				//echo 'From Before explode ---------- '.$m.PHP_EOL;

				$word = explode(',',$m);

				$word = $word[1];

				//echo 'From Before in Cond ---------- '.$word.PHP_EOL;

				if(strpos($word,'$lang->prints') !== false){

					preg_match('/\$lang\-\>prints\((.*)\)/' , $word , $wmatch );

					$word = substr($wmatch[1], 1, -1);

					$word = $lang->prints($word);

					//echo 'From if --- '.$word.PHP_EOL;

				}elseif(strpos($word,'$lang->get') !== false){

					preg_match('/\$lang\-\>get\((.*)\)/' , $word , $wmatch );

					$word = substr($wmatch[1], 1, -1);

					$word = $lang->get($word);

					//echo 'From else if --- '.$word.PHP_EOL;

				}else{

					$word = substr($word, 1, -1);

					//echo 'From else --- '.$word.PHP_EOL;

				}

				//echo $fileName.' --- > '.$word.PHP_EOL;

				$pendingWordList[] = $word;

				

			}

		}

	}

}

if(isset($_POST['isScan'])){
	/*$files = scandir(CONFIG_PATH_ADMIN_ABSOLUTE);
	$files = array_diff($files, array('.', '..'));
	$finalFileList = array_values($files);*/	

	$files = scandir(CONFIG_PATH_SITE_ABSOLUTE.CONFIG_PANEL_ALL);
	$files = array_diff($files, array('.', '..'));
	$finalFileList = array_values($files);	

	$pendingWordList = array();
	foreach($finalFileList as $fList){
		if($fList == 'chat' || $fList == 'shop'){
			continue;
		}
		//echo CONFIG_PATH_SITE_ABSOLUTE.CONFIG_PANEL_ALL.$fList.'/'."<br/>";
		$files = scandir(CONFIG_PATH_SITE_ABSOLUTE.CONFIG_PANEL_ALL.$fList.'/');
		$files = array_diff($files, array('.', '..'));
		$finalFileList = array_values($files);
		foreach($finalFileList as $fileName){
			$fileData = file_get_contents(CONFIG_PATH_SITE_ABSOLUTE.CONFIG_PANEL_ALL.$fList.'/'.$fileName);
			preg_match_all('/\$admin\-\>wordTrans\((.*?)\);/' , $fileData , $matches );
			$matches = $matches[1];
			
			foreach($matches as $m){
				//echo 'From Before explode ---------- '.$m.PHP_EOL;
				$word = explode(',',$m);
				$word = $word[1];
				//echo 'From Before in Cond ---------- '.$word.PHP_EOL;
				if(strpos($word,'$lang->prints') !== false){
					preg_match('/\$lang\-\>prints\((.*)\)/' , $word , $wmatch);
					$word = substr($wmatch[1], 1, -1);
				//	echo 'Word ---------- '.$word.PHP_EOL;
					$word = $lang->prints($word);
					//echo 'From if --- '.$word.PHP_EOL;
				}elseif(strpos($word,'$lang->get') !== false){
					preg_match('/\$lang\-\>get\((.*)\)/' , $word , $wmatch );
					$word = substr($wmatch[1], 1, -1);
					$word = $lang->get($word);
					//echo 'From else if --- '.$word.PHP_EOL;
				}else{
					$word = substr($word, 1, -1);
					//echo 'From else --- '.$word.PHP_EOL;
				}
				//echo $fileName.' --- > '.$word.PHP_EOL;
				if($word != ""){
					$pendingWordList[] = $word;
				}
			}
		}
	}	
	//die;

	$sqlForLang = 'select * from ' . LANG_MASTER ;

	$queryLangData = $mysql->query($sqlForLang);

	$langCount = $mysql->rowCount($queryLangData);

	$langData = $mysql->fetchArray($queryLangData);

	

	foreach($langData as $lData){

		$lShotArray[$lData['language_code']] = $lData['language'];

	}

	

	/*$pendingWordList = array();

	foreach($finalFileList as $fileName){

		$fileData = file_get_contents(CONFIG_PATH_ADMIN_ABSOLUTE.$fileName);	

		preg_match_all('/\$admin\-\>wordTrans\((.*?)\);/' , $fileData , $matches );

		$matches = $matches[1];

		foreach($matches as $m){

			//echo 'From Before explode ---------- '.$m.PHP_EOL;

			$word = explode(',',$m);

			$word = $word[1];

			//echo 'From Before in Cond ---------- '.$word.PHP_EOL;

			if(strpos($word,'$lang->prints') !== false){

				preg_match('/\$lang\-\>prints\((.*)\)/' , $word , $wmatch );

				$word = substr($wmatch[1], 1, -1);

				$word = $lang->prints($word);

				//echo 'From if --- '.$word.PHP_EOL;

			}elseif(strpos($word,'$lang->get') !== false){

				preg_match('/\$lang\-\>get\((.*)\)/' , $word , $wmatch );

				$word = substr($wmatch[1], 1, -1);

				$word = $lang->get($word);

				//echo 'From else if --- '.$word.PHP_EOL;

			}else{

				$word = substr($word, 1, -1);

				//echo 'From else --- '.$word.PHP_EOL;

			}

			//echo $fileName.' --- > '.$word.PHP_EOL;

			$pendingWordList[] = $word;

			

		}

	}*/

	

	$pendingWordList = array_unique($pendingWordList);	

	

	/******** check word available in database *************/

	foreach($pendingWordList as $pWord){

		$wordSql = 'select * from ' . TRANSLATION_MASTER . ' where ori_word = '.$mysql->quote($pWord) ;

		$queryData = $mysql->query($wordSql);

		$dataCount = $mysql->rowCount($queryData);

		$wordData = $mysql->fetchArray($queryData);

		$tWordsArray = array();

		foreach($wordData as $wData){

			$tWordsArray[] = $wData['lang_code'];

		}

		foreach($lShotArray as $key=>$val){

			if(!in_array($key,$tWordsArray)){

				$finalWords[$pWord][] = $val;

			}

		}

	}

	

	$resp = array('status'=>1,'pWordCount'=>count($finalWords));

	echo json_encode($resp);

	die;

}



if(isset($_POST['isAllTrans'])){

	$apiKey = CONFIG_TRANS_GOOGLE_KEY;

	

	/*$files = scandir(CONFIG_PATH_ADMIN_ABSOLUTE);

	

	$files = array_diff($files, array('.', '..'));

	$finalFileList = array_values($files);*/

	

	$files = scandir(CONFIG_PATH_SITE_ABSOLUTE.CONFIG_PANEL_ALL);

	

	$files = array_diff($files, array('.', '..'));

	$finalFileList = array_values($files);

	

	

	$pendingWordList = array();

	foreach($finalFileList as $fList){

		if($fList == 'chat' || $fList == 'shop'){

			continue;

		}

		//echo CONFIG_PATH_SITE_ABSOLUTE.CONFIG_PANEL_ALL.$fList.'/'."<br/>";

		$files = scandir(CONFIG_PATH_SITE_ABSOLUTE.CONFIG_PANEL_ALL.$fList.'/');

	

		$files = array_diff($files, array('.', '..'));

		$finalFileList = array_values($files);

		foreach($finalFileList as $fileName){

			$fileData = file_get_contents(CONFIG_PATH_SITE_ABSOLUTE.CONFIG_PANEL_ALL.$fList.'/'.$fileName);

			//$fileData = file_get_contents(CONFIG_PATH_SITE_ABSOLUTE.CONFIG_PANEL_ALL.$fileName);

			

		

			preg_match_all('/\$admin\-\>wordTrans\((.*?)\);/' , $fileData , $matches );

			$matches = $matches[1];

			foreach($matches as $m){

				//echo 'From Before explode ---------- '.$m.PHP_EOL;

				$word = explode(',',$m);

				$word = $word[1];

				//echo 'From Before in Cond ---------- '.$word.PHP_EOL;

				if(strpos($word,'$lang->prints') !== false){

					preg_match('/\$lang\-\>prints\((.*)\)/' , $word , $wmatch );

					$word = substr($wmatch[1], 1, -1);

					$word = $lang->prints($word);

					//echo 'From if --- '.$word.PHP_EOL;

				}elseif(strpos($word,'$lang->get') !== false){

					preg_match('/\$lang\-\>get\((.*)\)/' , $word , $wmatch );

					$word = substr($wmatch[1], 1, -1);

					$word = $lang->get($word);

					//echo 'From else if --- '.$word.PHP_EOL;

				}else{

					$word = substr($word, 1, -1);

					//echo 'From else --- '.$word.PHP_EOL;

				}

				//echo $fileName.' --- > '.$word.PHP_EOL;

				$pendingWordList[] = $word;

				

			}

		}

	}

	

	$sqlForLang = 'select * from ' . LANG_MASTER ;

	$queryLangData = $mysql->query($sqlForLang);

	$langCount = $mysql->rowCount($queryLangData);

	$langData = $mysql->fetchArray($queryLangData);

	

	foreach($langData as $lData){

		$lShotArray[$lData['language_code']] = $lData['language'];

	}

	/*$pendingWordList = array();

	foreach($finalFileList as $fileName){

		$fileData = file_get_contents(CONFIG_PATH_ADMIN_ABSOLUTE.$fileName);

	

		preg_match_all('/\$admin\-\>wordTrans\((.*?)\);/' , $fileData , $matches );

		$matches = $matches[1];

		foreach($matches as $m){

			$word = explode(',',$m);

			$word = $word[1];

			if(strpos($word,'$lang->prints') !== false){

				preg_match('/\$lang\-\>prints\((.*)\)/' , $word , $wmatch );

				$word = substr($wmatch[1], 1, -1);

				$word = $lang->prints($word);

			}elseif(strpos($word,'$lang->get') !== false){

				preg_match('/\$lang\-\>get\((.*)\)/' , $word , $wmatch );

				$word = substr($wmatch[1], 1, -1);

				$word = $lang->get($word);

			}else{

				$word = substr($word, 1, -1);

			}

			$pendingWordList[] = $word;

		}

	}*/

	$pendingWordList = array_unique($pendingWordList);

	

	//print_r($pendingWordList);

	

	/******** check word available in database *************/

	foreach($pendingWordList as $pWord){

		$wordSql = 'select * from ' . TRANSLATION_MASTER . ' where ori_word = '.$mysql->quote($pWord) ;

		$queryData = $mysql->query($wordSql);

		$dataCount = $mysql->rowCount($queryData);

		$wordData = $mysql->fetchArray($queryData);

		

		$tWordsArray = array();

		foreach($wordData as $wData){

			$tWordsArray[] = $wData['lang_code'];

		}

		foreach($lShotArray as $key=>$val){

			if(!in_array($key,$tWordsArray)){

				//$finalWords[$pWord][] = $val;

				if($key == 'cn'){

					$key = 'zh';

				}elseif($key == 'se'){

					$key = 'sv';

				}

				$url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($pWord) . '&source=en&target='.$key;

				

				$handle = curl_init($url);

				curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);//We want the result to be saved into variable, not printed out

				$response = curl_exec($handle);                         

				curl_close($handle);

				

				$result = json_decode($response, true);

				$translatedWord = $result['data']['translations'][0];

				

				if($key == 'zh'){

					$key = 'cn';

				}elseif($key == 'sv'){

					$key = 'se';

				}

				

				if($pWord != ""){

					if($key == 'en'){

						$sql = 'insert into '. TRANSLATION_MASTER.' (lang_code,ori_word,tran_word) values('.$mysql->quote($key).','.$mysql->quote($pWord).','.$mysql->quote($pWord).')';

						$mysql->query($sql);

					}else{

						$sql = 'insert into '. TRANSLATION_MASTER.' (lang_code,ori_word,tran_word) values('.$mysql->quote($key).','.$mysql->quote($pWord).','.$mysql->quote($translatedWord['translatedText']).')';

						$mysql->query($sql);

					}

				}

			}

		}

	}

	$resp = array('status'=>1);

	echo json_encode($resp);

	die;

}



if(isset($_POST['oriWord']) && $_POST['oriWord'] != ""){	

	$oriWord = $_POST['oriWord'];	

	if(isset($_POST['tranWord']) && count($_POST['tranWord']) > 0){

		$sql= 'select * from ' . LANG_MASTER ;

		$query = $mysql->query($sql);

		if($mysql->rowCount($query) > 0){

			$rows = $mysql->fetchArray($query);

			$i = 0;

			foreach($rows as $row){

				$sql = 'insert into '. TRANSLATION_MASTER.' (lang_code,ori_word,tran_word) values("'.$row['language_code'].'","'.$oriWord.'","'.$_POST['tranWord'][$i].'")';

				$mysql->query($sql);

				$i++;

			}

		}

	}

	header("location:" . CONFIG_PATH_SITE_ADMIN . "translations.html?reply=" . urlencode('lbl_translations_add'));

	exit();

}else{

	header("location:" . CONFIG_PATH_SITE_ADMIN . "translations.html?reply=" . urlencode('lbl_translations_adding_failed'));

	exit();

}

?>

