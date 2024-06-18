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
	
	
	$rs 		= $db->Execute("insert into material
									(
										no,kodebarang,namabarang,unit,last,
										rcv,adj,iss,bal,stokopname,diff,periode
									)
								select
									convert(varchar(1000), ROW_NUMBER() OVER(ORDER BY kodebarang)) as no,
									kodebarang, namabarang,
									'SET' as unit,
									sal_awal as last,
									masuk as rcv,
									'0' as adj,
									keluar as iss,
									sal_akhir as bal,
									'0' as stokopname,
									(
										(sal_awal+masuk)-(keluar+sal_akhir)
									) as diff,
									'". $en_periode ."' as periode
								from vw_pros_mtr('". $st_periode ."','". $en_periode ."')");
	$rs->Close();
	
	//	insert log
	$rs 	= $db->Execute("select * from tbl_userlog");
	$add = array();
	$add["user_action"] 	= $sesiis_username;
	$add["action"] 			= $typeform ;
	$add["target"] 			= 'menu_proses_material';
	$add["description"] 	= $sesiis_username;
	$add["date_action"] 	= $tgl.' '.$wkt;
	
	$insertSQL 	= $db->GetInsertSQL($rs, $add);
	$db->Execute($insertSQL);
	$rs->Close();

				
	echo "{
		'success': true,
		'msg': 'Material File has been successfully processed.'
	}";
	
	// Closing Database Connection
	$db->Close();
?>