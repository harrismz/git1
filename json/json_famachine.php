<?php
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	
	$page 	= @$_REQUEST["page"];
	$limit 	= @$_REQUEST["limit"];
	$start	= (($page*$limit)-$limit)+1;
	
	/**	select search **/
	if( isset($_REQUEST['periode']) ){
		$val_periode	= trim( $_REQUEST['periode'] );
	}else{
		$val_periode 	= "";
	}
	
	/**	filter **/
	if($val_periode != ""){
		$rs = $db->execute("declare @totalcount as int
							exec searchFaMachine $start, $limit, '". $val_periode ."', @totalcount=@totalcount out");
		
		$totalcount = $rs->fields['13'];
		$return = array();
	}
	else{
		$rs = $db->execute("declare @totalcount as int
							exec displayFaMachine $start, $limit, @totalcount=@totalcount out");
		$totalcount = $rs->fields['13'];
		$return = array();
	}
	
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
		$return[$i]['ket'] 		= $rs->fields['12'];
		
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