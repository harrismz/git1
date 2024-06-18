<?php
require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	/*
	****	create by Mohamad Yunus
	****	on 13 December 2016
	****	revise: -
	*/
	
	session_start();
	// include('../../ADODB/con_iis.php');
	
	
	//	declare
	$ufile 			= $_FILES['ufile']['name'];
	$periode		= $_REQUEST['valyear'] . $_REQUEST['valmonth'];
	
	//	get ext file bom
	//	***step 1
	$pisah		= explode(".", $ufile);
	$arrays		= (count($pisah)-1);
	$typefile	= strtolower($pisah[$arrays]);
	//	***step 2
	$types 	= 'csv'; 
	$lop	= explode(",",$types); 
	$total	= count($lop);
	//	***step 3
	$validfile	= false;
	for ($i = 0; $i < $total; $i++) {
		if( $typefile == $lop[$i] ){
			$validfile = true;
		}
	}
	
	//	cheking
	if ($validfile == false) {
		echo "{
			'success': false,
			'msg': 'Ext. file (*.$typefile) and incorrect, must be (*.csv).'
		}";
	}
	else{
		//	action
		try
		{
			//	delete period if same
			$rs = $db->Execute("delete from finishgoods where periode = '{$periode}'");
			$rs->Close();			
			
			//	process upload
			if (is_uploaded_file($_FILES['ufile']['tmp_name'])) 
			{
				$CSV_File 	= $_FILES['ufile']['tmp_name'];
				$feed 		= fopen($CSV_File, 'r');
				$no			= 1;
				
				set_time_limit(3600);
				while ($i = fgetcsv($feed, 1000, ",")) 
				{
					if(trim($i['0']) != "No"){
						$no 		= trim($i['0']);
						$kodebarang	= trim($i['1']);
						$namabarang	= trim($i['2']);
						$unit		= trim($i['3']);
						$last		= trim($i['4']);
						$rcv		= trim($i['5']);
						$adj		= trim($i['6']);
						$iss		= trim($i['7']);
						$bal		= trim($i['8']);
						$stokopname	= trim($i['9']);
						$diff		= trim($i['10']);
						
						$rs = $db->Execute("insert 	into finishgoods(no, kodebarang, namabarang, unit, last, rcv, adj, iss, bal, stokopname, diff, periode)
											select	'{$no}', '{$kodebarang}', '{$namabarang}', '{$unit}', '{$last}', '{$rcv}', '{$adj}', '{$iss}', '{$bal}', '{$stokopname}', '{$diff}', '{$periode}'");
						$rs->Close();
					}
				
					$no++;
				}
			}						
			
			//	message
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
				$error 		= str_replace( "'", "`", $err);
				$error_msg 	= str_replace( "[Microsoft][ODBC SQL Server Driver][SQL Server]", "", $error);
				
				echo "{
					'success': false,
					'msg': '$error_msg'
				}";
				break;
				
			case 1:
				echo "{
					'success': true,
					'msg': 'Upload file successful.'
				}";
				break;
		}
	}
	
	// closing database connection
	$db->Close();
?>