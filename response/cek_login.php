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
	
	$userid 	= $_REQUEST['userid'];
	$userpass 	= $_REQUEST['userpass'];
	
	
	$rs = $db->Execute("select * from tbl_userlogin where userid = '". $userid  ."' and userpass = '". md5($userpass) ."'");
	if( !$rs->EOF )
	{
		$_SESSION['sesiis_userid'] 		= trim( $rs->fields['0'] );
		$_SESSION['sesiis_username'] 	= trim( $rs->fields['1'] );
		$_SESSION['sesiis_level'] 		= trim( $rs->fields['3'] );
		$_SESSION['sesiis_dept'] 		= trim( $rs->fields['4'] );
		
		if( $rs->fields['3'] == 'ADMIN' ){
			if($userid == 'admin' || $userid == '31530' || $userid == '33109' ){
				$url = 'stp_userlogin.php';
			}else{
				$url = 'userlog.php';
			}
		}else{
			$url = 'inpermonth.php';
		}
		
		//	insert log
		$rs2 	= $db->Execute("select * from tbl_userlog");
		$add = array();
		$add["user_action"] 	= $_SESSION['sesiis_username'];
		$add["action"] 			= 'login';
		$add["target"] 			= 'login';
		$add["description"] 	= 'Success Login';
		$add["date_action"] 	= $tgl.' '.$wkt;
		
		$insertSQL 	= $db->GetInsertSQL($rs2, $add);
		$db->Execute($insertSQL);
		$rs2->Close();
		
		echo "{
			'success': true,
			'msg': '$url'
		}";
		$rs->Close();
	}
	else
	{
		//	insert log
		$rs2 	= $db->Execute("select * from tbl_userlog");
		$add = array();
		$add["user_action"] 	= $userid;
		$add["action"] 			= 'login';
		$add["target"] 			= 'login';
		$add["description"] 	= 'Invalid Login';
		$add["date_action"] 	= $tgl.' '.$wkt;
		
		$insertSQL 	= $db->GetInsertSQL($rs2, $add);
		$db->Execute($insertSQL);
		$rs2->Close();
		
		echo "{
			'success': false,
			'msg': 'Invalid Login.'
		}";
		$rs->Close();
	}
	
	// Closing Database Connection
	$db->Close();
?>