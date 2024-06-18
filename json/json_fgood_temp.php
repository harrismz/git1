<?php
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	
	//Setting Jam Indonesia //
		date_default_timezone_set('Asia/Jakarta');
		$Ym1 = gmdate("Ym")-4;
		$Ym2 = gmdate("Ym");
		$wkt = date('H:i:s');
	// ================= //
	

	$max = $db->Execute("select max(fgood_periode) from fgood_upload");
	$maxperiode = intval($max->fields['0']);
	$max->Close();	

	$rs = $db->Execute("select * from fgood_upload where fgood_periode = '". $maxperiode ."' order by kodebarang, fgood_periode asc");
	$return = array();

	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['kodebarang']		= $rs->fields['0'];
		$return[$i]['namabarang'] 		= $rs->fields['1'];
		$return[$i]['sal_awal'] 		= intval($rs->fields['2']);
		$return[$i]['masuk'] 			= intval($rs->fields['3']);
		$return[$i]['keluar'] 			= intval($rs->fields['4']);
		$return[$i]['sal_akhir'] 		= intval($rs->fields['5']);
		$return[$i]['fgood_periode'] 	= $rs->fields['6'];
		
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