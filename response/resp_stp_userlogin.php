<?php
	session_start();
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	
	//Setting Jam Indonesia //
		date_default_timezone_set('Asia/Jakarta');
		$tgl = gmdate("Y-m-d");
		$wkt = date('H:i:s');
	// ================= //
	
	$typeform 			= $_REQUEST['typeform'];
	$sesiis_username	= $_SESSION['sesiis_username'];
	switch ($typeform)
	{
		//***
		//response add
		case 'add':
			$userid		= $_REQUEST['userid'];
			$username	= $_REQUEST['username'];
			$userpass	= $_REQUEST['userpass'];
			$userlevel	= $_REQUEST['userlevel'];
			$cekstrstr	= strstr($userid, "\\") || strstr($username, "\\") || strstr($userpass, "\\");
			if( $cekstrstr )
			{
				echo "{
					'success': false,
					'msg': 'Character input error.'
				}";
			}
			else
			{
				try
				{
					$rs 	= $db->Execute("select * from tbl_userlogin");
					$add = array();
					$add["userid"] 		= $userid;
					$add["username"] 	= $username;
					$add["userpass"] 	= md5($userpass);
					$add["userlevel"] 	= $userlevel;
					$add["input_date"] 	= $tgl.' '.$wkt;
					$add["user_id"] 	= $sesiis_username;
					
					$insertSQL 	= $db->GetInsertSQL($rs, $add);
					$db->Execute($insertSQL);
					$rs->Close();
					
					//	insert log
					$rs 	= $db->Execute("select * from tbl_userlog");
					$add = array();
					$add["user_action"] 	= $sesiis_username;
					$add["action"] 			= $typeform ;
					$add["target"] 			= 'menu_userlogin';
					$add["description"] 	= $userid;
					$add["date_action"] 	= $tgl.' '.$wkt;
					
					$insertSQL 	= $db->GetInsertSQL($rs, $add);
					$db->Execute($insertSQL);
					$rs->Close();
					
					$var_msg = 1;
				}
				catch (exception $e)
				{
					$var_msg = $db->ErrorNo();
				}
				
				//	Message
					switch ($var_msg)
					{
						case $db->ErrorNo():
							$err		= $db->ErrorMsg();
							$error_msg 	= str_replace( "'", "`", $err);
							echo "{
								'success': false,
								'msg': '$error_msg'
							}";
							break;
						
						case 1:
							echo "{
								'success': true,
								'msg': 'User ID: <b>$userid</b> has been saved.'
							}";
							break;
					}
				//	end of message
			}
			break;
			
		//***
		//response update
		case 'update':
			$userid		= $_REQUEST['userid'];
			$username	= $_REQUEST['username'];
			$userlevel	= $_REQUEST['userlevel'];
			$cekstrstr	= strstr($userid, "\\") || strstr($username, "\\");
			if( $cekstrstr )
			{
				echo "{
					'success': false,
					'msg': 'Character input error.'
				}";
			}
			else
			{
				try
				{
					$rs 	= $db->Execute("select * from tbl_userlogin where userid = '".$userid."'");
					$upd = array();
					$upd["userid"] 		= $userid;
					$upd["username"] 	= $username;
					$upd["userlevel"] 	= $userlevel;
					$upd["update_date"] = $tgl.' '.$wkt;
					$upd["user_id"] 	= $sesiis_username;
					
					$updateSQL 	= $db->GetUpdateSQL($rs, $upd);
					$db->Execute($updateSQL);
					$rs->Close();
					
					//	insert log
					$rs 	= $db->Execute("select * from tbl_userlog");
					$add = array();
					$add["user_action"] 	= $sesiis_username;
					$add["action"] 			= $typeform ;
					$add["target"] 			= 'menu_userlogin';
					$add["description"] 	= $username.', '.$userlevel;
					$add["date_action"] 	= $tgl.' '.$wkt;
					
					$insertSQL 	= $db->GetInsertSQL($rs, $add);
					$db->Execute($insertSQL);
					$rs->Close();
					
					$var_msg = 1;
				}
				catch (exception $e)
				{
					$var_msg = $db->ErrorNo();
				}
				
				//	Message
					switch ($var_msg)
					{
						case $db->ErrorNo():
							$err		= $db->ErrorMsg();
							$error_msg 	= str_replace( "'", "`", $err);
							echo "{
								'success': false,
								'msg': '$error_msg'
							}";
							break;
						
						case 1:
							echo "{
								'success': true,
								'msg': 'User ID: <b>$userid</b> has been updated.'
							}";
							break;
					}
				//	end of message
			}
			break;
		
		//***
		//response delete
		case 'delete':
			$userid		= $_REQUEST['userid'];
			try
			{
				$rs 	= $db->Execute("delete from tbl_userlogin where userid = '".$userid."'");
				$rs->Close();
				
				//	insert log
				$rs 	= $db->Execute("select * from tbl_userlog");
				$add = array();
				$add["user_action"] 	= $sesiis_username;
				$add["action"] 			= $typeform ;
				$add["target"] 			= 'menu_userlogin';
				$add["description"] 	= $userid;
				$add["date_action"] 	= $tgl.' '.$wkt;
				
				$insertSQL 	= $db->GetInsertSQL($rs, $add);
				$db->Execute($insertSQL);
				$rs->Close();
				
				$var_msg = 1;
			}
			catch (exception $e)
			{
				$var_msg = $db->ErrorNo();
			}
			
			//	Message
				switch ($var_msg)
				{
					case $db->ErrorNo():
						$err		= $db->ErrorMsg();
						$error_msg 	= str_replace( "'", "`", $err);
						echo "{
							'success': false,
							'msg': '$error_msg'
						}";
						break;
					
					case 1:
						echo "{
							'success': true,
							'msg': 'User ID: <b>$userid</b> has been deleted.'
						}";
						break;
				}
			//	end of message
			break;
				
		//***
		//response cbdelete
		case 'cbdelete':
			$total 		= $_REQUEST['total'];
			$cb 		= $_REQUEST['cb'];
			try
			{
				$userid		= explode("/",$cb);
				for ($i = 0; $i < $total; $i++) {
					$rs 	= $db->Execute("delete from tbl_userlogin where userid = '". $userid[$i] ."'");
					$rs->Close();
					
					//	insert log
					$rs 	= $db->Execute("select * from tbl_userlog");
					$add = array();
					$add["user_action"] 	= $sesiis_username;
					$add["action"] 			= $typeform ;
					$add["target"] 			= 'menu_userlogin';
					$add["description"] 	= $userid[$i];
					$add["date_action"] 	= $tgl.' '.$wkt;
					
					$insertSQL 	= $db->GetInsertSQL($rs, $add);
					$db->Execute($insertSQL);
					$rs->Close();
				}
				
				$var_msg = 1;
			}
			catch (exception $e)
			{
				$var_msg = $db->ErrorNo();
			}
			
			//	Message
				switch ($var_msg)
				{
					case $db->ErrorNo():
						$err		= $db->ErrorMsg();
						$error_msg 	= str_replace( "'", "`", $err);
						echo "{
							'success': false,
							'msg': '$error_msg'
						}";
						break;
					
					case 1:
						echo "{
							'success': true,
							'msg': 'Data has been deleted.'
						}";
						break;
				}
			//	end of message
			break;
	}
	
	// Closing Database Connection
	$db->Close();
?>