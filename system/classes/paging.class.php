<?php
class paging

{



	function recordsetNav($db_query,$page_url,$offset=0,$limit=0,$extraurl='',$blocksize=10)

	{

		$mysql = new mysql();
		$admin = new admin();

		$tablewidth = "100%";

		$verbiage=false;

		$firstid = 0;

		$tdcheck=0;

	

		//$db_result = @mysql_query($db_query,$db_connect);

		$db_result = $mysql->query($db_query);

		

		//$totalrecords = @mysql_num_rows($db_result);

		$totalrecords = $mysql->rowCount($db_result);

		

		$pagenumber = intval(($offset + $limit) / $limit);

		$totalpages = intval($totalrecords/$limit);

		if ($totalrecords%$limit > 0) // partial page

		{

			$lastpage = $totalpages * $limit;

			$totalpages++;

		} else {

			$lastpage = ($totalpages - 1) * $limit;

		}

		$navstring  = '<div class="clearfix"></div>';

		$navstring  .= '<nav><ul class="pagination pagination">';

	

		// start building navigation string

		if ($totalrecords > $limit) // only show <<PREV NEXT>> row if $totalrecords is greater than $limit

		{

			if ($offset != 0)

			{

				$navstring .= "<li class='page-item'><a title='First Page' class='paging page-link tab-current' href='".$page_url."?offset=0$extraurl'><b>".$admin->wordTrans($admin->getUserLang(),"First")."</b></a></li>";

				$navstring .= "<li class='page-item'><a title='Previous Page' class='paging page-link tab-current' href='".$page_url."?offset=".($offset-$limit)."$extraurl'><b>&laquo;</b></a></li>";

				$firstid=1;

			}

			

			if ($totalpages < $blocksize)

			{

				$blocksize = $totalpages;

				$firstpage = 1;

			} elseif ($pagenumber > $blocksize) {

				$firstpage = ($pagenumber-$blocksize) + 2;

			} elseif ($pagenumber == $blocksize) {

				$firstpage = 2;

			} else {

				$firstpage = 1;

			}

	

			$blocklimit = $blocksize + $firstpage;

	

			// Page numbers

			for ($i=$firstpage;$i<$blocklimit;$i++)

			{

				if ($i == $pagenumber)

				{

					$navstring .= '<li class="page-item pageactive"><a href="#" class="page-link">' . $i . '</a></li>';

				} else {

					if ($i <= $totalpages)

					{

						$nextoffset = $limit * ($i-1);

						$navstring .= "<li class='page-item'><a title='Page ". $i ." of ". $totalpages ."' class='paging page-link tab-current' href='".$page_url."?offset=".$nextoffset."$extraurl'>$i</a></li>";

					}

				}

			}

	

			if($totalrecords-$offset > $limit)

			{

				$lastid=1;

				$navstring .= "<li class='page-item'><a title='Next Page' class='paging page-link tab-current' href='".$page_url."?offset=".($offset+$limit)."$extraurl'><b>&raquo;</b></a></li>";

				$navstring .= "<li class='page-item'><a title='Last Page' class='paging page-link tab-current' href='".$page_url."?offset=".$lastpage."$extraurl'><b>".$admin->wordTrans($admin->getUserLang(),"Last")."</b></a></li>";

				if ($offset == 0 )

				{

					$navstring .= "</ul>";

				}

				$tdcheck=1;

			}

		}

	

		if ($verbiage)

		{

			if($firstid!=1 && $lastid!=1)

			{

			$navstring .= "</ul></nav>";

			}

			if($firstid==1)

			{

			$navstring .= "</ul></nav>";

			}

			$navstring .= '<div class="divSearch">';

			$navstring .= "<span class='paging'>".$admin->wordTrans($admin->getUserLang(),"Page(s)").": <b>".$pagenumber."</b>/<b>".$totalpages."</b>";

			$navstring .= "&nbsp;&nbsp; ".$admin->wordTrans($admin->getUserLang(),"Total Records").": <b>".$totalrecords."</b>";

			$navstring .= "</div>";

	

		}

		

		

		if ($firstid==1)

		{

			$navstring .= "</ul>";

		}

		

		if ($tdcheck!=1 && $firstid!=1)

		{

			$navstring .= "</ul>";

		}

		

		

		$navstring  .= '<div class="CL"></div>';

		return $navstring;

	}

}

?>