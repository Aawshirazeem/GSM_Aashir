<?php
	$jsEasyPieChart = '1';

	/*************************************************************
								IMEI Stats
	**************************************************************/

	//cal todays total
	$sqlImeiToday = 'SELECT status, count(id) as total FROM ' . ORDER_IMEI_MASTER . ' where date(date_time)=CURDATE() group by status';
	$result = $mysql->getResult($sqlImeiToday);

	$imeiTodayNew = $imeiTodayAccepted = $imeiTodayAvail = $imeiTodayRejected =0;
	if($result['COUNT'] > 0){
		foreach($result['RESULT'] as $rowTotal){
			switch($rowTotal['status']){
				case '0':
					$imeiTodayNew = $rowTotal['total'];
					break;
				case '1':
					$imeiTodayAccepted = $rowTotal['total'];
					break;
				case '2':
					$imeiTodayAccepted = $rowTotal['total'];
					break;
				case '3':
					$imeiTodayRejected = $rowTotal['total'];
					break;
			}
		}
	}


	//IMEI:Today Income
	$sqlImeiTodayIncome = 'select sum(credits-credits_purchase) as imeiTodayIncome from '.ORDER_IMEI_MASTER .'  where status = 2 and date(date_time)=CURDATE() group by status';
	$resultTotayIncome = $mysql->getResult($sqlImeiTodayIncome);
	$imeiTodayIncome = 0;
	if($resultTotayIncome['COUNT'] > 0)
	{
		$rowsImeiTodayIncome = $resultTotayIncome['RESULT'];
		$imeiTodayIncome = $rowsImeiTodayIncome[0]['imeiTodayIncome'];
	}


	//IMEI:Yesterday
	$sqlImeiYesterday ='SELECT
								count(case when status=2 then id end) as imeiYesterdayAvail, 
								count(case when status=3 then id end) as imeiYesterdayRejected
							FROM ' . ORDER_IMEI_MASTER . ' 
							where date(date_time) = date(date_sub(CURDATE(), interval 1 day))';

	$sqlImeiYesterday ='SELECT count(id) as total, status FROM ' . ORDER_IMEI_MASTER . ' where date(date_time) = date(date_sub(CURDATE(), interval 1 day)) group by status';
	$resultImeiYesterday = $mysql->getResult($sqlImeiYesterday);
	$imeiYesterdayAvail = $imeiYesterdayRejected = 0;
	if($resultImeiYesterday['COUNT'] > 0)
	{
		foreach($resultImeiYesterday['RESULT'] as $rowTotal){
			switch($rowTotal['status']){
				case '2':
					$imeiYesterdayAvail = $rowTotal['total'];
					break;
				case '3':
					$imeiYesterdayRejected = $rowTotal['total'];
					break;
			}
		}
	}




	//IMEI:Month
	$sqlImeiMonth ='SELECT count(id) as total, status FROM ' . ORDER_IMEI_MASTER . ' where month(date_time) = month(CURDATE()) group by status';
	$resultImeiMonth = $mysql->getResult($sqlImeiMonth);
	$imeiYesterdayAvail = $imeiMonthRejected = 0;
	if($resultImeiMonth['COUNT'] > 0)
	{
		foreach($resultImeiMonth['RESULT'] as $rowTotal){
			switch($rowTotal['status']){
				case '2':
					$imeiMonthAvail = $rowTotal['total'];
					break;
				case '3':
					$imeiMonthRejected = $rowTotal['total'];
					break;
			}
		}
	}


	//IMEI:Year
	$sqlImeiYear ='SELECT count(id) as total, status FROM ' . ORDER_IMEI_MASTER . ' where year(date_time) = year(CURDATE()) group by status';
	$resultImeiYear = $mysql->getResult($sqlImeiYear);
	$imeiYesterdayAvail = $imeiYearRejected = 0;
	if($resultImeiYear['COUNT'] > 0)
	{
		foreach($resultImeiYear['RESULT'] as $rowTotal){
			switch($rowTotal['status']){
				case '2':
					$imeiYearAvail = $rowTotal['total'];
					break;
				case '3':
					$imeiYearRejected = $rowTotal['total'];
					break;
			}
		}
	}

	// Calc Percentage
	$imeiPer = 0;
	if($imeiYearRejected == 0)
	{
		$imeiPer = ($imeiYearAvail > 0) ? 100 : 0;
	}
	else
	{
		$imeiPer = (int)(($imeiYearAvail/($imeiYearAvail + $imeiYearRejected)) * 100);
	}




	/*************************************************************
						File Server Stats
	**************************************************************/
	$sqlFileToday = 'SELECT count(id) as total, status FROM '.ORDER_FILE_SERVICE_MASTER .' where date(date_time)=CURDATE() group by status';
	$resultFileToday = $mysql->getResult($sqlFileToday);
	$fileTodayNew = $fileTodayAccepted = $fileTodayAvail = $fileTodayRejected =0;
	if($resultFileToday['COUNT'] > 0)
	{
		foreach($resultFileToday['RESULT'] as $rowTotal){
			switch($rowTotal['status']){
				case '0':
					$fileTodayNew = $rowTotal['total'];
					break;
				case '1':
					$fileTodayAccepted = $rowTotal['total'];
					break;
				case '2':
					$fileTodayAvail = $rowTotal['total'];
					break;
				case '3':
					$fileTodayRejected = $rowTotal['total'];
					break;
			}
		}
	}

	//File:Today Income
	$sqlFileTodayIncome = 'select sum(credits-credits_purchase) as fileTodayIncome
								from '.ORDER_FILE_SERVICE_MASTER .' 
								where status = 2 and date(date_time)=CURDATE()';
	$queryFileTodayIncome = $mysql->query($sqlFileTodayIncome);
	$fileTodayIncome = 0;
	if($mysql->rowCount($queryFileTodayIncome) > 0)
	{
		$rowsFileTodayIncome = $mysql->fetchArray($queryFileTodayIncome);
		$fileTodayIncome = $rowsFileTodayIncome[0]['fileTodayIncome'];
	}

	//File:Yesterday
	$sqlFileYesterday ='SELECT
								count(id) as total, status
							FROM ' . ORDER_FILE_SERVICE_MASTER . ' 
							where date(date_time) = date(date_sub(CURDATE(), interval 1 day))
							group by status';
	$resultFileYesterday = $mysql->getResult($sqlFileYesterday);
	$fileYesterdayAvail = $fileYesterdayRejected = 0;
	if($resultFileYesterday['COUNT'] > 0)
	{
		foreach($resultFileToday['RESULT'] as $rowTotal){
			switch($rowTotal['status']){
				case '2':
					$fileYesterdayAvail = $rowTotal['total'];
					break;
				case '3':
					$fileYesterdayRejected = $rowTotal['total'];
					break;
			}
		}
	}

	//File:Month
	$sqlFileMonth ='SELECT
								count(id) as total, status
							FROM ' . ORDER_FILE_SERVICE_MASTER . '
							where month(date_time) = month(CURDATE())
							group by status';
	$resultFileMonth = $mysql->getResult($sqlFileMonth);
	$fileYesterdayAvail = $fileMonthRejected = 0;
	if($resultFileMonth['COUNT'] > 0)
	{
		foreach($resultFileMonth['RESULT'] as $rowTotal){
			switch($rowTotal['status']){
				case '2':
					$fileMonthAvail = $rowTotal['total'];
					break;
				case '3':
					$fileMonthRejected = $rowTotal['total'];
					break;
			}
		}
	}

	//File:Year
	$sqlFileYear ='SELECT
								count(id) as total, status
							FROM ' . ORDER_FILE_SERVICE_MASTER . '
							where year(date_time) = year(CURDATE())
							group by status';
	$resultFileYear = $mysql->getResult($sqlFileMonth);
	$fileYearAvail = $fileYearRejected = 0;
	if($resultFileYear['COUNT'] > 0)
	{
		foreach($resultFileYear['RESULT'] as $rowTotal){
			switch($rowTotal['status']){
				case '2':
					$fileYearAvail = $rowTotal['total'];
					break;
				case '3':
					$fileYearRejected = $rowTotal['total'];
					break;
			}
		}
	}

	// Calc Percentage
	$filePer = 0;
	if($fileYearRejected == 0)
	{
		$filePer = ($fileYearAvail > 0) ? 100 : 0;
	}
	else
	{
		$filePer = (int)(($fileYearAvail/($fileYearAvail + $fileYearRejected)) * 100);
	}
	/*************************************************************
							End: File Server Stats
	**************************************************************/



?>