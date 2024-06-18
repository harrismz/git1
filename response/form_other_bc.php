<?php
	/*
	****	create by Mohamad Yunus
	****	on 13 December 2016
	****	revise: -
	*/
	
	$fname = 'FormatUploadOtherBC.csv';
		
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=$fname");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$fp = fopen("php://output", "w");
	
	$headers = 	'jns_dok,	dp_no,	dp_tgl,	bpb_no,	bpb_tgl,	pemasok,	partno,	partname,	sat,	jumlah,	nilai,	periode,	files,	currency' . "\n";
	fwrite($fp,$headers);
	fclose($fp);
?>