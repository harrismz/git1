<?php	/*	****	create by Mohamad Yunus	****	on 10 February 2017	****	revise: -	*/		$fname = 'FormatUploadNoRegBC25.csv';			header("Content-type: text/csv");	header("Content-Disposition: attachment; filename=$fname");	header("Pragma: no-cache");	header("Expires: 0");		$fp = fopen("php://output", "w");		$headers = 	'No Aju, No Register' . "\n";	fwrite($fp,$headers);	fclose($fp);?>