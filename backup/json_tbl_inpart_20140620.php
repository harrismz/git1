<?php
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	
	/**	select search **/
	if( isset($_REQUEST['st_date']) || isset($_REQUEST['en_date']) || isset($_REQUEST['jns_dok']) || isset($_REQUEST['dp_no']) || isset($_REQUEST['partno']) || isset($_REQUEST['files']) ){
		$val_st_date 	= trim( str_replace("'", "#", $_REQUEST['st_date']) );
		$val_en_date 	= trim( str_replace("'", "#", $_REQUEST['en_date']) );
		$val_jns_dok	= trim( str_replace("'", "#", $_REQUEST['jns_dok']) );
		$val_refno		= trim( str_replace("'", "#", $_REQUEST['dp_no']) );
		$val_partno		= trim( str_replace("'", "#", $_REQUEST['partno']) );
		$val_files		= trim( str_replace("'", "#", $_REQUEST['files']) );
	}else{
		$val_st_date 	= "";
		$val_en_date 	= "";
		$val_jns_dok 	= "";
		$val_refno 		= "";
		$val_partno 	= "";
		$val_files 		= "";
	}
	
	/**	filter **/
	if($val_st_date != "" or $val_en_date != "" or $val_jns_dok != "" or $val_refno != "" or $val_partno != "" or $val_files != "" ){
		$rs = $db->execute("select
								jns_dok, dp_no, 
								convert(varchar(11), dp_tgl, 113) as dp_tgl, 
								bpb_no, 
								convert(varchar(11), bpb_tgl, 113) as bpb_tgl, 
								pemasok, partno, partname, sat, 
								jumlah, nilai, periode
							from tbl_inpart
							where	convert(varchar(8), dp_tgl, 112) between '". $val_st_date ."' and '". $val_en_date ."' and
									jns_dok like '%". $val_jns_dok ."%' and
									dp_no like '%". $val_refno ."%' and
									partno like '%". $val_partno ."%' and
									files like '%". $val_files ."%'
							order by convert(varchar(8), dp_tgl, 112) asc");
		$return = array();
	}
	else{
		$rs = $db->execute("select
								jns_dok, dp_no, 
								convert(varchar(11), dp_tgl, 113) as dp_tgl, 
								bpb_no, 
								convert(varchar(11), bpb_tgl, 113) as bpb_tgl, 
								pemasok, partno, partname, sat, 
								jumlah, nilai, periode
							from tbl_inpart
							where 	convert(varchar(6), dp_tgl, 112) = (convert(varchar(6), getdate(), 112)-1) and
									jns_dok = '17BC'
							order by convert(varchar(8), dp_tgl, 112) asc");
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
		
		$rs->MoveNext();
	}
	
	$start = @$_REQUEST["start"];
	$limit = @$_REQUEST["limit"];

	$start = $start ? $start : 0;
	$limit = $limit ? $limit : 25;
	
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