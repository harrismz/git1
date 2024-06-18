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
	
	$rs = $db->Execute("select 
							id_log,
							user_action,
							action,
							target,
							description,
							convert(varchar(20), date_action, 120) as date_action
						from tbl_userlog 
						". $where_clause ."
						order by convert(varchar(20), date_action, 120) desc, user_action, action asc");
	$return = array();

	for ($i = 0; !$rs->EOF; $i++) {
		
		$return[$i]['id_log'] 		= $rs->fields['0'];
		$return[$i]['user_action'] 	= $rs->fields['1'];
		$return[$i]['action'] 		= $rs->fields['2'];
		$return[$i]['target'] 		= $rs->fields['3'];
		$return[$i]['description'] 	= $rs->fields['4'];
		$return[$i]['date_action'] 	= $rs->fields['5'];
		
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
	
	/*
	$o = array(
		"success"=>true,
		"rows"=>$return);
	
	echo json_encode($o);
	*/	
	
	// Closing Database Connection
	$rs->Close();
	$db->Close();
?>