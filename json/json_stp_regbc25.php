<?php
	/*
	****	create by Mohamad Yunus
	****	on 3 Nov 2016
	****	revise:  -
	*/
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	
	//	get paramater
	$valnoaju	= @$_REQUEST["valnoaju"];
	$valnodaft	= @$_REQUEST["valnodaft"];
	$page		= @$_REQUEST["page"];
	$limit		= @$_REQUEST["limit"];
	$start		= (($page*$limit)-$limit)+1;
	
	//	execute query
	if( isset($_REQUEST['valnoaju']) || isset($_REQUEST['valnodaft']) ){
		$sql 	= "declare @totalcount as int; exec searchRegBC25 $start, $limit, '{$valnoaju}', '{$valnodaft}', @totalcount=@totalcount out";
		$rs 	= $db->Execute($sql);
		$totalcount = $rs->fields['5'];
	}
	else{
		$sql 	= "declare @totalcount as int; exec displayRegBC25 $start, $limit, @totalcount=@totalcount out";
		$rs 	= $db->Execute($sql);
		$totalcount = $rs->fields['5'];
	}
	
	$return = array();
	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['id'] 			= $rs->fields['0'];
		$return[$i]['noaju'] 		= $rs->fields['1'];
		$return[$i]['nodaft'] 		= $rs->fields['2'];
		$return[$i]['action_user']	= $rs->fields['3'];
		$return[$i]['action_date'] 	= $rs->fields['4'];
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