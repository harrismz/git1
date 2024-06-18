<?php
	/*
	****	create by Mohamad Yunus
	****	on 15 December 2016
	****	revise: -
	*/
	
	session_start();
	
	if (!isset($_SESSION['sesiis_userid']))
	{
		echo '<script language="javascript">location.href="index.php";</script>'; 
	}
	else
	{
		include "iconlevel.php";
		$sesiis_username 	= $_SESSION['sesiis_username'];
		$sesiis_userid	 	= $_SESSION['sesiis_userid'];
		$sesmis_level	 	= $_SESSION['sesiis_level'];
?>
<!doctype html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title> IIS - BARANG DALAM PROSES (WIP)  </title>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="shortcut icon" href= "icons/iconcustoms.ico"/>

	<script type="text/javascript" src="css_nav/jquery.js"></script>
	<link type="text/css" href="css_nav/nav.css" rel="stylesheet" />
	<script type="text/javascript">
		$(document).ready(function () {
			
			$('#nav li').hover(
				function () {
					//show its submenu
					$('ul', this).slideDown(500);

				}, 
				function () {
					//hide its submenu
					$('ul', this).slideUp(500);
				}
			);

		});
	</script>
		
	<!-- <link rel="stylesheet" type="text/css" href="../extjs-4.1.1/resources/css/ext-all.css"/> -->
		<link rel="stylesheet" type="text/css" href="./extjs/resources/css/ext-all.css"/>
		<!-- <link rel="stylesheet" type="text/css" href="../extjs-4.1.1/examples/shared/example.css" /> -->
		<link rel="stylesheet" type="text/css" href="./extjs/ext-all.css" />
		<!-- <script type="text/javascript" src="../extjs-4.1.1/ext-all.js"></script> -->
		<script type="text/javascript" src="./extjs/ext-all.js"></script>
	<style type="text/css">
		/* style rows on mouseover */
		.x-grid-row-over .x-grid-cell-inner {
			font-weight: bold;
		}
		/* shared styles for the ActionColumn icons */
		.x-action-col-cell img {
			cursor: pointer;
		}
		.x-grid-row-summary  .x-grid-cell-inner {
            font-weight: bold;
            font-size: 11px;
            padding-bottom: 4px;
        }
		.x-grid-row-summary {
            color:#333;
            background: #f1f2f4;
        }		
		.x-column-header-inner { 
			font-weight: bold;
			text-align: center;
		}
		.x-grid-cell {
			padding: 2px;
		}
		
		
		.search {
             background-image:url(icons/search.png) !important;
        }
		.refresh {
			 background-image:url(icons/refresh.gif) !important;
		}
		.download {
			 background-image:url(icons/download.png) !important;
		}
		.upload {
             background-image:url(icons/upload.png) !important;
        }
		.proses {
             background-image:url(icons/cog.gif) !important;
        }
		.csv {
             background-image:url(icons/csv.png) !important;
        }
	</style>
	
	<script type="text/javascript">
		Ext.Loader.setConfig({enabled: true});
		
		// Ext.Loader.setPath('Ext.ux', '../extjs-4.1.1/examples/ux/');
			Ext.Loader.setPath('Ext.ux', './extjs/examples/ux/');
		Ext.require([
			'Ext.*',
			'Ext.tip.*',
			'Ext.tab.*',
			'Ext.Action',
			'Ext.form.*',
			'Ext.grid.*',
			'Ext.data.*',
			'Ext.util.*',
			'Ext.window.*',
			'Ext.window.MessageBox',
			'Ext.ux.ProgressBarPager',
			'Ext.ux.statusbar.StatusBar',
			'Ext.layout.container.Border',
			'Ext.layout.container.Column',
			'Ext.selection.CheckboxModel'
		]);
		
		Ext.onReady(function(){
			Ext.QuickTips.init();
			
			//	All about function
			//	***
				//	function untuk cost rupiah
					function convertToRupiah(angka)
					{
						var rupiah 		= '';
						var angkarev 	= angka.toString().split('').reverse().join('');
						for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
						return rupiah.split('',rupiah.length-1).reverse().join('');
					}
				//	function untuk column jumlah
					function jumlah(val) {
						if (val > 0) {
							return '<span style="color:green; float:right;">' + convertToRupiah(val) + '</span>';
						} else if (val <= 0) {
							return '<span style="color:red; float:right;">' + convertToRupiah(val) + '</span>';
						}
						return val;
					}				
				//	function required
					var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
			//	----***----  //
		
			//	All about json data
			//	***
				var itemperpage = 15;
				//	json store
					Ext.define('wip',{
					   extend:'Ext.data.Model',
					   fields:[ 'kodebarang', 'namabarang', 'jumlah', 'scrap_periode', 'action_user', 'last_update' ]
					});
					var datastore = Ext.create('Ext.data.JsonStore', {
						model       : 'wip',
						autoLoad    : true,
						pageSize    : itemperpage,
						proxy		: {
							type	: 'ajax',
							url		: 'json/json_tbl_wip.php',
							extraParams: {
								valstdate: '999999',
								valendate: '999999',
							},
							reader	: {
								type			: 'json',
								root			: 'rows',
								totalProperty	: 'totalCount'
							}
						}
					});
					
				// 	month combobox 
					monthsStore = Ext.create('Ext.data.JsonStore', {
						fields: ['name', 'num'],
						data: (function() {
							var data = [];
							Ext.Array.forEach(Ext.Date.monthNames, function(name, i) {
								urut = i + 1;
								if(urut <= 9){
									data[i] = {name: name, num: '0'+urut};
								}
								else{
									data[i] = {name: name, num: urut};
								}
							});
							return data;
						})()
					});
			//	----***----  //
		
			//	Panel
			//	***
				//	form filter
					function fnc_filter() {
						var win_filter;
						var date = new Date();							
						if (!win_filter) {
							var frm_filter = Ext.widget('form', {
								layout			: {
									type	: 'vbox',
									align	: 'stretch'
								},
								border			: false,
								bodyPadding		: 10,

								fieldDefaults	: {
									labelWidth	: 150,
									labelStyle	: 'font-weight:bold',
									msgTarget	: 'side'
								},
								defaults		: {
									margins	: '0 0 10 0'
								},
								items			: [
									{
										xtype			: 'fieldcontainer',
										fieldDefaults	: {
											labelWidth	: 150,
											labelAlign	: 'top',
											labelStyle	: 'font-weight:bold',
											msgTarget	: 'side'
										},
										layout			: 'hbox',
										items			: [
											{
												xtype			: 'datefield',
												id				: 'stdate',
												name			: 'stdate',
												fieldLabel		: 'START DATE',
												format			: 'Ymd',
												value			: Ext.Date.format(new Date(date.getFullYear(), date.getMonth(), 1), 'Ymd'),
												editable		: false,
												margin  		: '5 0 0 0',
												flex			: 1,
												listeners 		: {
													change	: function(f,new_val) {
														Ext.getCmp('en_date').setMinValue( Ext.getCmp('st_date').getValue() );
													}
												}
											}, {
												xtype		: 'splitter'
											}, {
												xtype			: 'datefield',
												id				: 'endate',
												name			: 'endate',
												fieldLabel		: 'END DATE',
												format			: 'Ymd',
												value			: Ext.Date.format(new Date(), 'Ymd'),
												editable		: false,
												margin  		: '5 0 0 0',
												flex			: 1
											}
										]
									}, {
										xtype				: 'textfield',
										id					: 'partno',
										name				: 'partno',
										fieldLabel			: 'PART NUMBER',
										listeners 			: {
											change	: function(f,new_val) {
												f.setValue(new_val.toUpperCase());
											}
										}
									}
								],
								buttons			: [
									{
										xtype	: 'button',
										id		: 'btn_download',
										iconCls	: 'download',
										text 	: 'Download',
										tooltip	: 'Download',
										handler : function (){
											var stdate 	= Ext.Date.format(Ext.getCmp('stdate').getValue(), 'Ym');
											var endate 	= Ext.Date.format(Ext.getCmp('endate').getValue(), 'Ym');
											var partno 	= Ext.getCmp('partno').getValue();
											window.open('response/down_wip.php?valstdate='+stdate+'&valendate='+endate+'&valpartno='+partno+'');
										}
									}, {
										text	: 'Search',
										iconCls	: 'search',
										handler	: function() {
											var stdate 	= Ext.Date.format(Ext.getCmp('stdate').getValue(), 'Ym');
											var endate 	= Ext.Date.format(Ext.getCmp('endate').getValue(), 'Ym');
											var partno 	= Ext.getCmp('partno').getValue();
										
											datastore.proxy.setExtraParam('valstdate', stdate);
											datastore.proxy.setExtraParam('valendate', endate);
											datastore.proxy.setExtraParam('valpartno', partno );
											datastore.loadPage(1);
											
											var vwst_date = Ext.Date.format(Ext.getCmp('stdate').getValue(), 'd M Y');
											var vwen_date = Ext.Date.format(Ext.getCmp('endate').getValue(), 'd M Y');
											
											if( vwst_date != '' && vwen_date != '' ){
												document.getElementById("lbl_date").innerHTML = 'Search by Period Date <br> <b>'+vwst_date+' &nbsp;s.d&nbsp; '+vwen_date+'</b>';
											}
											
											if( partno != ''){
												document.getElementById("lbl_partno").innerHTML = 'Search by Part Number <br> <b>'+partno+'</b>';
											}
											
											var popwindow = this.up('window');
											popwindow.close();
										}
									}
								]
							});
							
							win_filter = Ext.widget('window', {
								title			: 'Search by field',
								width			: 450,
								minWidth		: 450,
								height			: 170,
								minHeight		: 170,
								modal			: true,
								constrain		: true,
								layout			: 'fit',
								animateTarget	: 'btn_filter',
								items			: frm_filter
							});
						}
						win_filter.show();
					}
				
				//	form upload
					function fnc_upload() {
						var win_upload;						
						if (!win_upload) {
							var frm_upload = Ext.widget('form', {
								layout			: {
									type	: 'vbox',
									align	: 'stretch'
								},
								border			: false,
								bodyPadding		: 10,

								fieldDefaults	: {
									labelWidth	: 150,
									labelStyle	: 'font-weight:bold',
									msgTarget	: 'side'
								},
								defaults		: {
									margins	: '0 0 10 0'
								},
								items			: [
									{
										xtype			: 'fieldcontainer',
										fieldDefaults	: {
											labelWidth	: 150,
											labelAlign	: 'top',
											labelStyle	: 'font-weight:bold',
											msgTarget	: 'side'
										},
										layout			: 'hbox',
										items			: [
											{
												xtype			: 'combobox',
												id				: 'valmonth',
												name			: 'valmonth',
												displayField	: 'name',
												valueField		: 'num',
												queryMode		: 'local',
												margins			: '0 6 0 0',
												fieldLabel		: 'MONTH',
												flex			: 1,
												allowBlank		: false,
												forceSelection	: true,
												store			: monthsStore,
												listeners: {
													afterrender: function(combo) {
														var recordSelected = combo.getStore().getAt(0);                     
														combo.setValue(recordSelected.get('num'));
													}
												}
											}, {
												xtype		: 'splitter'
											}, {
												xtype		: 'numberfield',
												id			: 'valyear',
												name		: 'valyear',
												fieldLabel	: 'YEAR',
												allowBlank	: false,
												width		: 150,
												value		: new Date().getFullYear()
											}
										]
									}, {
										xtype				: 'fileuploadfield',
										id					: 'ufile',
										name				: 'ufile',
										fieldLabel			: 'File (*.CSV)',
										flex				: 1,
										buttonConfig		: {
											text	: '...'
										}
									}
								],
								buttons			: [
									{
										xtype	: 'button',
										id		: 'btn_csv',
										iconCls	: 'csv',
										text 	: 'Format CSV',
										tooltip	: 'Format CSV',
										handler : function (){
											window.open('response/form_wip.php');
										}
									}, {
										text		: 'Upload',
										iconCls		: 'upload',
										formBind	: true,
										handler		: function() {
											var form = this.up('form').getForm();
											var popwindow = this.up('window');
											var period = Ext.getCmp('valyear').getValue()+'-'+Ext.getCmp('valmonth').getValue();										
											Ext.Msg.confirm('Upload Data', 'Periode: <b>'+period+'</b> <br> Apakah Anda yakin ingin melakukan proses ini ?', function(btn){
												if (btn == 'yes'){
													if (form.isValid()) {
														form.submit({
															url				: 'response/upl_wip.php',
															waitMsg			: 'sending data',
															submitEmptyText	: false,																
															success	: function(form, action) {
																popwindow.close();
																Ext.Msg.show({
																	title		:'Upload Data',
																	icon		: Ext.Msg.INFO,
																	msg			: action.result.msg,
																	buttons		: Ext.Msg.OK
																});
															},
															failure	: function(form, action) {
																Ext.Msg.show({
																	title		:'Upload Data',
																	icon		: Ext.Msg.ERROR,
																	msg			: action.result.msg,
																	buttons		: Ext.Msg.OK
																});
															}
														});
													}
												}
											});
										}
									}
								]
							});
							
							win_upload = Ext.widget('window', {
								title			: 'Upload Data WIP',
								width			: 450,
								minWidth		: 450,
								height			: 170,
								minHeight		: 170,
								modal			: true,
								constrain		: true,
								layout			: 'fit',
								animateTarget	: 'btn_upload',
								items			: frm_upload
							});
						}
						win_upload.show();
					}
							
				//	grid
					var clock = Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'g:i:s A')});
					Ext.create('Ext.grid.Panel', {
						renderTo	: 'panelgrid',
						title		: 'Data WIP',
						store		: datastore,
						width		: '100%',
						autoHeight	: true,
						columnLines	: true,
						multiSelect	: true,
						viewConfig	: {
							stripeRows			: true,
							enableTextSelection	: true
						},
						columns: [
							{ header: 'NO.', xtype: 'rownumberer', width: 50, height: 40, sortable: false },
							{ header: 'KODE BARANG', 	dataIndex: 'kodebarang',	flex: 1 },
							{ header: 'NAMA BARANG', 	dataIndex: 'namabarang',	flex: 2 },
							{ header: 'JUMLAH', 		dataIndex: 'jumlah',		flex: 1, 	align: 'right', renderer : jumlah },
							{ header: 'PERIODE', 		dataIndex: 'scrap_periode',	flex: 1, 	align: 'center' }
						<?php 	if( $sesmis_level == 'ADMIN' ){ ?>
							,{ header: 'LAST UPDATE', 		dataIndex: 'last_update', 	width: 150,	align: 'center' },
							{ header: 'INPUT BY', 			dataIndex: 'action_user', 	flex: 1, 	align: 'center' }
						<?php	}?>
						],
						listeners: {
							render: {
								fn: function(){
									Ext.fly(clock.getEl().parent()).addCls('x-status-text-panel').createChild({cls:'spacer'});

								 Ext.TaskManager.start({
									 run: function(){
										 Ext.fly(clock.getEl()).update(Ext.Date.format(new Date(), 'g:i:s A'));
									 },
									 interval: 1000
								 });
								},
								delay: 100
							}
						},
						tbar		: [
							{
								xtype	: 'button',
								id		: 'btn_refresh',
								iconCls	: 'refresh',
								text 	: 'Refresh',
								tooltip	: 'Refresh',
								handler : function (){
									datastore.proxy.setExtraParam('valstdate', '999999');
									datastore.proxy.setExtraParam('valendate', '999999');
									datastore.proxy.setExtraParam('valpartno', '' );
									datastore.loadPage(1);
									
									document.getElementById("lbl_date").innerHTML = '';
									document.getElementById("lbl_partno").innerHTML = '';
								}
							}, {
								xtype	: 'button',
								id		: 'btn_filter',
								iconCls	: 'search',
								text	: 'Search',
								tooltip	: 'Search',
								handler	: fnc_filter
							},  
						<?php 	if( $sesmis_level == 'ADMIN' ){ ?>
							{
								xtype	: 'button',
								id		: 'btn_upload',
								iconCls	: 'upload',
								text	: 'Upload',
								tooltip	: 'Upload',
								handler	: fnc_upload
							}, 
						<?php	}?>
							'->',
							{
								xtype		: 'label',
								text		: Ext.Date.format(new Date(), 'l, d F Y'),
								margins		: '15 5 0 5'
							}, 
							'-',
							clock
						],
						bbar		: Ext.create('Ext.PagingToolbar', {
							pageSize	: itemperpage,
							store		: datastore,
							displayInfo	: true,
							plugins		: Ext.create('Ext.ux.ProgressBarPager', {}),
							listeners	: {
								afterrender: function(cmp) {
									cmp.getComponent("refresh").hide();
								}
							}
						})
					});
			//	----***----  //
		});
	</script>
</head>
<body id="body">

<header>
	<?php include "header.php"; ?>
</header>

<nav>
	<div id="menu">
		<?php include "glb_menu.php"; ?>
	</div>
</nav>

<section>
	<div class="wrapper_section">
		<div id="separator"></div>
		<div style="float:left;"> BARANG DALAM PROSES (WIP) </div> <div style="float:right"> Welcome: <?=$img .' <b>'. $sesiis_username?> </b></div>
		<br><br>
			<table width="100%" border="0">
				<tr bgcolor="ebf4ff">
					<td align="center" width="50%"> <label id="lbl_date"></label> </td>
					<td align="center" width="50%"> <label id="lbl_partno"></label> </td>
				</tr>
			</table>
		<br>
		
		<div id="panelgrid"></div>
	</div>
</section>

<footer>
	<?php include "footer.php"; ?>
</footer>

</body>
</html>
<?php		
	}
?>