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
	
	$sesiis_username	= $_SESSION['sesiis_username'];
	$userid 			= $_REQUEST['userid'];
	$old_userpass 		= $_REQUEST['old_userpass'];
	$new_userpass 		= $_REQUEST['new_userpass'];
	
	$cekstrstr	= strstr($old_userpass, "\\") || strstr($new_userpass, "\\");
	
	if( $cekstrstr )
	{
		echo "{
			'success': false,
			'msg': 'Character input error.'
		}";
	}
	else
	{
		$rs = $db->Execute("select * from tbl_userlogin where userid = '". $userid  ."' and userpass = '". md5($old_userpass) ."'");
		if( !$rs->EOF )
		{
			$rs->Close();
			try
			{
				$rs = $db->Execute("select * from tbl_userlogin where userid = '". $userid  ."' and userpass = '". md5($old_userpass) ."'");
				$upd = array();
				$upd["userpass"] 	= md5($new_userpass);
				$upd["update_date"] = $tgl.' '.$wkt;
				$upd["user_id"] 	= $sesiis_username;
				
				$updateSQL 	= $db->GetUpdateSQL($rs, $upd);
				$db->Execute($updateSQL);
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
							'msg': 'Password has been changed.'
						}";
						break;
				}
			//	end of message
		}
		else
		{
			echo "{
				'success': false,
				'msg': 'User ID and Old Password not match.'
			}";
			$rs->Close();
		}
	}
	
	// Closing Database Connection
	$db->Close();
?>