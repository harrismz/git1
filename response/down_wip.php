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
	
	//	execute query
    $sql 	= "declare @totalcount as int; exec downWIP '{$stdate}', '{$endate}', '{$partno}'";
    $rs 	= $db->Execute($sql);
	
	//	save file
	$fname = 'JEIN_WIP.csv';
	
	//	input data in file
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=$fname");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$fp = fopen("php://output", "w");
	$headers = 'Kode Baramg, Nama Barang, Jumlah, Periode' . "\n";
	fwrite($fp,$headers);
	
	while(!$rs->EOF)
	{
	   fputcsv($fp, array(	$rs->fields['0'], $rs->fields['1'], $rs->fields['2'], 
							$rs->fields['3']));
	   
	   $rs->MoveNext();
	} 
	
	//	connection close
	fclose($fp);
	$rs->Close();
    $db->Close();
?>