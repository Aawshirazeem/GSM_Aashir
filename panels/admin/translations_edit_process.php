<?php
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

if(isset($_POST['isDelete']) && $_POST['isDelete'] == 1){
	$word = $_POST['word'];
	$sql = 'delete from '.TRANSLATION_MASTER.' where ori_word = "'.$word.'"';
	$mysql->query($sql);
	
	$resp = array('status'=>1,'msg'=>'translation successfully deleted.');
	echo json_encode($resp);
	die;
}

if(isset($_POST['isEdit']) && $_POST['isEdit'] == 1){
	$word = $_POST['word'];
	$sql = 'select * from '.TRANSLATION_MASTER.' where ori_word = "'.$word.'"';
	$query = $mysql->query($sql);
	$rows = $mysql->fetchArray($query);
	
	$ids = array();
	$langCode = array();
	foreach($rows as $row){
		$ids[] = $row['id'];
		$langCode[] = $row['lang_code'];
	}
	$ids = implode(',',$ids);
	$langCodes = implode(',',$langCode);
	$singleRow = $rows[0];
	
	$result = '<form name="frmEditTranslation" id="frmEditTranslation" class="frmEditTranslation" action="'. CONFIG_PATH_SITE_ADMIN .'translations_edit_process.do" method="post">
					<input type="hidden" name="hdnIds" class="hdnIds" id="hdnIds" value="'.$ids.'"/>
					<input type="hidden" name="hdnLangCodes" class="hdnLangCodes" id="hdnLangCodes" value="'.$langCodes.'"/>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>'.$admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_word')).' </label>
                                    <input name="oriWord" type="text" class="form-control oriWord" id="oriWord" value="'.$word.'" autocomplete="off" required />
                                </div>';
								$languageSql= 'select * from ' . LANG_MASTER . ' where lang_status = 1' ;
								$queryLang = $mysql->query($languageSql);
								$langList = $mysql->fetchArray($queryLang);
								$i = 0;
                                foreach($langList as $lang){
									$sqlSingleLang = 'select * from '.TRANSLATION_MASTER.' where ori_word = "'.$word.'" and lang_code = "'.$lang['language_code'].'"';
									//echo $sqlSingleLang;
									//die;
									$query = $mysql->query($sqlSingleLang);
									$sLangData = $mysql->fetchArray($query);
                     $result .= '<div class="form-group">
                                    <label>'.$lang['language'].'</label>
                                    <input name="tranWord[]" type="text" class="form-control tranWord" id="tranWord" value="'.$sLangData[0]['tran_word'].'" data-tranlang="'.$lang['language_code'].'" autocomplete="off" required />
                                </div>';
								$i++;
                                }
               $result .= '</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btnSavePage btnBeforeClick" name="btnSavePage">'.$admin->wordTrans($admin->getUserLang(),'Save').'</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">'.$admin->wordTrans($admin->getUserLang(),'Close').'</button>
                    </div>
                </form>';
	
	$resp = array('status'=>1,'result'=>$result);
	echo json_encode($resp);
	die;
}

/*if(isset($_POST['langCode']) && $_POST['langCode'] != "" && isset($_POST['oriWord']) && $_POST['oriWord'] != "" && isset($_POST['tranWord']) && $_POST['tranWord'] != ""){
	$id = $_POST['hdnUpdateId'];
	$langCode = $_POST['langCode'];
	$oriWord = $_POST['oriWord'];
	$tranWord = $_POST['tranWord'];
	
	$sql = 'update ' . TRANSLATION_MASTER . ' set lang_code = "' . $langCode . '", ori_word = "'.$oriWord.'", tran_word = "'.$tranWord.'" where id = ' . $id;
	$mysql->query($sql);

	header("location:" . CONFIG_PATH_SITE_ADMIN . "translations.html?reply=" . urlencode('lbl_translations_update'));
	exit();
}else{
	header("location:" . CONFIG_PATH_SITE_ADMIN . "translations.html?reply=" . urlencode('lbl_translations_updating_failed'));
	exit();
}*/

if(isset($_POST['oriWord']) && $_POST['oriWord'] != ""){
	$ids = explode(',',$_POST['hdnIds']);
	$langCodes = explode(',',$_POST['hdnLangCodes']);
	$oriWord = $_POST['oriWord'];
	$tranWord = $_POST['tranWord'];
	
	$languageSql= 'select * from ' . LANG_MASTER;
	$queryLang = $mysql->query($languageSql);
	$langList = $mysql->fetchArray($queryLang);
	
	$i = 0;
	foreach($langList as $lang){
		if(in_array($lang['language_code'],$langCodes)){
			//$sql = 'update ' . TRANSLATION_MASTER . ' set ori_word = "' . $oriWord . '", tran_word = "'.$tranWord[$i].'" where ori_word = "' . $oriWord . '" AND lang_code = "'.$lang['language_code'].'"';
			$sql = 'update ' . TRANSLATION_MASTER . ' set ori_word = "' . $oriWord . '", tran_word = "'.$tranWord[$i].'" where id = ' . $ids[$i];
			$mysql->query($sql);
		}else{
			$sql = 'insert into '. TRANSLATION_MASTER.' (lang_code,ori_word,tran_word) values("'.$lang['language_code'].'","'.$oriWord.'","'.$tranWord[$i].'")';
			$mysql->query($sql);
		}
		$i++;
	}
	
	/*if(count($tranWord) != count($ids)){
		
	}else{
		$i = 0;
		foreach($ids as $id){
			$sql = 'update ' . TRANSLATION_MASTER . ' set ori_word = "' . $oriWord . '", tran_word = "'.$tranWord[$i].'" where id = ' . $id;
			$mysql->query($sql);
			$i++;
		}
	}*/
	header("location:" . CONFIG_PATH_SITE_ADMIN . "translations.html?reply=" . urlencode('lbl_translations_update'));
	exit();
}else{
	header("location:" . CONFIG_PATH_SITE_ADMIN . "translations.html?reply=" . urlencode('lbl_translations_updating_failed'));
	exit();
}
?>
