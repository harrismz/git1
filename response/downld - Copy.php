<?php
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	
	//Setting Jam Indonesia //
		date_default_timezone_set('Asia/Jakarta');
		$tgl = gmdate("Ymd");
		$wkt = date('H:i:s');
	// ================= //
	

	if( isset($_REQUEST['month']) || isset($_REQUEST['year']) ){
		$val_month 		= trim($_REQUEST['month']);
			if( $val_month >=1 &&  $val_month <=9)
			{
				$val_month = '0'. $val_month;
			}
			else
			{
				$val_month = $val_month;
			}
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
	
	// Closing Database Connection
	$db->Close();
?>