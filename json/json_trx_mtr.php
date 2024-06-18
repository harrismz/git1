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
	

	if( isset($_REQUEST['st_periode']) || isset($_REQUEST['en_periode']) || isset($_REQUEST['kodebarang'])){
		$val_st_periode	= trim( $_REQUEST['st_periode'] );
		$val_en_periode	= trim( $_REQUEST['en_periode'] );
		$val_kodebarang	= trim( $_REQUEST['kodebarang'] );
	}else{
		$val_st_periode 	= "";
		$val_en_periode 	= "";
		$val_kodebarang 	= "";
	}
	
	if($val_st_periode != "" && $val_en_periode != "" && $val_kodebarang != ""){
		$from_clause = "from vw_pros_mtr('". $val_st_periode ."', '". $val_en_periode ."')";
		$where_clause = "where kodebarang = '". $val_kodebarang ."'";
	}
	else if($val_st_periode != "" && $val_en_periode != ""){
		$from_clause = "from vw_pros_mtr('". $val_st_periode ."', '". $val_en_periode ."')";
		$where_clause = "";
	}
	else{
		$from_clause = "from vw_pros_mtr('xxx', 'x')";
		$where_clause = "";
	}

	$rs = $db->Execute("select
							*,
							(
								(sal_awal+masuk)-(keluar+sal_akhir)
							) as selisih
						". $from_clause ."
						". $where_clause ."
						order by kodebarang asc");
	$return = array();

	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['kodebarang']	= $rs->fields['0'];
		$return[$i]['namabarang'] 	= $rs->fields['1'];
		$return[$i]['sal_awal'] 	= intval($rs->fields['2']);
		$return[$i]['masuk'] 		= intval($rs->fields['3']);
		$return[$i]['keluar'] 		= intval($rs->fields['4']);
		$return[$i]['sal_akhir'] 	= intval($rs->fields['5']);
		$return[$i]['selisih'] 		= intval($rs->fields['6']);
		
		$rs->MoveNext();
	}
	
	$start = @$_REQUEST["start"];
	$limit = @$_REQUEST["limit"];

	$start = $start ? $start : 0;
	$limit = $limit ? $limit : 10;
	
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