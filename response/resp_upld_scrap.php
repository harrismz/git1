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
	$scrap_periode 		= $_REQUEST['scrap_periode'];
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
	
		if ($typefile != 'TXT')
		{
			echo "{
				'success': false,
				'msg': 'Incorrect file typename. <br> Type file must be <b>(.TXT)</b>.'
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
				while ($i = fgetcsv($feed, 10000, "|")) 
				{
					if( trim($i['2']) != '** T O T A L **' ){
						if( ($i['1'] != '' && trim($i['1']) != 'NO URUT') || ($i['1'] == '' && trim($i['2']) != '')){
							$ins = array();
							$ins ['KODEBARANG']			= trim( $i['3']);
							$ins ['NAMABARANG']			= trim( $i['2']);
							$ins ['JUMLAH']				= trim( $i['4']);
							$ins ['SCRAP_PERIODE']		= $scrap_periode;
							
							$rs 		= $db->Execute("select top 1 * from scrap_upload");
							$insertSQL 	= $db->GetInsertSQL($rs, $ins );
							$db->Execute($insertSQL);
							$rs->Close();
						}
					}else{
						break;
					}
				}
				
				//	insert log
				$rs 	= $db->Execute("select * from tbl_userlog");
				$add = array();
				$add["user_action"] 	= $sesiis_username;
				$add["action"] 			= $typeform ;
				$add["target"] 			= 'menu_upload_scrap';
				$add["description"] 	= $sesiis_username;
				$add["date_action"] 	= $tgl.' '.$wkt;
				
				$insertSQL 	= $db->GetInsertSQL($rs, $add);
				$db->Execute($insertSQL);
				$rs->Close();
				
				echo "{
					'success': true,
					'msg': 'Scrap File has been successfully uploaded.'
				}";
			}
		}
	}
	
	// Closing Database Connection
	$db->Close();
?>