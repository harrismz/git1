<?php
	error_reporting(0);
	include('../../ADODB/con_edi.php');
	
	
	
	$rs = $db_edi->Execute("select suppname, suppcode from supplier where suppname like '%".$_REQUEST['suppname']."%' order by suppcode asc");
	$return = array();
	

	for ($i = 0; !$rs->EOF; $i++) {
		$return[$i]['suppname'] 	= $rs->fields['0'];
		$return[$i]['suppcode'] 	= $rs->fields['1'];
		
		$rs->MoveNext();
	}
	
	$o = array(
		"success"=>true,
		"rows"=>$return);
	
	echo json_encode($o);
	
	// Closing Database Connection
	$rs->Close();
	$db_edi->Close();
?>