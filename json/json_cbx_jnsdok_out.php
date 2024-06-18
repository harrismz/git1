<?php
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	
	
	$rs = $db->execute("select distinct jns_dok from tbl_outpart 
						where 	periode = (select convert(varchar(6), getdate(), 112)) and jns_dok <> ''
						order by jns_dok asc");
	$return = array();

	for ($i = 0; !$rs->EOF; $i++) {
		
		$return[$i]['jns_dok'] 	= $rs->fields['0'];
		
		$rs->MoveNext();
	}
	
	$o = array(
		"success"=>true,
		"rows"=>$return);
	
	echo json_encode($o);
	
	// Closing Database Connection
	$rs->Close();
	$db->Close();
?>