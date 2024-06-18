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
	
	
	$max = $db->Execute("select max(scrap_periode) from scrap_upload");
	$maxperiode = intval($max->fields['0']);
	$max->Close();	

	$rs = $db->Execute("select * from scrap_upload where scrap_periode = '". $maxperiode ."' order by kodebarang, scrap_periode asc");
	$return = array();

	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['kodebarang']		= $rs->fields['0'];
		$return[$i]['namabarang'] 		= $rs->fields['1'];
		$return[$i]['jumlah'] 			= intval($rs->fields['2']);
		$return[$i]['scrap_periode'] 	= $rs->fields['3'];
		
		$rs->MoveNext();
	}
	
	$start = @$_REQUEST["start"];
	$limit = @$_REQUEST["limit"];

	$start = $start ? $start : 0;
	$limit = $limit ? $limit : 15;
	
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