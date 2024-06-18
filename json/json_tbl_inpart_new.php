<?php
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	
	$page 	= @$_REQUEST["page"];
	$limit 	= @$_REQUEST["limit"];
	$start	= (($page*$limit)-$limit)+1;
	
	/**	select search **/
	if( isset($_REQUEST['st_date']) || isset($_REQUEST['en_date']) || isset($_REQUEST['dp_no']) || isset($_REQUEST['partno']) || isset($_REQUEST['files']) || isset($_REQUEST['jns_dok']) ){
		$val_st_date 	= trim( str_replace("'", "#", $_REQUEST['st_date']) );
		$val_en_date 	= trim( str_replace("'", "#", $_REQUEST['en_date']) );
		$val_refno		= trim( str_replace("'", "#", $_REQUEST['dp_no']) );
		$val_partno		= trim( str_replace("'", "#", $_REQUEST['partno']) );
		$val_files		= trim( str_replace("'", "#", $_REQUEST['files']) );
		$val_jns_dok	= trim( str_replace("'", "#", $_REQUEST['jns_dok']) );
	}else{
		$val_st_date 	= "";
		$val_en_date 	= "";
		$val_refno 		= "";
		$val_partno 	= "";
		$val_files 		= "";
		$val_jns_dok 	= "";
	}
	
	/**	filter **/
	if($val_st_date != "" or $val_en_date != "" or $val_refno != "" or $val_partno != "" or $val_files != "" or $val_jns_dok != "" ){
		set_time_limit(7200);  // Set limit time fpr maximum execute
		
		$rs = $db->execute("declare @totalcount as int
							exec searchinpart $start, $limit,
														'". $val_st_date ."',
														'". $val_en_date ."',
														'". $val_refno ."',
														'". $val_partno ."',
														'". $val_files ."', 
														'". $val_jns_dok ."', 
														@totalcount=@totalcount out");
		$totalcount = $rs->fields['13'];
		$return = array();
	}
	else{
		$rs = $db->execute("declare @totalcount as int
							exec displayinpart $start, $limit, @totalcount=@totalcount out");
		$totalcount = $rs->fields['13'];
		$return = array();
	}

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
		$return[$i]['id'] 		= $rs->fields['14'];
		$return[$i]['up_dp_tgl'] = $rs->fields['15'];
		$return[$i]['up_bpb_tgl']= $rs->fields['16'];
		
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