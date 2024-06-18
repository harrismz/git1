<?php
	session_start();
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	error_reporting(0);
	
	//Setting Jam Indonesia //
		date_default_timezone_set('Asia/Jakarta');
		$year 	= gmdate("Y");
		$month 	= gmdate("m");
		$tgl 	= gmdate("Y-m-d");
		$wkt 	= date('H:i:s');
	// ================= //
	
	$upload 			= $_FILES['ufile']['name'];
	$jmltitik			= substr_count($upload, ".");
	$fgood_periode 		= $_REQUEST['fgood_periode'];
	$sesiis_username	= $_SESSION['sesiis_username'];
	$typeform 			= $_REQUEST['typeform'];
	
	if($jmltitik != 1)
	{
		echo "{
			'success': false,
			'msg': 'Incorrect file name.'
		}";	
	}
	else
	{
		$pisah			= explode(".", $upload);
		$nama			= $pisah[0];
		$typefile		= $pisah[1];
	
		if ($typefile != 'txt')
		{
			echo "{
				'success': false,
				'msg': 'Incorrect file typename. <br> Type file must be <b>(.txt)</b>.'
			}";
		}
		else
		{
			if (is_uploaded_file($_FILES['ufile']['tmp_name'])) 
			{
				//	open file
				$TEXT_File 	= $_FILES['ufile']['tmp_name'];
				$feed 		= fopen($TEXT_File, 'r');
				
				set_time_limit(7200);  // Set limit time fpr maximum execute
			
				//	insert data to scrap_upload
				while ($i = fgetcsv($feed, 1000, ",")) 
				{
					$rs 		= $db->Execute("select top 1 modelname from fgood_masterlist where modelno = '". trim( str_replace("'", " ", $i['3']) ) ."'");
					$modelname	= $rs->fields[0];
					$rs->Close();
				
					$ins = array();
					$ins ['KODEBARANG']			= trim( str_replace("'", " ", $i['3']) );
					$ins ['NAMABARANG']			= $modelname;
					$ins ['SAL_AWAL']			= trim( $i['5'] );
					$ins ['MASUK']				= trim( $i['6']);
					$ins ['KELUAR']				= trim( $i['7']);
					$ins ['SAL_AKHIR']			= trim( $i['8']);
					$ins ['FGOOD_PERIODE']		= $fgood_periode;
					
					$rs 		= $db->Execute("select top 1 * from fgood_upload");
					$insertSQL 	= $db->GetInsertSQL($rs, $ins );
					$db->Execute($insertSQL);
					$rs->Close();
				}
			
				//	insert log
				$rs 	= $db->Execute("select * from tbl_userlog");
				$add = array();
				$add["user_action"] 	= $sesiis_username;
				$add["action"] 			= $typeform;
				$add["target"] 			= 'menu_upload_fgood';
				$add["description"] 	= $sesiis_username;
				$add["date_action"] 	= $tgl.' '.$wkt;
				
				$insertSQL 	= $db->GetInsertSQL($rs, $add);
				$db->Execute($insertSQL);
				$rs->Close();
			
				echo "{
					'success': true,
					'msg': 'Finish Good File has been successfully uploaded.'
				}";
			}
		}
	}
	
	// Closing Database Connection
	$db->Close();
?>