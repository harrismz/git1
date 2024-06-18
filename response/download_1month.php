<?php
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	error_reporting(0);
	
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
	/** end of **/
	
	/**	filter **/
	if($val_st_date != "" or $val_en_date != "" or $val_jns_dok != "" or $val_refno != "" or $val_partno != "" or $val_files != "" ){
		$where_clause = "	where	convert(varchar(8), dp_tgl, 112) between '". $val_st_date ."' and '". $val_en_date ."' and
									jns_dok = '". $val_jns_dok ."' and
									dp_no like '%". $val_refno ."%' and
									partno like '%". $val_partno ."%' and
									files like '%". $val_files ."%'";
	}
	/** end of **/
	
	
	$typemodul 			= $_REQUEST['typemodul'];
	switch ($typemodul)
	{
		//***
		//response inpermonth
		case 'inpermonth':
			$rs = $db->execute("select
									jns_dok, dp_no, 
									convert(varchar(11), dp_tgl, 113) as dp_tgl, 
									bpb_no, 
									convert(varchar(11), bpb_tgl, 113) as bpb_tgl, 
									pemasok, partno, partname, sat, 
									jumlah, currency, nilai
								from tbl_inpart
								".$where_clause." 
								order by convert(varchar(8), dp_tgl, 112) asc");
			//	save file
			$fname = $typemodul.'.csv';
			
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=$fname");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$fp = fopen("php://output", "w");
			
			$headers = 'JENIS DOKUMEN, DP NOMOR, DP TANGGAL, BPB NOMOR, BPB TANGGAL, PEMASOK, PARTNO, PARTNAME, SAT, JUMLAH, MATA UANG, NILAI' . "\n";
			fwrite($fp,$headers);
			
			foreach ($rs as $fields) {
				fputcsv($fp, $fields);
			}	
			fclose($fp);
			$rs->Close();
		break;
		/** end of **/
		
		
		//***
		//response outpermonth
		case 'outpermonth':
			$rs = $db->execute("select
									jns_dok, dp_no, 
									convert(varchar(11), dp_tgl, 113) as dp_tgl, 
									bpb_no, 
									convert(varchar(11), bpb_tgl, 113) as bpb_tgl, 
									pemasok, partno, partname, sat, 
									jumlah, currency, nilai
								from tbl_outpart
								".$where_clause." 
								order by convert(varchar(8), dp_tgl, 112) asc");
			//	save file
			$fname = $typemodul.'.csv';
			
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=$fname");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$fp = fopen("php://output", "w");
			
			$headers = 'JENIS DOKUMEN, DP NOMOR, DP TANGGAL, BPB NOMOR, BPB TANGGAL, PEMASOK, PARTNO, PARTNAME, SAT, JUMLAH, MATA UANG, NILAI' . "\n";
			fwrite($fp,$headers);
			
			foreach ($rs as $fields) {
				fputcsv($fp, $fields);
			}	
			fclose($fp);
			$rs->Close();
		break;
		/** end of **/
	}
	
	// Closing Database Connection
	$db->Close();
?>