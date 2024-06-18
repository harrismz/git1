<?php
	session_start();
	require_once('../../adodb5/adodb.inc.php');
require_once('../../adodb5/adodb-exceptions.inc.php');
require_once('../../adodb5/adodb-errorpear.inc.php');
require_once('../extjs/con_iis.php');
	
	$typeform 			= $_REQUEST['typeform'];
	switch ($typeform)
	{
	//***
	//response add
		case 'add':
			$jns_dok	= $_REQUEST['jns_dok'];
			$dp_no		= $_REQUEST['dp_no'];
			$dp_tgl		= $_REQUEST['dp_tgl'];
			$bpb_no		= $_REQUEST['bpb_no'];
			$bpb_tgl	= $_REQUEST['bpb_tgl'];
			$pemasok	= $_REQUEST['pemasok'];
			$partno		= $_REQUEST['partno'];
			$partname	= $_REQUEST['partname'];
			$sat		= $_REQUEST['sat'];
			$jumlah		= $_REQUEST['jumlah'];
			$currency	= $_REQUEST['currency'];
			$nilai		= $_REQUEST['nilai'];
			$sesmis_username 	= $_SESSION['sesiis_username'];
			
			
			try
			{
				$rs 	= $db->Execute("exec addInpart 	'".$jns_dok."', '".$dp_no."', '".$dp_tgl."', '".$bpb_no."', '".$bpb_tgl."', '".$pemasok."', 
														'".$partno."',  '".$partname."',  '".$sat."',  '".$jumlah."',  '".$currency."', '".$nilai."', 
														'".$sesmis_username."'");
				
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
							'msg': 'Data has been saved.'
						}";
						break;
				}
			//	end of message
			
			break;
	//==== end ====//
	
	//***
	//response update
		case 'update':
			$id			= $_REQUEST['id'];
			$jns_dok	= $_REQUEST['jns_dok'];
			$dp_no		= $_REQUEST['dp_no'];
			$dp_tgl		= $_REQUEST['dp_tgl'];
			$bpb_no		= $_REQUEST['bpb_no'];
			$bpb_tgl	= $_REQUEST['bpb_tgl'];
			$pemasok	= $_REQUEST['pemasok'];
			$partno		= $_REQUEST['partno'];
			$partname	= $_REQUEST['partname'];
			$sat		= $_REQUEST['sat'];
			$jumlah		= $_REQUEST['jumlah'];
			$currency	= $_REQUEST['currency'];
			$nilai		= $_REQUEST['nilai'];
			$sesmis_username 	= $_SESSION['sesiis_username'];
			
			
			try
			{
				$rs 	= $db->Execute("exec updInpart 	'".$jns_dok."', '".$dp_no."', '".$dp_tgl."', '".$bpb_no."', '".$bpb_tgl."', '".$pemasok."', 
														'".$partno."',  '".$partname."',  '".$sat."',  '".$jumlah."',  '".$currency."', '".$nilai."', 
														'".$sesmis_username."', '".$id."'");
				
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
							'msg': 'Data has been saved.'
						}";
						break;
				}
			//	end of message
			
			break;
	//==== end ====//
	
	//***
	//response delete
		case 'delete':
			$id			= $_REQUEST['id'];
			
			
			try
			{
				$rs 	= $db->Execute("exec delInpart 	'".$id."'");
				
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
							'msg': 'Data has been deleted.'
						}";
						break;
				}
			//	end of message
			
			break;
	//==== end ====//
	}
	
	// Closing Database Connection
	$db->Close();
?>