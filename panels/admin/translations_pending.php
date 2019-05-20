<?php

defined("_VALID_ACCESS") or die("Restricted Access");

$admin = new admin();

$mysql = new mysql();



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
				//echo 'Word ---------- '.$word.PHP_EOL;
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
	

	$sqlForLang = 'select * from ' . LANG_MASTER ;

	$queryLangData = $mysql->query($sqlForLang);

	$langCount = $mysql->rowCount($queryLangData);

	$langData = $mysql->fetchArray($queryLangData);

	

	foreach($langData as $lData){

		$lShotArray[$lData['language_code']] = $lData['language'];

	}

	/*$pendingWordList = array();

	foreach($finalFileList as $fileName){

		if(!is_readable(CONFIG_PATH_ADMIN_ABSOLUTE.$fileName)){

			echo 'File is not readable '.$fileName;

			exit;

		}

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

	

//die;

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





?>

<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),'Settings'); ?></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_translations')); ?></li>

        </ol>

    </div>

</div>



<div class="m-t-10">

	<h4 class="m-b-20">

		<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Pending_Translation')); ?>

        <a href="#" class="btn btn-sm btn-warning pull-right btnTranslateAll">

        	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Translate_All_words')); ?>

        </a>

    </h4>

    

    <table id="transTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">

        <thead>

            <tr>

                <th><?php echo $admin->wordTrans($admin->getUserLang(),'Sr'); ?></th>

                <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_word')); ?></th>

                <th><?php echo $admin->wordTrans($admin->getUserLang(),'Action'); ?></th>

            </tr>

        </thead>

        <tbody>

        	<?php

			$i = 1;

			if(count($finalWords) > 0){

				foreach($finalWords as $key=>$val){

			?>

            <tr>

                <td><?php echo $i++; ?></td>

                <td><?php echo $key; ?></td>

                <td>

					<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations_edit.html" class="btn btn-sm btn-info btnEditTranslation" data-word="<?php echo $key; ?>"><i class="fa fa-pencil"></i></a>

                </td>

            </tr>

            <?php

				}

			}

			?>

        </tbody>

    </table>

</div>



<div id="addNewTranslationModal" class="modal fade" role="dialog">

	<div class="modal-dialog">

    <!-- Modal content-->

    	<div class="modal-content">

        	<div class="modal-header">

            	<button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title">

					<?php echo $admin->wordTrans($admin->getUserLang(),'Translation'); ?>

                    <?php

					if(CONFIG_TRANS_GOOGLE_KEY != ""){

					?>

                    <button type="button" class="btn btn-sm btn-warning m-l-10 btnTransAuto"><?php echo $admin->wordTrans($admin->getUserLang(),'Auto Translate'); ?></button>

                    <?php } ?>

                </h4>

       	  </div>

            <div class="editTransResp"></div>

        </div>

    </div>

</div>



<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" charset="utf-8">

	var english = {

		"sLengthMenu": "<?php echo $admin->wordTrans($admin->getUserLang(),'Display'); ?> _MENU_ <?php echo $admin->wordTrans($admin->getUserLang(),'records per page'); ?>",

		"sZeroRecords": "<?php echo $admin->wordTrans($admin->getUserLang(),'Nothing found - sorry'); ?>",

		"sInfo": "<?php echo $admin->wordTrans($admin->getUserLang(),'Showing'); ?> _START_ <?php echo $admin->wordTrans($admin->getUserLang(),'to'); ?> _END_ <?php echo $admin->wordTrans($admin->getUserLang(),'of'); ?> _TOTAL_ <?php echo $admin->wordTrans($admin->getUserLang(),'records'); ?>",

		"sInfoEmpty": "<?php echo $admin->wordTrans($admin->getUserLang(),'Showing'); ?> 0 <?php echo $admin->wordTrans($admin->getUserLang(),'to'); ?> 0 <?php echo $admin->wordTrans($admin->getUserLang(),'of'); ?> 0 <?php echo $admin->wordTrans($admin->getUserLang(),'records'); ?>",

		"sInfoFiltered": "(<?php echo $admin->wordTrans($admin->getUserLang(),'filtered from'); ?> _MAX_ <?php echo $admin->wordTrans($admin->getUserLang(),'total records'); ?>)",

		"sSearch": "<?php echo $admin->wordTrans($admin->getUserLang(),'Search:'); ?>",

	};

	

	var currentLang = english;

	$(document).ready(function() {

		var dtable = jQuery('#transTable').DataTable({

			"oLanguage": english

		});

	});

</script>

<script>

$(document).ready(function(e) {	

	//################### auto translate ###################//

	$(document).on('click','.btnTransAuto',function(e){

		e.preventDefault();

		var _this = $(this);

		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations_add_process.do';

		var _isAuto = 1;

		var _oWord = $('.oriWord').val();

		console.log(_oWord);

		if(_oWord != ""){

			_this.text('<?php echo $admin->wordTrans($admin->getUserLang(),'Translating...'); ?>');

			$.ajax({

				url: _url,

				data: {	word: _oWord,isAuto:_isAuto},

				type: "POST",

				dataType : "json",

			}).done(function( resp ) {

				var JSONString = resp;

				var JSONString = JSON.stringify(JSONString);

				var JSONObject = JSON.parse(JSONString);

				

				$.each(JSONObject, function (index, value) {

				  $('input[data-tranlang="'+value['lang']+'"]').val(value['translatedText']);

				});

				_this.text('<?php echo $admin->wordTrans($admin->getUserLang(),'Auto Translate'); ?>');

			}).fail(function( xhr, status, errorThrown ) {

			}).always(function( xhr, status ) {

			});

		}else{

			alert('<?php echo $admin->wordTrans($admin->getUserLang(),'Please fill original word.'); ?>');

		}

	});

	

	$('#addNewTranslationModal').on('hidden.bs.modal', function (e) {

	  $('.frmAddTranslation')[0].reset();

	  $('.editTransResp').html('');

	  $('.transBefore').show();

	})

	

	$(document).on('click','.btnEditTranslation',function(e){

		e.preventDefault();

		var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations_edit_process.do';

		var _word = $(this).data('word');

		var isEdit = 1;

		$.ajax({

			url: _url,

			data: {	word: _word,isEdit:isEdit},

			type: "POST",

			dataType : "json",

		}).done(function( resp ) {

			if(resp.status == 1){

				$('.transBefore').hide();

				$('.editTransResp').html(resp.result);

				$('#addNewTranslationModal').modal();

			}else{

				alert('<?php echo $admin->wordTrans($admin->getUserLang(),'Something went wrong.'); ?>');

			}

		}).fail(function( xhr, status, errorThrown ) {

		}).always(function( xhr, status ) {

		});

	});

	

	$(document).on('click','.btnTranslateAll',function(e){

		e.preventDefault();

		if(confirm("Are you sure want to translate all words using Google Translation APIs ?")){

			var _this = $(this);

			var _url = '<?php echo CONFIG_PATH_SITE_ADMIN; ?>translations_add_process.do';

			var _isAllTrans = 1;

			_this.html('<i class="fa fa-refresh fa-spin"></i> <?php echo $admin->wordTrans($admin->getUserLang(),'Translating all...'); ?>');

			$.ajax({

				url: _url,

				data: {	isAllTrans: _isAllTrans},

				type: "POST",

				dataType : "json",

			}).done(function( resp ) {

				if(resp.status == 1){

					//location.reload();

				}else{

					alert('<?php echo $admin->wordTrans($admin->getUserLang(),'Something went wrong!'); ?>');

				}

				

				_this.html('<?php echo $admin->wordTrans($admin->getUserLang(),'Translate All Words'); ?>');

			}).fail(function( xhr, status, errorThrown ) {

			}).always(function( xhr, status ) {

			});

		}

	});

});

</script>