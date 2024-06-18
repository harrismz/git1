<?php
	session_start();
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	
	//Setting Jam Indonesia //
		date_default_timezone_set('Asia/Jakarta');
		$year = gmdate("Y");
		$month = gmdate("m");
		$wkt = date('H:i:s');
	// ================= //
	
	$upload 		= $_FILES['ufile']['name'];
	$jmltitik		= substr_count($upload, ".");
	
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
	
		if ($typefile != 'csv')
		{
			echo "{
				'success': false,
				'msg': 'Incorrect file typename. <br> Type file must be <b>(.csv)</b>.'
			}";
		}
		else
		{
			if( $month == 01 || $month == 05 || $month == 09 ){
				if (is_uploaded_file($_FILES['ufile']['tmp_name'])) 
				{
					// delete tabel
					$rs_del = $db->Execute("delete from materialtemp");
					$rs_del->Close();
				
					//	Set limit time fpr maximum execute
					set_time_limit(7200);
					
					// insert data to hqttemp
					$CSV_File 	= $_FILES['ufile']['tmp_name'];
					$feed 		= fopen($CSV_File, 'r');
					
					while ($i = fgetcsv($feed, 10000, ",")) 
					{
						if( is_numeric( trim( $i['0']) ) ){
							if( $month == 01 ){
								$bln = 12;
								$year = ($year-1);
								if ($bln <= 9){ $bln = '0'.$bln; }else{ $bln = $bln; }

								$ins = array();
								$ins ['NO']				= trim( $i['0']);
								$ins ['KODEBARANG']		= trim( $i['1']);
								$ins ['NAMABARANG'] 	= trim( $i['2']);
								$ins ['UNIT'] 			= trim( $i['3']);
								$ins ['LAST'] 			= trim( $i['4']);
								$ins ['RCV'] 			= trim( $i['5']);
								$ins ['ADJ'] 			= trim( $i['6']);
								$ins ['ISS'] 			= trim( $i['7']);
								$ins ['BAL'] 			= trim( $i['8']);
								$ins ['STOKOPNAME'] 	= trim( $i['9']);
								$ins ['DIFF'] 			= trim( $i['10']);
								$ins ['KETERANGAN'] 	= trim( $i['11']);
								$ins ['PERIODE'] 		= $year.''.$bln;
								
								$rsinsert 	= $db->Execute("select top 1 * from materialtemp");
								$insertSQL 	= $db->GetInsertSQL($rsinsert, $ins );
								$db->Execute($insertSQL);
								$rsinsert->Close();
							}
							elseif( $month == 05 ){
								$bln = ($month-1);
								if ($bln <= 9){ $bln = '0'.$bln; }else{ $bln = $bln; }

								$ins = array();
								$ins ['NO']				= trim( $i['0']);
								$ins ['KODEBARANG']		= trim( $i['1']);
								$ins ['NAMABARANG'] 	= trim( $i['2']);
								$ins ['UNIT'] 			= trim( $i['3']);
								$ins ['LAST'] 			= trim( $i['4']);
								$ins ['RCV'] 			= trim( $i['5']);
								$ins ['ADJ'] 			= trim( $i['6']);
								$ins ['ISS'] 			= trim( $i['7']);
								$ins ['BAL'] 			= trim( $i['8']);
								$ins ['STOKOPNAME'] 	= trim( $i['9']);
								$ins ['DIFF'] 			= trim( $i['10']);
								$ins ['KETERANGAN'] 	= trim( $i['11']);
								$ins ['PERIODE'] 		= $year.''.$bln;
								
								$rsinsert 	= $db->Execute("select top 1 * from materialtemp");
								$insertSQL 	= $db->GetInsertSQL($rsinsert, $ins );
								$db->Execute($insertSQL);
								$rsinsert->Close();
							}
							elseif( $month == 09 ){
								$bln = ($month-1);
								if ($bln <= 9){ $bln = '0'.$bln; }else{ $bln = $bln; }

								$ins = array();
								$ins ['NO']				= trim( $i['0']);
								$ins ['KODEBARANG']		= trim( $i['1']);
								$ins ['NAMABARANG'] 	= trim( $i['2']);
								$ins ['UNIT'] 			= trim( $i['3']);
								$ins ['LAST'] 			= trim( $i['4']);
								$ins ['RCV'] 			= trim( $i['5']);
								$ins ['ADJ'] 			= trim( $i['6']);
								$ins ['ISS'] 			= trim( $i['7']);
								$ins ['BAL'] 			= trim( $i['8']);
								$ins ['STOKOPNAME'] 	= trim( $i['9']);
								$ins ['DIFF'] 			= trim( $i['10']);
								$ins ['KETERANGAN'] 	= trim( $i['11']);
								$ins ['PERIODE'] 		= $year.''.$bln;
								
								$rsinsert 	= $db->Execute("select top 1 * from materialtemp");
								$insertSQL 	= $db->GetInsertSQL($rsinsert, $ins );
								$db->Execute($insertSQL);
								$rsinsert->Close();
							}
							/*
								$ins = array();
								$ins ['NO']				= trim( $i['0']);
								$ins ['KODEBARANG']		= trim( $i['1']);
								$ins ['NAMABARANG'] 	= trim( $i['2']);
								$ins ['UNIT'] 			= trim( $i['3']);
								$ins ['LAST'] 			= trim( $i['4']);
								$ins ['RCV'] 			= trim( $i['5']);
								$ins ['ADJ'] 			= trim( $i['6']);
								$ins ['ISS'] 			= trim( $i['7']);
								$ins ['BAL'] 			= trim( $i['8']);
								$ins ['STOKOPNAME'] 	= trim( $i['9']);
								$ins ['DIFF'] 			= trim( $i['10']);
								$ins ['KETERANGAN'] 	= trim( $i['11']);
								$ins ['PERIODE'] 		= trim( $i['11']);
								
								$rsinsert 	= $db->Execute("select top 1 * from materialtemp");
								$insertSQL 	= $db->GetInsertSQL($rsinsert, $ins );
								$db->Execute($insertSQL);
								$rsinsert->Close();
							*/
						}
					}
				
					echo "{
						'success': true,
						'msg': 'Material File has been successfully uploaded.'
					}";
				}
			}
			else
			{
				echo "{
					'success': false,
					'msg': 'Out of month for uploading files.'
				}";
			}
		}
	}
	
	// Closing Database Connection
	$db->Close();
?>