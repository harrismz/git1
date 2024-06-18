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
	

	if( isset($_REQUEST['periode']) ){
		$val_periode	= trim( $_REQUEST['periode'] );
	}else{
		$val_periode 	= "";
	}
	
	if($val_periode != ""){
		$where_clause = "where periode like '". $val_periode ."%'";
	}
	else{
		$max = $db->Execute("select max(periode) from scrap");
		$maxperiode = intval($max->fields['0']);
		$max->Close();
	
		$where_clause = "where periode = ".intval($max->fields['0'])."";
	}

	$rs = $db->Execute("select * from scrap ".$where_clause." order by periode desc, kodebarang asc");
	$return = array();

	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['kodebarang']	= $rs->fields['1'];
		$return[$i]['namabarang'] 	= $rs->fields['2'];
		$return[$i]['unit'] 		= $rs->fields['3'];
		$return[$i]['last'] 		= floatval($rs->fields['4']);
		$return[$i]['rcv'] 			= floatval($rs->fields['5']);
		$return[$i]['adj'] 			= floatval($rs->fields['6']);
		$return[$i]['iss'] 			= intval($rs->fields['7']);
		$return[$i]['bal'] 			= floatval($rs->fields['8']);
		$return[$i]['stockopname'] 	= floatval($rs->fields['9']);
		$return[$i]['diff'] 		= floatval($rs->fields['10']);
		$return[$i]['periode'] 		= intval($rs->fields['11']);
		$return[$i]['ket'] 			= $rs->fields['12'];
		
		$rs->MoveNext();
	}
	
	$start = @$_REQUEST["start"];
	$limit = @$_REQUEST["limit"];

	$start = $start ? $start : 0;
	$limit = $limit ? $limit : 50;
	
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