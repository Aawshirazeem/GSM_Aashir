<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$admin = new admin();
	$mysql = new mysql();
	
?>
<div class="bg_red">
	<div class="container">
		<div class="thumb-pad9 wow fadeInUp" data-wow-delay="0.1s">
			<?php
				$sql= 'select rm.*, cm.countries_name as CountryName
								from ' . RESELLER_MASTER . ' rm 
								left join ' . COUNTRY_MASTER . ' cm on (rm.country = cm.id)
								where type=0 and rm.status=1
								order by reseller';
				$query = $mysql->query($sql);
				
				$i = 0;
				$groupName = "";
				if($mysql->rowCount($query) > 0)
				{
					echo '<h1 class="highlight">World Wide Distributor</h1>';
					$rows = $mysql->fetchArray($query);
					foreach($rows as $row)
					{
						echo '<div class="col-sm-6">';
							echo '<div class="panel">';
								echo '<div class="panel-heading"><b>' . $row['reseller'] . '</b></div>';
								echo '<div class="panel-body">';
									echo '<ul class="list-unstyled">';
									echo '<li><b>' . $lang->get('lbl_email') . ':</b> ' . $row['email'] . '</li>';
									echo '<li><b>' . $lang->get('lbl_address') . ':</b> ' . $row['address'] . '</li>';
									echo '<li><b>' . $lang->get('lbl_country') . ':</b> ' . $row['CountryName'] . '</li>';
									echo '<li><b>' . $lang->get('lbl_mobile') . ':</b> ' . $row['mobile'] . '</li>';
									echo '<li><b>' . $lang->get('lbl_phone') . ':</b> ' . $row['phone'] . '</li>';
									echo '<li><b>' . $lang->get('lbl_website') . ':</b> ' . $row['website'] . '</li>';
									echo '<li><b>' . $lang->get('lbl_yahoo') . ':</b> ' . $row['yahoo'] . '</li>';
									echo '<li><b>' . $lang->get('lbl_msn') . ':</b> ' . $row['msn'] . '</li>';
									echo '<li><b>' . $lang->get('lbl_skype') . ':</b> ' . $row['skype'] . '</li>';
									echo '<li><b>' . $lang->get('lbl_icq') . ':</b> ' . $row['icq'] . '</li>';
									echo '<li><b>' . $lang->get('lbl_sonork') . ':</b> ' . $row['sonork'] . '</li>';
									echo '</ul>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
					}
				}
			?>
			<div class="clearfix"></div>
			<?php
				$sql= 'select rm.*, cm.countries_name as CountryName
								from ' . RESELLER_MASTER . ' rm 
								left join ' . COUNTRY_MASTER . ' cm on (rm.country = cm.id)
								where type=1 and rm.status=1
								order by reseller';
				$query = $mysql->query($sql);
				
				$i = 0;
				$groupName = "";
				if($mysql->rowCount($query) > 0)
				{
					echo '<h1>Distributor</h1>';
					$rows = $mysql->fetchArray($query);
					foreach($rows as $row)
					{
						echo '<div class="col-sm-6">';
							echo '<div class="panel">';
								echo '<div class="panel-heading">' . $row['reseller'] . '</div>';
								echo '<div class="panel-body">';
									echo '<ul class="list-unstyled">';
										echo '<li><b>' . $lang->get('lbl_email') . ':</b> ' . $row['email'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_address') . ':</b> ' . $row['address'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_country') . ':</b> ' . $row['CountryName'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_mobile') . ':</b> ' . $row['mobile'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_phone') . ':</b> ' . $row['phone'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_website') . ':</b> ' . $row['website'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_yahoo') . ':</b> ' . $row['yahoo'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_msn') . ':</b> ' . $row['msn'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_skype') . ':</b> ' . $row['skype'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_icq') . ':</b> ' . $row['icq'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_sonork') . ':</b> ' . $row['sonork'] . '</li>';
									echo '</ul>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
					}
				}
			?>
			<div class="clearfix"></div>
			<?php
				$sql= 'select rm.*, cm.countries_name as CountryName
								from ' . RESELLER_MASTER . ' rm 
								left join ' . COUNTRY_MASTER . ' cm on (rm.country = cm.id)
								where type=2 and rm.status=1
								order by reseller';
				$query = $mysql->query($sql);
				
				$i = 0;
				$groupName = "";
				if($mysql->rowCount($query) > 0)
				{
					echo '<h1>Reseller</h1>';
					$rows = $mysql->fetchArray($query);
					foreach($rows as $row)
					{
						echo '<div class="col-sm-6">';
						echo '<h2>' . $row['reseller'] . '</h2>';
						echo '<ul>';
						echo '</ul>';
						echo '<ul>';
						echo '</ul>';
						echo '</div>';
						
						
						echo '<div class="col-sm-4">';
							echo '<div class="panel">';
								echo '<div class="panel-heading">' . $row['reseller'] . '</div>';
								echo '<div class="panel-body">';
									echo '<ul class="list-unstyled">';
										echo '<li><b>' . $lang->get('lbl_email') . ':</b> ' . $row['email'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_address') . ':</b> ' . $row['address'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_country') . ':</b> ' . $row['CountryName'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_mobile') . ':</b> ' . $row['mobile'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_phone') . ':</b> ' . $row['phone'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_website') . ':</b> ' . $row['website'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_yahoo') . ':</b> ' . $row['yahoo'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_msn') . ':</b> ' . $row['msn'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_skype') . ':</b> ' . $row['skype'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_icq') . ':</b> ' . $row['icq'] . '</li>';
										echo '<li><b>' . $lang->get('lbl_sonork') . ':</b> ' . $row['sonork'] . '</li>';
									echo '</ul>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
					}
				}
			?>
		
		</div>
	</div>
</div>