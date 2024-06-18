<?php
	/*
	****	create by Mohamad Yunus
	****	on 3 Nov 2016
	****	revise:  -
	*/
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	//error_reporting(0);
	
	//session
	session_start();
	$sesiis_username	= $_SESSION['sesiis_username'];
	
	$typeform 			= $_REQUEST['typeform'];
	switch ($typeform)
	{
		//***
		//response add
		case 'add':
			//	declare
			$noaju 		= @$_REQUEST["valnoaju"];
			$nodaft 	= @$_REQUEST["valnodaft"];
			
			//	execute query
			try
			{
				$sql 	= "insert into tbl_regbc25(noaju, nodaft, action_user) select '{$noaju}', '{$nodaft}', '{$sesiis_username}'";
				$ins 	= $db->Execute($sql);
				$ins->Close();
				
				$var_msg = 1;
				
			}
			catch (exception $e)
			{
				$var_msg = $db->ErrorNo();
			}
		
			//	message
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
						'msg': 'Data has been input.'
					}";
					break;
			}
		break;
		
		//***
		//response upload
		case 'upload':
			//	declare
			$ufile 		= $_FILES['ufile']['name'];
			
			//	get ext file
			//	***step 1
			$pisah		= explode(".", $ufile);
			$arrays		= (count($pisah)-1);
			$typefile	= $pisah[$arrays];
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
							if( trim($i['0']) != 'No Aju' ){
								$noaju 				= trim($i['0']);
								$nodaft 			= trim($i['1']);
								
								$sql 	= "insert into tbl_regbc25(noaju, nodaft, action_user) select '{$noaju}', '{$nodaft}', '{$sesiis_username}'";
								$ins 	= $db->Execute($sql);
								$ins->Close();
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
				//	end of message
			}
		break;
		
		//***
		//response del
		case 'del':
			//	declare
			$id 		= @$_REQUEST["id"];
			
			//	execute query
			try
			{
				$sql 	= "delete from tbl_regbc25 where id = '{$id}'";
				$ins 	= $db->Execute($sql);
				$ins->Close();
				
				$var_msg = 1;
				
			}
			catch (exception $e)
			{
				$var_msg = $db->ErrorNo();
			}
		
			//	message
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
						'msg': 'Data has been delete.'
					}";
					break;
			}
		break;
	}
	
	// Closing Database Connection
	$db->Close();
?>