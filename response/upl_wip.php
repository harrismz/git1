<?php
require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	/*
	****	create by Mohamad Yunus
	****	on 15 December 2016
	****	revise: -
	*/
	
	session_start();
	// include('../../ADODB/con_iis.php');
	
	
	//	declare
	$ufile 			= $_FILES['ufile']['name'];
	$periode		= $_REQUEST['valyear'] . $_REQUEST['valmonth'];
	$action_user	= $_SESSION['sesiis_username'];
	
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
			$rs = $db->Execute("delete from tbl_wip where periode = '{$periode}'");
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
					if(trim($i['0']) != "Kode Barang"){
						$kodebarang = trim($i['0']);
						$namabarang	= trim($i['1']);
						$jumlah		= trim($i['2']);
						
						$rs = $db->Execute("insert 	into tbl_wip(kodebarang, namabarang, jumlah, periode, action_user)
											select	'{$kodebarang}', '{$namabarang}', '{$jumlah}', '{$periode}', '{$action_user}'");
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