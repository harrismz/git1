<?php
	/*
	****	create by Mohamad Yunus
	****	on 13 December 2016
	****	revise: -
	*/
	
	session_start();
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	
	
	//	declare
	$ufile 		= $_FILES['ufile']['name'];
	$files		= $_REQUEST['valfiles'];
	
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
			// //	delete period if same
			// $rs = $db->Execute("delete from tbl_inpart where files = '{$files}'");
			// $rs->Close();			
			
			//	process upload
			if (is_uploaded_file($_FILES['ufile']['tmp_name'])) 
			{
				$CSV_File 	= $_FILES['ufile']['tmp_name'];
				$feed 		= fopen($CSV_File, 'r');
				$no			= 1;
				
				set_time_limit(3600);
				while ($i = fgetcsv($feed, 1000, ",")) 
				{
					if(trim($i['0']) != "jns_dok"){
						$jns_dok 	= trim($i['0']);
						$dp_no		= trim($i['1']);
						$dp_tgl		= trim($i['2']);
						$bpb_no		= trim($i['3']);
						$bpb_tgl	= trim($i['4']);
						$pemasok	= trim($i['5']);
						$partno		= trim($i['6']);
						$partname	= trim($i['7']);
						$sat		= trim($i['8']);
						$jumlah		= trim($i['9']);
						$nilai		= trim($i['10']);
						$periode	= trim($i['11']);
						$files		= 'MANUAL';
						$currency	= trim($i['13']);
						
						$rs = $db->Execute("insert 	into tbl_outpart(jns_dok, dp_no, dp_tgl, bpb_no, bpb_tgl, pemasok, partno, partname, sat, jumlah, nilai, periode, files, currency)
											select	'{$jns_dok}', '{$dp_no}', '{$dp_tgl}', '{$bpb_no}', '{$bpb_tgl}', '{$pemasok}', '{$partno}', '{$partname}', '{$sat}', '{$jumlah}', '{$nilai}', '{$periode}', '{$files}', '{$currency}'");
											
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