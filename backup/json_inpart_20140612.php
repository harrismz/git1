<?php
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	
	//Setting Jam Indonesia //
		date_default_timezone_set('Asia/Jakarta');
		$Ym = gmdate("Y-m");
		$wkt = date('H:i:s');
	// ================= //
/*	
	if( isset($_REQUEST['idfield']) || isset($_REQUEST['srcfield']) ){
		$val_idfield	= trim( str_replace("'", "#", $_REQUEST['idfield']) );
		$val_srcfield	= trim( str_replace("'", "#", $_REQUEST['srcfield']) );
	}else{
		$val_idfield 	= "";
		$val_srcfield 	= "";
	}
	
	if($val_idfield != "" && $val_srcfield != ""){
		$where_clause = "where $val_idfield like '".$val_srcfield."%'";
	}
	else{
		$where_clause = "";
	}
*/	
	$rs = $db->Execute("select * from tbl_inpart where periode = '201405' and jns_dok = '40BC'");
	$return = array();

	for ($i = 0; !$rs->EOF; $i++) {
		
		$return[$i]['jns_dok'] 		= $rs->fields['0'];
		$return[$i]['dp_no'] 		= $rs->fields['1'];
		if($rs->fields['2'] != ""){
			$yyyy 		= substr($rs->fields['2'],0,4);
			$mm 		= substr($rs->fields['2'],5,2);
			$dd 		= substr($rs->fields['2'],8,2);
			$return[$i]['dp_tgl'] 	= $yyyy.'-'.$mm.'-'.$dd;
		}
		$return[$i]['bpb_no'] 		= $rs->fields['3'];
		if($rs->fields['4'] != ""){
			$yyyy 		= substr($rs->fields['4'],0,4);
			$mm 		= substr($rs->fields['4'],5,2);
			$dd 		= substr($rs->fields['4'],8,2);
			$return[$i]['bpb_tgl'] 	= $yyyy.'-'.$mm.'-'.$dd;
		}
		$return[$i]['pemasok'] 		= $rs->fields['5'];
		$return[$i]['partno'] 		= $rs->fields['6'];
		$return[$i]['partname'] 	= $rs->fields['7'];
		$return[$i]['sat'] 			= $rs->fields['8'];
		$return[$i]['jumlah'] 		= floatval($rs->fields['9']);
		$return[$i]['nilai'] 		= floatval($rs->fields['10']);
		
		$rs->MoveNext();
	}
	
	$start = @$_REQUEST["start"];
	$limit = @$_REQUEST["limit"];

	$start = $start ? $start : 0;
	$limit = $limit ? $limit : 25;
	
	if ($start == 0) {
		$finish = $limit;
	} else {
		$finish = $start + $limit;
	}


	$a = array();
	for ($ii = $start; $ii < $finish; $ii++)
	{
		if(isset($return[$ii])) {
			if($return[$ii] != null){ 
				$a[] = $return[$ii];
			}
		}
	}
	
	$o = array(
		"totalCount"=>sizeof($return),
		"rows"=>$a);
	
	echo json_encode($o);
	
	// Closing Database Connection
	$rs->Close();
	$db->Close();
?>