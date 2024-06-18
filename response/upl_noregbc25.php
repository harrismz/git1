<?php
require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	/*
	****	create by Mohamad Yunus
	****	on 10 February 2017
	****	revise: -
	*/
	
	session_start();
	// include('../../ADODB/con_iis.php');
	
	
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
			//	process upload
			if (is_uploaded_file($_FILES['ufile']['tmp_name'])) 
			{
				$CSV_File 	= $_FILES['ufile']['tmp_name'];
				$feed 		= fopen($CSV_File, 'r');
				$no			= 1;
				
				set_time_limit(3600);
				while ($i = fgetcsv($feed, 1000, ",")) 
				{
					if(trim($i['0']) != "No Aju"){
						$noaju 	= trim($i['0']);
						$noreg	= trim(strtoupper($i['1']));
						
						$rs = $db->Execute("update tbl_outpart set dp_no = '{$noreg}', files = '{$files}' where dp_no = '{$noaju}'");
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