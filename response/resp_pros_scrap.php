<?php
	session_start();
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	error_reporting(0);
	
	//Setting Jam Indonesia //
		date_default_timezone_set('Asia/Jakarta');
		$tgl 	= gmdate("Y-m-d");
		$wkt 	= date('H:i:s');
	// ================= //
	
	$st_periode 		= $_REQUEST['st_periode'];
	$en_periode 		= $_REQUEST['en_periode'];
	$sesiis_username	= $_SESSION['sesiis_username'];
	$typeform 			= $_REQUEST['typeform'];
	
	
	$rs 		= $db->Execute("insert into scrap
									(
										no,kodebarang,namabarang,unit,last,
										rcv,adj,iss,bal,stokopname,diff,periode
									)
								select * from vw_proses('". $st_periode ."','". $en_periode ."')");
	$rs->Close();
	
	//	insert log
	$rs 	= $db->Execute("select * from tbl_userlog");
	$add = array();
	$add["user_action"] 	= $sesiis_username;
	$add["action"] 			= $typeform ;
	$add["target"] 			= 'menu_proses_scrap';
	$add["description"] 	= $sesiis_username;
	$add["date_action"] 	= $tgl.' '.$wkt;
	
	$insertSQL 	= $db->GetInsertSQL($rs, $add);
	$db->Execute($insertSQL);
	$rs->Close();

				
	echo "{
		'success': true,
		'msg': 'Scrap File has been successfully processed.'
	}";
	
	// Closing Database Connection
	$db->Close();
?>