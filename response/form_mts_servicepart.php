<?php
	/*
	****	create by Mohamad Yunus
	****	on 11 December 2016
	****	revise: -
	*/
	
	$fname = 'FormatUploadServicePart.csv';
		
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=$fname");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$fp = fopen("php://output", "w");
	
	$headers = 'Kode Barang, Nama Barang, Saldo Awal, Jumlah Masuk, Jumlah Keluar, Saldo Akhir' . "\n";
	fwrite($fp,$headers);
	fclose($fp);
?>