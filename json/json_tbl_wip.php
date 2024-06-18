<?php
	/*
	****	create by Mohamad Yunus
	****	on 15 December 2016
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
    $sql 	= "declare @totalcount as int; exec displayWIP $start, $limit, '{$stdate}', '{$endate}', '{$partno}', @totalcount=@totalcount out";
    $rs 	= $db->Execute($sql);
    $totalcount = $rs->fields['6'];
	
	//	array data
	$return 	= array();
	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['kodebarang'] 		= $rs->fields['0'];
		$return[$i]['namabarang'] 		= $rs->fields['1'];
		$return[$i]['jumlah'] 			= $rs->fields['2'];
		$return[$i]['scrap_periode']	= $rs->fields['3'];
		$return[$i]['action_user'] 		= $rs->fields['4'];
		$return[$i]['last_update'] 		= $rs->fields['5'];
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