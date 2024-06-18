<?php
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
	<title> IIS - SCRAP </title>
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
				//	func color number
					function last(val) {
						if (val > 0) {
							return '<span style="color:green;">' + val + '</span>';
						} else if (val <= 0) {
							return '<span style="color:red;">' + val + '</span>';
						}
						return val;
					}
					
					function rcv(val) {
						if (val > 0) {
							return '<span style="color:green;">' + val + '</span>';
						} else if (val <= 0) {
							return '<span style="color:red;">' + val + '</span>';
						}
						return val;
					}
					
					function adj(val) {
						if (val > 0) {
							return '<span style="color:green;">' + val + '</span>';
						} else if (val <= 0) {
							return '<span style="color:red;">' + val + '</span>';
						}
						return val;
					}
					
					function iss(val) {
						if (val > 0) {
							return '<span style="color:green;">' + val + '</span>';
						} else if (val <= 0) {
							return '<span style="color:red;">' + val + '</span>';
						}
						return val;
					}
					
					function bal(val) {
						if (val > 0) {
							return '<span style="color:green;">' + val + '</span>';
						} else if (val <= 0) {
							return '<span style="color:red;">' + val + '</span>';
						}
						return val;
					}
					
					function stockopname(val) {
						if (val > 0) {
							return '<span style="color:green;">' + val + '</span>';
						} else if (val <= 0) {
							return '<span style="color:red;">' + val + '</span>';
						}
						return val;
					}
					
					function diff(val) {
						if (val > 0) {
							return '<span style="color:green;">' + val + '</span>';
						} else if (val <= 0) {
							return '<span style="color:red;">' + val + '</span>';
						}
						return val;
					}
				//	end of func color number
			//	----***----  //

			//	All about json data
			//	***
				//	json scrap
					var itemperpage = 15;
					
					Ext.define('scrap', {
						extend	: 'Ext.data.Model',
						fields	: [ 'kodebarang', 'namabarang', 'unit',
									'last', 'rcv', 'adj',
									'iss', 'bal', 'stockopname',
									'diff', 'periode', 'remarks'
								  ]
					});
					
					var ds_scrap = Ext.create('Ext.data.Store', {
						model		: 'scrap',
						autoLoad	: true,
						pageSize	: itemperpage,
						fields		: [ 'kodebarang', 'namabarang', 'unit',
										'last', 'rcv', 'adj',
										'iss', 'bal', 'stockopname',
										'diff', 'periode', 'remarks'
									  ],
						proxy		: {
							type	: 'ajax',
							url		: 'json/json_scrap.php',
							reader	: {
								type			: 'json',
								root			: 'rows',
								totalProperty	: 'totalCount'
							}
						}
					});
				//	end of json scrap
				
				//	json cbx field
					Ext.define('field', {
						extend	: 'Ext.data.Model',
						fields	: [ 'idfield', 'srcfield' ]
					});
					
					var ds_cbx_field = Ext.create('Ext.data.Store', {
						model	: 'field',
						data	: [
							{ 'idfield': 'kodebarang', 'srcfield': 'KODE BARANG' },
							{ 'idfield': 'namabarang', 'srcfield': 'NAMA BARANG' }
						]
					});
				//	end of json cbx field
				
				//	json cbx month
					monthsStore = Ext.create('Ext.data.Store', {
						fields: ['num', 'name'],
						data	: [
							{ 'name': 'January - April', 		'num': '04' },
							{ 'name': 'Mei - August', 			'num': '08' },
							{ 'name': 'September - December', 	'num': '12' }
						]
					});
				//	end of json cbx month
			//	----***----  //
			
			
			//	Grid data
			//	***
				//	grid panel
					var clock = Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'g:i:s A')});
					var grd_scrap = Ext.create('Ext.grid.Panel', {
						stateId		: 'grd_scrap',
						renderTo	: 'grd_scrap',
						title		: 'Data Scrap',
						store		: ds_scrap,
						width		: '100%',
						autoHeight	: true,
						x 			: 0, 
						y 			: 0,
						border		: true,
						columnLines	: true,
						viewConfig	: {
							stripeRows			: true,
							enableTextSelection	: true
						},
						//	columns
						columns		: [
							{ header: 'No.', xtype: 'rownumberer', width: 50, height: 40, sortable: false },
							{ header: 'KODE BARANG',		dataIndex: 'kodebarang',	width: 180 },
							{ header: 'NAMA BARANG',		dataIndex: 'namabarang',	width: 180 },
							{ header: 'UNIT',				dataIndex: 'unit',			width: 80 },
							{ header: 'LAST',				dataIndex: 'last',			width: 80, renderer: last, 			align: 'right' },
							{ header: 'RECEIVE',			dataIndex: 'rcv',			width: 80, renderer: rcv, 			align: 'right' },
							{ header: 'ADJUSTMENT',			dataIndex: 'adj',			width: 90, renderer: adj, 			align: 'right' },
							{ header: 'ISSUE',				dataIndex: 'iss',			width: 80, renderer: iss, 			align: 'right' },
							{ header: 'INVENTORY',			dataIndex: 'bal',			width: 80, renderer: bal, 			align: 'right' },
							{ header: 'STOCK<BR>OP NAME',	dataIndex: 'stockopname',	width: 80, renderer: stockopname, 	align: 'right' },
							{ header: 'DIFF',				dataIndex: 'diff',			width: 80, renderer: diff, 			align: 'right' },
							{ header: 'KET',				dataIndex: 'remarks',		flex: 1 }
						],
						//	end columns
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
									ds_scrap.proxy.setExtraParam('periode', '' );
									ds_scrap.proxy.setExtraParam('idfield', '' );
									ds_scrap.proxy.setExtraParam('srcfield', '' );
									Ext.getCmp('lbl_search').setText('');
									ds_scrap.loadPage(1);
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
								id		: 'btn_upload4bulan',
								iconCls	: 'upload',
								text	: 'Upload 4 Bulanan',
								tooltip	: 'Upload 4 Bulanan',
								handler	: fnc_upload4bulan
							}, 
						<?php	}?>
							{
								xtype		: 'label',
								id			: 'lbl_search',
								text		: '',
								margins		: '15 5 0 5'
							}, 
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
							store		: ds_scrap,
							displayInfo	: true,
							plugins		: Ext.create('Ext.ux.ProgressBarPager', {}),
							listeners	: {
								afterrender: function(cmp) {
									cmp.getComponent("refresh").hide();
								}
							}
						})
					});
				//	end of grid panel
			//	----***----  //
			
			
			//	All about form
			//	***
				//	form filter
					function fnc_filter() {
						var win_filter;
							
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
												xtype			: 'combobox',
												id				: 'month',
												name			: 'month',
												fieldLabel		: 'Periode',
												displayField	: 'name',
												valueField		: 'num',
												queryMode		: 'local',
												emptyText		: 'Month',
												margin  		: '5 0 0 0',
												flex			: 1,
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
												id			: 'year',
												name		: 'year',
												fieldLabel	: 'Year',
												flex		: 1,
												margin  	: '5 0 0 0',
												value		: new Date().getFullYear()
											},
											//	type modul
											{
												xtype	: 'hiddenfield',
												id		: 'typemodul',
												name	: 'typemodul',
												value	: 'scrap'
											}
										]
									}
								],
								buttons			: [
									{
										text   	: 'Reset',
										iconCls	: 'refresh',
										handler	: function() {
											this.up('form').getForm().reset();
										}
									}, {
										text	: 'Download',
										iconCls	: 'download',
										handler	: function() {
											var val_month 		= Ext.getCmp('month').getValue();
											var val_year 		= Ext.getCmp('year').getValue();
											var typemodul 		= Ext.getCmp('typemodul').getValue();
											
											window.open('response/downld.php?month='+ val_month +'&year='+ val_year +'&typemodul='+ typemodul +'');
										}
									}, {
										text	: 'Search',
										iconCls	: 'search',
										handler	: function() {
											var vmonth 		= Ext.getCmp('month').getValue();
											var val_year 	= Ext.getCmp('year').getValue();											
											var val_periode = val_year+''+vmonth;
											
											
											switch(vmonth){
												case '04':
													Ext.getCmp('lbl_search').setText('Search by: January - April, ' + val_year);
													break;
												case '08':
													Ext.getCmp('lbl_search').setText('Search by: Mei - August, ' + val_year);
													break;
												case '12':
													Ext.getCmp('lbl_search').setText('Search by: September - December, ' + val_year);
													break;
											}
											
											ds_scrap.proxy.setExtraParam('periode', val_periode );
											ds_scrap.loadPage(1);
											
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
				//	end of form filter
				
				//	form upload4bulan
					function fnc_upload4bulan() {
						var win_upload4bulan;						
						if (!win_upload4bulan) {
							var frm_upload4bulan = Ext.widget('form', {
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
											window.open('response/form_4bln_scrap.php');
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
															url				: 'response/upl_4bln_scrap.php',
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
							
							win_upload4bulan = Ext.widget('window', {
								title			: 'Upload Data 4 Bulanan',
								width			: 450,
								minWidth		: 450,
								height			: 170,
								minHeight		: 170,
								modal			: true,
								constrain		: true,
								layout			: 'fit',
								animateTarget	: 'btn_upload4bulan',
								items			: frm_upload4bulan
							});
						}
						win_upload4bulan.show();
					}
				//	end of form upload4bulan
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
		<div style="float:left;"> SCRAP </div> <div style="float:right"> Welcome: <?=$img .' <b>'. $sesiis_username?> </b></div>
		<br><br><br><br>
		
		<div id="grd_scrap"></div>
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