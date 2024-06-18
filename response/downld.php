<?php
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	error_reporting(0);
	
	//Setting Jam Indonesia //
		date_default_timezone_set('Asia/Jakarta');
		$tgl = gmdate("Ymd");
		$wkt = date('H:i:s');
	// ================= //
	

	if( isset($_REQUEST['month']) || isset($_REQUEST['year']) ){
		$val_month 		= trim($_REQUEST['month']);
		$val_year 		= trim($_REQUEST['year']);
	}else{
		$val_month		= "";
		$val_year		= "";
	}
	
	if($val_month != "" && $val_year != ""){
		$where_clause = "where periode like '". $val_year . $val_month ."%'";
	} 
	else{
		$max = $db->Execute("select max(periode) from material");
		$maxperiode = intval($max->fields['0']);
		$max->Close();
	
		$where_clause = "where periode = ".intval($max->fields['0'])."";
	}
	
	
	$typemodul 			= $_REQUEST['typemodul'];
	switch ($typemodul)
	{
		//***
		//response material
		case 'material':
			$rs = $db->execute("select 
									ROW_NUMBER()  OVER (ORDER BY  kodebarang) As no, 
									kodebarang,  
									namabarang, 
									unit,
									last,
									rcv,
									adj,
									iss,
									bal,
									stokopname,
									diff,
									keterangan
								from material 
									".$where_clause." 
								order by periode desc, kodebarang asc");
			
			//	save file
			$fname = $val_year . $val_month.'_MATERIAL.csv';
			
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=$fname");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$fp = fopen("php://output", "w");
			
			$headers = 'NO, KODE BARANG, NAMA BARANG, UNIT, LAST, RECEIVE, ADJUSTMENT, ISSUE, BALANCE, STOCK OP NAME, DIFFERENT, KETERANGAN' . "\n";
			fwrite($fp,$headers);
			
			foreach ($rs as $fields) {
				fputcsv($fp, $fields);
			}	
			fclose($fp);
			$rs->Close();
			
			break;
		
		
		//***
		//response scrap
		case 'scrap':
			$rs = $db->execute("select 
									ROW_NUMBER()  OVER (ORDER BY  kodebarang) As no, 
									kodebarang,  
									namabarang, 
									unit,
									last,
									rcv,
									adj,
									iss,
									bal,
									stokopname,
									diff,
									keterangan
								from scrap 
									".$where_clause." 
								order by periode desc, kodebarang asc");
			
			//	save file
			$fname = $val_year . $val_month.'_SCRAP.csv';
			
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=$fname");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$fp = fopen("php://output", "w");
			
			$headers = 'NO, KODE BARANG, NAMA BARANG, UNIT, LAST, RECEIVE, ADJUSTMENT, ISSUE, BALANCE, STOCK OP NAME, DIFFERENT, KETERANGAN' . "\n";
			fwrite($fp,$headers);
			
			foreach ($rs as $fields) {
				fputcsv($fp, $fields);
			}	
			fclose($fp);
			$rs->Close();
			
			break;
		
		
		//***
		//response finishgoods
		case 'finishgoods':
			$rs = $db->execute("select 
									ROW_NUMBER()  OVER (ORDER BY  kodebarang) As no, 
									kodebarang,  
									namabarang, 
									unit,
									last,
									rcv,
									adj,
									iss,
									bal,
									stokopname,
									diff,
									keterangan
								from finishgoods 
									".$where_clause." 
								order by periode desc, kodebarang asc");
			
			//	save file
			$fname = $val_year . $val_month.'_FINISHGOODS.csv';
			
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=$fname");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$fp = fopen("php://output", "w");
			
			$headers = 'NO, KODE BARANG, NAMA BARANG, UNIT, LAST, RECEIVE, ADJUSTMENT, ISSUE, BALANCE, STOCK OP NAME, DIFFERENT, KETERANGAN' . "\n";
			fwrite($fp,$headers);
			
			foreach ($rs as $fields) {
				fputcsv($fp, $fields);
			}	
			fclose($fp);
			$rs->Close();
			
			break;
		
		
		//***
		//response famachine
		case 'famachine':
			$rs = $db->execute("select 
									ROW_NUMBER()  OVER (ORDER BY  kodebarang) As no, 
									kodebarang,  
									namabarang, 
									unit,
									last,
									rcv,
									adj,
									iss,
									bal,
									stokopname,
									diff,
									keterangan
								from famachine 
									".$where_clause." 
								order by periode desc, kodebarang asc");
			
			//	save file
			$fname = $val_year . $val_month.'_FAMACHINE.csv';
			
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=$fname");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$fp = fopen("php://output", "w");
			
			$headers = 'NO, KODE BARANG, NAMA BARANG, UNIT, LAST, RECEIVE, ADJUSTMENT, ISSUE, BALANCE, STOCK OP NAME, DIFFERENT, KETERANGAN' . "\n";
			fwrite($fp,$headers);
			
			foreach ($rs as $fields) {
				fputcsv($fp, $fields);
			}	
			fclose($fp);
			$rs->Close();
			
			break;
	}
	
	
	
	
	
	
	
/*	
	//	save file
	try
	{
		set_time_limit(3600);
		$rs = $db->Execute("select 
								ROW_NUMBER()  OVER (ORDER BY  kodebarang) As no, 
								kodebarang,  
								namabarang, 
								unit,
								last,
								rcv,
								adj,
								iss,
								bal,
								stokopname,
								diff,
								periode,
								keterangan
							from material 
								".$where_clause." 
							order by periode desc, kodebarang asc");
	
		$fname = 'material.csv';
		$fp = fopen('c:\\xampp\\htdocs\\customs\\download\\'.$fname, 'w');
		foreach ($rs as $fields) {
			fputcsv($fp, $fields);
		}	
		fclose($fp);
		$rs->Close();
		
		$var_msg = 1;
	}
	catch (exception $e)
	{
		$var_msg = $db->ErrorNo();
	}
	
	//	Message
		switch ($var_msg)
		{
			case $db->ErrorNo():
				$err		= $db->ErrorMsg();
				$error_msg 	= str_replace( "'", "`", $err);
				echo "{
					'success': false,
					'msg': '$error_msg'
				}";
				break;
			
			case 1:
				echo "{
					'success': true,
					'msg': '".$fname."'
				}";
				break;
		}
	//	end of message
*/
	
/*	
	try
	{
		set_time_limit(10000);
		$rs = $db->Execute("select 
								ROW_NUMBER()  OVER (ORDER BY  kodebarang) As no, 
								kodebarang,  
								namabarang, 
								unit,
								last,
								rcv,
								adj,
								iss,
								bal,
								stokopname,
								diff,
								periode,
								keterangan
							from material 
							".$where_clause." 
							order by periode desc, kodebarang asc");
	
		$fname = 'material.csv';
		$fp = fopen('c:\\xampp\\htdocs\\customs\\download\\'.$fname, 'w');
		foreach ($rs as $fields) {
			fputcsv($fp, $fields);
		}	
		fclose($fp);
		$rs->Close();
		
		$var_msg = 1;
	}
	catch (exception $e)
	{
		$var_msg = $db->ErrorNo();
	}
	
	//	Message
		switch ($var_msg)
		{
			case $db->ErrorNo():
				$err		= $db->ErrorMsg();
				$error_msg 	= str_replace( "'", "`", $err);
				echo "{
					'success': false,
					'msg': '$error_msg'
				}";
				break;
			
			case 1:
				echo "{
					'success': true,
					'msg': '".$fname."'
				}";
				break;
		}
	//	end of message
*/	
	// Closing Database Connection
	$db->Close();
?>