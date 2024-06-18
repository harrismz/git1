<?php
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	
	$page 	= @$_REQUEST["page"];
	$limit 	= @$_REQUEST["limit"];
	
	$start	= (($page*$limit)-$limit)+1;
	
	$rs = $db->execute("declare @totalcount as int
						exec pageSalaries $start, $limit, @totalcount=@totalcount out");
	$totalcount = $rs->fields['14'];
	$return = array();

	for ($i = 0; !$rs->EOF; $i++) {
		
		$return[$i]['jns_dok'] 	= $rs->fields['0'];
		$return[$i]['dp_no'] 	= $rs->fields['1'];
		$return[$i]['dp_tgl'] 	= $rs->fields['2'];
		$return[$i]['bpb_no'] 	= $rs->fields['3'];
		$return[$i]['bpb_tgl'] 	= $rs->fields['4'];
		$return[$i]['pemasok'] 	= $rs->fields['5'];
		$return[$i]['partno'] 	= $rs->fields['6'];
		$return[$i]['partname'] = $rs->fields['7'];
		$return[$i]['sat'] 		= $rs->fields['8'];
		$return[$i]['jumlah'] 	= floatval($rs->fields['9']);
		$return[$i]['nilai'] 	= floatval($rs->fields['10']);
		$return[$i]['periode'] 	= $rs->fields['11'];
		$return[$i]['currency'] = $rs->fields['12'];
		$return[$i]['ID'] 		= $rs->fields['13'];
		
		$rs->MoveNext();
	}
	
	
	
	$o = array(
		"success"=>true,
		"totalCount"=>$totalcount,
		"rows"=>$return);
	
	echo json_encode($o);

	
	
	// Closing Database Connection
	$rs->Close();
	$db->Close();
?>