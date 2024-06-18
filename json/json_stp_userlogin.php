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
	
	
	if( isset($_REQUEST['userlevel']) || isset($_REQUEST['idfield']) || isset($_REQUEST['srcfield']) ){
		$val_idfield 	= trim( str_replace("'", "#", $_REQUEST['idfield']) );
		$val_srcfield 	= trim( str_replace("'", "#", $_REQUEST['srcfield']) );
		$val_userlevel	= trim( $_REQUEST['userlevel'] );
	}else{
		$val_idfield 	= "";
		$val_srcfield 	= "";
		$val_userlevel 	= "";
	}
	
	if($val_userlevel != "" && $val_idfield != "" && $val_srcfield != ""){
		$where_clause = "where userlevel like '". $val_userlevel ."%' and $val_idfield like '".$val_srcfield."%'";
	}
	else if($val_userlevel != ""){
		$where_clause = "where userlevel like '". $val_userlevel ."%'";
	}
	else if($val_idfield != "" && $val_srcfield != ""){
		$where_clause = "where $val_idfield like '".$val_srcfield."%'";
	}
	else{
		$where_clause = "";
	}
	
	$rs = $db->Execute("select * from tbl_userlogin ". $where_clause ." order by userlevel, username asc");
	$return = array();

	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['userid'] 		= $rs->fields['0'];
		$return[$i]['username'] 	= $rs->fields['1'];
		$return[$i]['userpass'] 	= $rs->fields['2'];
		$return[$i]['userlevel'] 	= $rs->fields['3'];
		
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