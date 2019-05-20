<?php
	class cart
	{
		public function addToCart_new($id, $qty)
		{
			$cookie = new cookie();
			if(isset($_COOKIE['cart']['id' . $id]))
			{
				$qtyOld = (int)$_COOKIE['cart']['id' . $id]['qty'];
				$qtyOld = ((int)$qtyOld > 0) ? $qtyOld : 1;
				$cookie->setCookie('cart[id' . $id . '][qty]',$qtyOld + $qty);
			}
			else
			{
				$qty = ((int)$qty > 0) ? $qty : 1;
				$cookie->setCookie('cart[id' . $id . '][qty]',(int)$qty);
			}
		}
		public function remove($id)
		{
			$cookie = new cookie();
			$cookie->deleteCookie('cart[id' . $id . '][qty]');
			
			//$cookie->delete(array("cart" => "' . $id . '"));
		}
		public function displayProduct($proId,$pro_name,$pro_weight,$pro_credits)
		{
			$graphics = new graphics();
			$currency = new currency();
			echo '
			<div class="item_border">
				<table width="100%" cellpadding="10" cellspacing="0" border="0">
					<tr>
						<td width="10%">' . $graphics->displayImage(CONFIG_PATH_SITE . "/images/pro_size1/" . $proId . ".jpg","") . '</td>
						<td width="50%" class="text">' . 
										'<h4>' . $pro_name . '</h4>' .
										'Weight: ' . $pro_weight . 'Kg<br />' .
										'Price: ' . $currency->displayPrice($pro_credits) . '<br />' .
						'</td>
						<td width="40%" class="text" align="center">
							<div id="hiddenFileds' . $proId . '">
								Quantity: 
									<input type="text" value="0" name="qty" id="qty_' . $proId . '" class="textbox" size="4" />
									<input type="hidden" value="' . $proId . '" id="val_' . $proId . '" name="id" />';
									$graphics->button_script("Add", "javascript:silentAddToCart($('#val_" . $proId . "').attr('value'),$('#qty_" . $proId . "').attr('value'),'hiddenFileds" . $proId . "')");
			echo '
							</div>
						</td>
					</tr>
				</table>
			</div>';
		}
		
		public function displayProductCart($proId,$pro_name,$pro_weight,$pro_credits,$cartCount)
		{
			$graphics = new graphics();
			$currency = new currency();
			
			$mysql = new mysql();
			
			$sql = 'select 
						pm.*
						from ' . PRODUCT_MASTER . ' pm
						where pm.id=' . $mysql->getInt($proId);
			$query = $mysql->query($sql);
			$row = $mysql->fetchArray($query);
			
			$row = $row[0];
			

			$max = 0;
			
			$pro_credits = $row['price'];
						
			
			$rateList = $this->showItemCountDetails($proId);
			
			echo '
			<div class="item_border">
				<table width="100%" cellpadding="2" cellspacing="0" border="0">
				    <tr style="background-color:#F7F7F7">
						<td class="text" align="center">Remove<br /><input type="checkbox" name="id[]" id="id[]" value="' . $proId . '" /></td>
						<td colspan="2"><h2 style="margin:0px 0px 0px 0px;">' . $pro_name . '</h2></td>
					</tr>
					<tr>
						<td width="10%">' . $graphics->displayImage(CONFIG_PATH_SITE . "/images/pro_size1/" . $proId . "-1.jpg","") . '</td>
						<td width="50%" class="text">' . 'Weight: ' . $pro_weight . ' kg<br />' . 'Price: ' . $currency->displayPrice($pro_credits) . ' x ' . $cartCount . ' = ' . $currency->displayPrice(($pro_credits*$cartCount)) . '<br /></td>
						<td width="40%" class="text" align="center">
						<input name="qty_' . $proId . '" type="text" style="text-align:center;border:1px #999999 solid;margin-bottom:4px;background-color:#F9F9F9" id="qty' . $proId . '" value="' . $cartCount . '" size="6" maxlength="4" />' . $rateList . '</td>
					</tr>
				</table>
			</div>';
		}
		
		public function showItemCount($proId)
		{
			$mysql = new mysql();
			
			$currency = new currency();
			
			$sql = 'select 
						pm.*
						from ' . PRODUCT_MASTER . ' pm
						where pm.id=' . $mysql->getInt($proId);
			$query = $mysql->query($sql);
			$details = $mysql->fetchArray($query);
			
			$pro = $details[0];
					echo '<select name="product_qty" id="product_qty" class="textbox_fix">';
				  	if($mysql->getFloat($pro['price_spl']) == '0')
					{
				  		echo '<option value="1">1 Pc</option>';
					}
					else
					{
						echo '<option value="1">1 Pc</option>';
					}
					if($mysql->getInt($pro['unit1']) == '0')
					{
						for($i=2;$i<=40;$i++)
						{
							echo '<option value="' . $i . '">' . $i . ' Pcs</option>';
						}
					}
					echo '</select>';
		}
		
		public function showItemCountDetails($proId)
		{
			$mysql = new mysql();
			$currency = new currency();
			
			$ret = '';
			
			$sql = 'select 
						pm.*
						from ' . PRODUCT_MASTER . ' pm
						where pm.id=' . $mysql->getInt($proId);
			$query = $mysql->query($sql);
			$row = $mysql->fetchArray($query);
			
			$row = $row[0];
			
			$ret .= '<table cellpadding="2" width="150" cellspacing="1" style="background-color:#999999" border="0">';
			
			$ret .= '<tr style="background-color:#F9F9F9"><td>1 unit</td><td> ' . $currency->displayPrice($row['price']) . '</td></tr>';
			
			$ret .= '</table>';
			
			return $ret;
		}
		
		public function displayCartDetails($comIds,$comNames,$comIdSel,$subTotal,$shipping,$grossWeight)
		{
			$graphics = new graphics();
			$currency = new currency();
			echo '
			<Table width="100%" style="background-color:#2584C0;color:#FFFFFF" cellpadding="5" cellspacing="0" border="0">
				<Tr>
					<Td width="20%" class="text_white" align="right"><b>Gross Weight</b></Td>
					<Td width="20%" class="text_white">' . $grossWeight . 'Kg</Td>
					<Td width="20%" class="text_white"></Td>
					<Td width="20%" class="text_white" align="right"><b>Sub Total</b></Td>
					<Td width="20%" class="text_white">' . $currency->displayPrice($subTotal) . '</Td>
				</Tr>
				<Tr>
					<Td width="20%" class="text_white" align="right"><b>Shipping Company</b></Td>
					<Td class="text_white" colspan="2">';
						$tempComIds = explode(",",$comIds);
						$tempComNames = explode(",",$comNames);
						$tempComIdSel = explode(",",$comIdSel);
						echo '<select name="company_id" class="textbox">';
						for($i=0;$i<count($tempComNames);$i++)
						{
							$sel = "";
							if($tempComIdSel[$i] == "1")
							{
								$sel = 'selected="selected"';
							}
							echo '<option value="' . $tempComIds[$i] . '" ' . $sel . ' >' . $tempComNames[$i] . '</option>';
						}
						echo '</select>';
			echo '</Td>
					<Td width="20%" class="text_white" align="right"><b>Shipping</b></Td>
					<Td width="20%" class="text_white">' . $currency->displayPrice($shipping) . '</Td>
				</Tr>
				<Tr>
					<Td width="20%" class="text_white" align="right" colspan="3"></Td>
					<Td width="20%" class="text_white" align="right"><b>Grand Total</b></Td>
					<Td width="20%" class="text_white">' . $currency->displayPrice(($subTotal + $shipping)) . '</Td>
				</Tr>
			</Table>';
		}
		
		public function displayCartHistryItem($order_id,$date_time,$status,$company_name)
		{
			$graphics = new graphics();
			echo '
			<div class="item_border">
				<Table width="100%" cellpadding="0" cellspacing="6" border="0">
					<Tr>
						<Td class="text" width="20%">Order No: ' . $order_id . '</Td>
						<Td class="text" width="20%">' . $date_time . '</Td>';
			switch($status)
			{
				case "0":
					$status = "Pending";
					break;
				case "1":
					$status = "Package Dispatched";
					break;
				case "2":
					$status = "Complete";
					break;
				default:
					$status = "Pending";
					break;

			}
			echo '
						<Td class="text" width="20%">' . $status . '</Td>
						<Td class="text" width="20%">' . $company_name . '</Td>
						<Td class="text" width="20%" align="center">';
			$graphics->button("Details", "cart_history_details.php?id=" . $order_id);
			echo '</Td>
					</Tr>
				</Table>
			</div>';
		}
		
		public function displayCartHistryOrderDetails($order_id,$date_time,$status,$tracking_no,$grossWeight,$subTotal,$shipping,$grandTotal)
		{
			$graphics = new graphics();
			echo '
			<div class="item_border">
				<Table width="100%" cellpadding="5" cellspacing="0" border="0">
					<Tr>
						<Td class="text" width="50%">Order No : ' . $order_id . '</Td>
						<Td class="text" width="50%">Gross Weight : ' . $grossWeight . ' Kg</Td>
					</Tr>
					<Tr>
						<Td class="text" width="50%">Date of Order : ' . $date_time . '</Td>
						<Td class="text" width="50%">Sub Total : ' . $subTotal . ' Cr</Td>
					</Tr>
					<Tr>';
			switch($status)
			{
				case "0":
					$status = "Pending";
					break;
				case "1":
					$status = "Package Dispatched";
					break;
				case "2":
					$status = "Complete";
					break;
				default:
					$status = "Pending";
					break;

			}
			echo '
						<Td class="text" width="50%">Order Status : ' . $status . '</Td>
						<Td class="text" width="50%">Shipping : ' . $shipping . ' Cr</Td>
					</Tr>
					<Tr>
						<Td class="text" width="50%">Tracking Number : ' . $tracking_no . '</Td>
						<Td class="text" width="50%">Grand Total : ' . $grandTotal . ' Cr</Td>
					</Tr>
				</Table>
			</div>';
		}
		
		public function displayCartHistryOrderItem($pro_name,$qty,$credits,$weight,$isHeading)
		{
			if($isHeading == false)
			{
				echo '
				<div class="CartHisBody">
					<Table width="100%" cellpadding="0" cellspacing="9" border="0">
						<Tr>
							<Td class="text" width="40%"><b>' . $pro_name . '</b></Td>
							<Td class="text" width="15%">' . $qty . ' Units</Td>
							<Td class="text" width="15%">' . $credits . ' Cr</Td>
							<Td class="text" width="15%">' . $weight . ' Kg</Td>
							<Td class="text" width="15%">' . $credits . ' Cr</Td>
						</Tr>
					</Table>
				</div>';
			}
			else
			{
				echo '
				<div class="CartHisHeading">
					<Table width="100%" cellpadding="0" cellspacing="5" border="0">
						<Tr>
							<Td class="Lagend" width="40%">Product name</Td>
							<Td class="Lagend" width="15%">Quantity</Td>
							<Td class="Lagend" width="15%">Price [Cr]</Td>
							<Td class="Lagend" width="15%">Weight</Td>
							<Td class="Lagend" width="15%">Sub Total [Cr]</Td>
						</Tr>
					</Table>
				</div>';
			}
		}
		
		public function addToCart($pro_id,$qty)
		{
			// Create Cookie Object
			$cookie = new cookie();
			$str = "";
			
			$found = -1;
			//Check if the Cart if empty
			if(trim($cookie->getCookie("cart")) != "")
			{
				// Get Cart items
				$item_list = explode(",",$cookie->getCookie("cart"));
				// Iterate the Cart Items
				for($i=0;$i<count($item_list);$i++)
				{
					$item = explode(":", $item_list[$i]);
					$items[] = $item;
					// Check for the id if exists
					if($item[0] == $pro_id)
					{
						$found = $i;
					}
				}
				// if Item exists in the cart
				if($found != -1)
				{
					$items[$found][1] += $qty;
					$addStr = "";
				}
				else
				{
					$addStr = "," . $pro_id . ":" . $qty;
				}
				for($i=0;$i<count($items);$i++)
				{
					$str .= $items[$i][0] . ":" . $items[$i][1] . ",";
				}
				$str = substr($str,0,strlen($str)-1);
				$cookie->setCookie("cart",$str.$addStr);
			}
			else
			{
				$cookie->setCookie("cart",$pro_id . ":" . $qty);
			}
			//setcookie("cart",$cart,time() + 3600);
		}
		
		
		
		public function exists($pro_id)
		{
			// Create Cookie Object
			$cookie = new cookie();
			
			$found = -1;
			//Check if the Cart if empty
			if(trim($cookie->getCookie("cart")) != "")
			{
				// Get Cart items
				$item_list = explode(",",$cookie->getCookie("cart"));
				// Iterate the Cart Items
				for($i=0;$i<count($item_list);$i++)
				{
					$item = explode(":", $item_list[$i]);
					$items[] = $item;
					// Check for the id if exists
					if($item[0] == $pro_id)
					{
						$found = $i;
					}
				}
				// if Item exists in the cart
				if($found != -1)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}

		
		public function updateCart($value)
		{
			// Create Cookie Object
			$cookie = new cookie();
			$cookie->setCookie("cart",$value);
		}











		public function showCart($show_buttons)
		{
			$cookie = new cookie();
			$mysql = new mysql();
			echo '<form action="update_cart_company.php" name="frm_cart_company" method="get">';
			echo 'Shipping Company &nbsp; ';
			$sql_com = "select * from " . STORE_SHIPPING_COMPANY . " where company_name!='' and status=1";
			$query_com = $mysql->query($sql_com);
			echo '<select class="textbox" name="com_id">';
			while($row_com = $mysql->fetchArray($query_com))
			{
				$sel = "";
				if($cookie->getCookie("ShippingCompany")!="")
				{
					if($row_com["id"] == $cookie->getCookie("ShippingCompany"))
					{
						$sel = ' selected="selected"';
					}
				}
				else
				{
					if($row_com["defaults"] == "1")
					{
						$sel = ' selected="selected"';
					}
				}
				echo '<option ' . $sel . ' value="' . $row_com["id"] . '">' . $row_com["company_name"] . '</option>';
			}
			echo '</select>';
			echo ' &nbsp; <input type="submit" value="Change Company">';
			echo '</form>';
			echo '
				<form action="update_cart.php" name="frm_cart" method="get">
				<table width="100%" border="0" cellspacing="1" cellpadding="0" class="">
						  <tr class="frame_head_blank">
							<td colspan="2" align="left" class="txt_11_normal"><b>Item</b></td>
							<td width="13%" align="center" class="txt_11_normal"><b>Weight</b> </td>
							<td width="13%" align="center" class="txt_11_normal"><b>Unit Price</b> </td>
							<td width="12%" align="center" class="txt_11_normal"><b>Quantity</b></td>
							<td width="13%" align="center" class="txt_11_normal"><b>Cost</b></td>
						  </tr>
                  <tr>
				  	<td colspan="6">';
					if($cookie->getCookie("cart") != "")
					{
						echo '
						<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#FFFFFF">
						  <tr class="bg_gray">
							<td colspan="6" align="left" class="bg_cart_head" height="1"></td>
						  </tr>';
						  
						$g_total = 0;
						$sub_weight = 0;
						$g_weight += 0;
						
						$items = explode(",", $cookie->getCookie("cart"));
		
						for($i=0;$i<count($items);$i++)
						{
							$item = explode(":",$items[$i]);
							$qty = $item[1];
							$mysql = new mysql();
							$sql = "select pro_id, pro_type, pro_name, pro_weight, pro_mrp_usd, pro_mrp_usd_spl, unit2, price2, unit3, price3, unit4, price4, unit5, price5, pro_shipping_mode from product_master where pro_id like '" . $item[0] . "' and pro_type like 'main'";
							$query = $mysql->query($sql);
							$row = $mysql->fetchArray($query);
							
							if ($row["pro_mrp_usd_spl"] == "0" || $row["pro_mrp_usd_spl"] == "")
							{
								$price = $row["pro_mrp_usd"];
							}
							else
							{
								$price = $row["pro_mrp_usd_spl"];
							}
							
							if($row["unit2"] != "" && $row["unit2"] != "0" && $row["price2"] != "" && $row["price2"] != "0")
							{
								if($qty >= $row["unit2"])
								{
									$price = $row["price2"];
								}
							}
							if($row["unit3"] != "" && $row["unit3"] != "0" && $row["price3"] != "" && $row["price3"] != "0")
							{
								if($qty >= $row["unit3"])
								{
									$price = $row["price3"];
								}
							}
							if($row["unit4"] != "" && $row["unit4"] != "0" && $row["price4"] != "" && $row["price4"] != "0")
							{
								if($qty >= $row["unit4"])
								{
									$price = $row["price4"];
								}
							}
							if($row["unit5"] != "" && $row["unit5"] != "0" && $row["price5"] != "" && $row["price5"] != "0")
							{
								if($qty >= $row["unit5"])
								{
									$price = $row["price5"];
								}
							}
							
							
							$total = ($price) * ($qty);
							if($i % 2 == 0)
							{
								$str_bg = "#FFFFFF";
							}
							else
							{
								$str_bg = "#F5F5F5";
							}
						echo '
						  <tr class="frame_white">
							<td width="13%" align="center" class="text_11_gray"><img border="0" src="images/pro_small/' . $row["pro_id"] . '.jpg" alt="' . $row["pro_name"] . '" /></td>
							<td align="left" class="text_11_gray">' . $row["pro_name"] . '</td>
							<td width="10%" align="center" class="text_11_gray">' . number_format(($row["pro_weight"] * $qty),2) . ' kg</td>
							<td width="10%" align="center" class="text_11_gray">$' . $price . '</td>
							<td width="10%" align="center" class="text_11_gray"><input type="text" name="' . $row["pro_id"] .'" value="' . $qty . '" class="textbox" size="3" /></td>
							<td  width="10%"align="center" class="text_11_gray"><b>$' . $total . '</b><br /><a href="remove_cart_item.php?pro_id=' . $row["pro_id"] . '"><img src="./images/but_remove.jpg" border="0"></td>
						  </tr>
						  <tr>
						  	<td colspan="6" align="center" class="bg_line" height="1"></td>
						  </tr>';
							$g_total = $g_total + $total;
							$sub_weight = $row["pro_weight"] * $qty;
							if($row["pro_shipping_mode"] == 0)
							{
								$grandWeight += $sub_weight;
							}
						}
						echo '
						  <tr>
							<td height="30" colspan="3" align="left" class="text_11_gray">&nbsp;</td>
							<td width="13%" height="30" align="center" class="text_11_gray">&nbsp;</td>
							<td width="12%" height="30" align="right" class="text_11_gray">Sub Total</td>
							<td width="13%" height="30" align="right" class="text_11_gray">$' . number_format($g_total,2) . '</td>
						  </tr>';
						  $country_id = $cookie->getCookie("country");
						if($cookie->getCookie("ShippingCompany")!="")
						{
							$com_id = (int)addslashes($_GET["com_id"]);
							$sql_ship = "select * from " . STORE_SHIPPING_CREDITS . " where company_id=" . $cookie->getCookie("ShippingCompany") . " and country_id={$country_id}";
							$query_ship = $mysql->query($sql_ship);
							$row_ship = $mysql->fetchArray($query_ship);
						}
						else
						{
							$sql_ship = "select * from " . STORE_SHIPPING_COMPANY . " where defaults=1";
							$query_ship = $mysql->query($sql_ship);
							$row_ship = $mysql->fetchArray($query_ship);
							
							$com_id = $row_ship["id"];
							
							$sql_ship = "select * from " . STORE_SHIPPING_CREDITS . " where company_id={$com_id} and country_id={$country_id}";
							$query_ship = $mysql->query($sql_ship);
							$row_ship = $mysql->fetchArray($query_ship);
						}
							if($grandWeight > 0.5)
							{
								$shipping = $grandWeight - 0.5;
								$shipping /= 0.5;
								$tempFloor = floor($shipping);
								if($tempFloor < $shipping)
								{
									$tempFloor++;
								}
								$shipping = ($row_ship["addl"] * $tempFloor) + $row_ship["credits"];
							}
							else
							{
								if($grandWeight > 0)
								{
									$shipping = $row_ship["credits"];
								}
							}
							$g_total += $shipping;
						echo '
						  <tr>
							<td height="30" colspan="5" align="right" class="text_11_gray">Shipping</td>
							<td width="13%" height="30" align="right" class="text_11_gray">$' . number_format($shipping,2) . '</td>
						  </tr>';
						echo '
						  <tr>
							<td height="30" colspan="5" align="right" class="text_11_gray"><b>Grand Total</b></td>
							<td width="13%" height="30" align="right" class="text_11_gray"><b>$' . number_format($g_total,2) . '</b></td>
						  </tr>';
						echo '
						  <tr>
							<td height="30" colspan="5" align="right" class="text_11_gray">Gross Weight</td>
							<td width="13%" height="30" align="right" class="text_11_gray">' . number_format($grandWeight,2) . ' Kg</td>
						  </tr>';
						  if($show_buttons == 1)
						  {
							  echo '<tr>
								<td height="30" colspan="6" align="right">
								<Table border=0 cellpadding=0 cellspacing=0>
											<Tr>
												<Td align="center" class="bg_button" onmouseover="mouse_hover(this,\'bg_button_hover\');" onmouseout="mouse_out(this,\'bg_button\');" onclick="mouse_click(\'checkout.php\')">Check Out Now</Td>
												<Td class="button_spacer"></Td>
												<Td align="center" class="bg_button" onmouseover="mouse_hover(this,\'bg_button_hover\');" onmouseout="mouse_out(this,\'bg_button\');" onclick="document.frm_cart.submit()">Update Cart</Td>
											</Tr>
										</table>
								</td>
							  </tr>';
						  }
						  echo '</table>';
					
						}
						else
						{
					echo '
						<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#FFFFFF">
						  <tr>
							<td align="center" class="text_11_gray">
								<img border="0" src="images/cart_empty.jpg" /><br /><br />
								To put something in your cart, start by searching or browsing through our departments. When you find something you like, simply click "Add to Cart" and it will be placed here until you check out.<br /><br />
								<Table border=0 cellpadding=0 cellspacing=0>
										<Tr>
											<Td align="center" class="bg_button" onmouseover="mouse_hover(this,\'bg_button_hover\');" onmouseout="mouse_out(this,\'bg_button\');" onclick="mouse_click(\'all-categories_1_0_cat.html\')">Continue Shopping</Td>
										</Tr>
									</table></td>
						  </tr>
						</table>';
						}
						echo '
				       </td>
				  	</tr>
				</table>
				</form>';
				
			echo '<form action="update_country.php" method="post">Select Country';
			echo '&nbsp;<select name="country" class="textbox" id="country">';
				$query_c = "select countries_id,countries_name, countries_iso_code_2 from countries";
				$result_c = mysql_query($query_c);
				$sel = "";
				while($row_c = mysql_fetch_array($result_c))
				{
					$sel = "";
					if($cookie->getCookie("country") == $row_c["countries_id"])
					{
						$sel = 'Selected="selected"';
					}
					echo '<option value="' . $row_c["countries_id"] . '"' . $sel . '>' . $row_c["countries_name"] . '</option>';
					$sel = "";
				}
			echo '</select>';
			echo '&nbsp;<input type="submit" value="Set Country" /></form>';



		}
		
		
		
		public function feedCartDetails()
		{
			$companyName = "";
			$cartItems = "";
			$member = new member();
			$cookie = new cookie();
			$order_no = $cookie->getCookie("order_number");
			$com_id = "";
			$g_total = 0;
			if($cookie->getCookie("cart") != "")
			{
				$items = explode(",", $cookie->getCookie("cart"));
	
				for($i=0;$i<count($items);$i++)
				{
					$item = explode(":",$items[$i]);
					$qty = $item[1];
					$mysql = new mysql();
					$sql = "select pro_id, pro_type, pro_name, pro_mrp_usd, pro_mrp_usd_spl, pro_weight, pro_shipping_mode from product_master where pro_id like '" . $item[0] . "' and pro_type like 'main'";
					$query = $mysql->query($sql);
					$row = $mysql->fetchArray($query);
					
					$cartItems .= "-" . $row["pro_name"] . "<br />";
					
					if ($row["pro_mrp_usd_spl"] == "0" || $row["pro_mrp_usd_spl"] == "")
					{
						$price = $row["pro_mrp_usd"];
					}
					else
					{
						$price = $row["pro_mrp_usd_spl"];
					}
					
					$mysql_o = new mysql();
					$sql_o = "INSERT INTO order_details
								VALUES (
								'" . $item[0] . "', '" . $qty . "', '$price', '$order_no'
								)";
					$query_o = $mysql->query($sql_o);
					
					$total = ($price) * $qty;
					$g_total = $g_total + $total;
					
					$sub_weight = $row["pro_weight"] * $qty;
					if($row["pro_shipping_mode"] == "0")
					{
						$grandWeight += $sub_weight;
					}
				}
				
						//Shpping Details
						$sql_member = "select * from member_master where username=" . $member->getUserId();
						$query_member = 
						$country_id = $cookie->getCookie("country");
						if($cookie->getCookie("ShippingCompany")!="")
						{
							$sql_ship = "select * from " . STORE_SHIPPING_COMPANY . " where id=" . $cookie->getCookie("ShippingCompany");
							$query_ship = $mysql->query($sql_ship);
							$row_ship = $mysql->fetchArray($query_ship);
							$companyName = $row_ship["company_name"];



							$com_id = (int)addslashes($_GET["com_id"]);
							$sql_ship = "select * from " . STORE_SHIPPING_CREDITS . " where company_id=" . $cookie->getCookie("ShippingCompany") . " and country_id={$country_id}";
							$query_ship = $mysql->query($sql_ship);
							$row_ship = $mysql->fetchArray($query_ship);
						}
						else
						{
							$sql_ship = "select * from " . STORE_SHIPPING_COMPANY . " where defaults=1";
							$query_ship = $mysql->query($sql_ship);
							$row_ship = $mysql->fetchArray($query_ship);
							
							$companyName = $row_ship["company_name"];
							
							$com_id = $row_ship["id"];
							
							$sql_ship = "select * from " . STORE_SHIPPING_CREDITS . " where company_id={$com_id} and country_id={$country_id}";
							$query_ship = $mysql->query($sql_ship);
							$row_ship = $mysql->fetchArray($query_ship);
							
						}
						
						if($grandWeight > 0.5)
						{
							$shipping = $grandWeight - 0.5;
							$shipping /= 0.5;
							$tempFloor = floor($shipping);
							if($tempFloor < $shipping)
							{
								$tempFloor++;
							}
							$shipping = ($row_ship["addl"] * $tempFloor) + $row_ship["credits"];
						}
						else
						{
							if($grandWeight > 0)
							{
								$shipping = $row_ship["credits"];
							}
						}
						$g_total2 = $g_total + $shipping;
						//Shpping Details End
				
				$cookie->setCookie("Shipping",$shipping);
				$mysql_master = new mysql();
				$sql_master = "UPDATE order_master SET 
					order_status = 'Confirmed By User',
					date_time = now(),
					payment_mode = '" . $cookie->getCookie("payment_opt") . "',
					total_payment  = '$g_total',
					courier_company = '$com_id',
					gross_weight = '$grandWeight',
					gross_shipping = '$shipping',
					subtotal = '$g_total',
					grand_total = '$g_total2',
					country = '" . $cookie->getCookie("country") . "',
					clearance = '0',
					tracking_number = ''
					WHERE order_no =$order_no";
				$query_master = $mysql_master->query($sql_master);
			}
			$email = new email();
			$email->sendOrderNo($member->getUserId(),$order_no,$companyName,$cartItems);
			
		}
		
		public function checkoutCart($cartPros)
		{
			$cookie = new cookie();
			
			if($cartPros != "")
			{
				echo "<form  name='paypal' method='POST' action='https://www.paypal.com/uk/cgi-bin/webscr' target='_new'>";
				echo "<input type = 'hidden' name = 'cmd' value = '_cart'>";
				echo "<input type = 'hidden' name = 'upload' value = '1'>";
				echo "<input type = 'hidden' name = 'business' value = 'pnt3@hotmail.com'>";
				echo "<input type = 'hidden' name = 'bn' value = 'www.point3.net'>";
				echo "<input type = 'hidden' name = 'lc' value ='GB'>";
				echo "<input type = 'hidden' name = 'currency_code' value = 'USD'>";
				echo "<input type = 'hidden' name = 'upload' value = '1'>";
				echo '<input type="hidden" name="handling_cart" value="' . $cookie->getCookie("Shipping") . '">';

				$items = explode(",", $cartPros);
				for($i=0;$i<count($items);$i++)
				{
					$item = explode(":",$items[$i]);
					$mysql = new mysql();
					$sql = "select pro_id, pro_type, pro_name, pro_mrp_usd, pro_mrp_usd_spl from product_master where pro_id like '" . $item[0] . "' and pro_type like 'main'";
					$query = $mysql->query($sql);
					$row = $mysql->fetchArray($query);
					
					if ($row["pro_mrp_usd_spl"] == "0" || $row["pro_mrp_usd_spl"] == "")
					{
						$price = $row["pro_mrp_usd"];
					}
					else
					{
						$price = $row["pro_mrp_usd_spl"];
					}
					$j = $i + 1;
					echo "<input type='hidden' name='item_name_" . $j . "' id='item_name_" . $j . "'  value='" . $row["pro_name"] . "'>";
					echo "<input type='hidden' name='item_number_" . $j . "' id='item_number_" . $j . "'  value='" . $item[0] . "'>";
					echo "<input type='hidden' name='quantity_" . $j . "' id='quantity_" . $j . "'  value='" . $qty . "'>";
					echo "<input type='hidden' name='amount_" . $j . "' id='amount_" . $j . "'  value='" . $price . "'>";
				}//End For
				echo "<input name='paypal2' type='submit'  id='paypal2' value='Checkout'>";
				echo "</form>";
			}//End if
			
		}//End Function
		
	}
?>
