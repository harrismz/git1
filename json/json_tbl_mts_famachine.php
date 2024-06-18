<?php
	/*
	****	create by Mohamad Yunus
	****	on 11 December 2016
	****	revise: -
	*/
	
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');

	
	//	get paramater
	$stdate		= trim(@$_REQUEST["valstdate"]);
	$endate		= trim(@$_REQUEST["valendate"]);
	$partno		= trim(@$_REQUEST["valpartno"]);
    $page		= @$_REQUEST["page"];
	$limit		= @$_REQUEST["limit"];
	$start		= (($page*$limit)-$limit)+1;
	
	//	execute query
    $sql 	= "declare @totalcount as int; exec displayMutasiFAMachine $start, $limit, '{$stdate}', '{$endate}', '{$partno}', @totalcount=@totalcount out";
    $rs 	= $db->Execute($sql);
    $totalcount = $rs->fields['9'];
	
	//	array data
	$return 	= array();
	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['kodebarang'] 		= $rs->fields['0'];
		$return[$i]['namabarang'] 		= $rs->fields['1'];
		$return[$i]['sal_awal'] 		= $rs->fields['2'];
		$return[$i]['masuk'] 			= $rs->fields['3'];
		$return[$i]['keluar'] 			= $rs->fields['4'];
		$return[$i]['sal_akhir'] 		= $rs->fields['5'];
		$return[$i]['famach_periode']	= $rs->fields['6'];
		$return[$i]['action_user'] 		= $rs->fields['7'];
		$return[$i]['last_update'] 		= $rs->fields['8'];
		$rs->MoveNext();
	}
	
	$o = array(
		"success"=>true,
		"totalCount"=>$totalcount,
		"rows"=>$return);
	
	echo json_encode($o);
	
	//	closing database connection
	$rs->Close();
	$db->Close();
?>