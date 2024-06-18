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
?>
<!doctype html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title> IIS - MACHINE </title>
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
					var itemperpage = 50;
					
					Ext.define('scrap', {
						extend	: 'Ext.data.Model',
						fields	: [ 'kodebarang', 'namabarang', 'unit',
									'last', 'rcv', 'adj',
									'iss', 'bal', 'stockopname',
									'diff', 'periode', 'ket'
								  ]
					});
					
					var ds_scrap = Ext.create('Ext.data.Store', {
						model		: 'scrap',
						autoLoad	: true,
						pageSize	: itemperpage,
						fields		: [ 'kodebarang', 'namabarang', 'unit',
										'last', 'rcv', 'adj',
										'iss', 'bal', 'stockopname',
										'diff', 'periode', 'ket'
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
							{ 'name': 'January', 	'num': '1' },
							{ 'name': 'Mei', 		'num': '5' },
							{ 'name': 'September', 	'num': '9' }
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
						title		: 'Data Machine',
						store		: ds_scrap,
						width		: '100%',
						height		: 500,
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
							{ header: 'Kode Barang',		dataIndex: 'kodebarang',	width: 180 },
							{ header: 'Nama Barang',		dataIndex: 'namabarang',	width: 180 },
							{ header: 'Unit',				dataIndex: 'unit',			width: 100 },
							{ header: 'Last',				dataIndex: 'last',			width: 80, renderer: last, 		align: 'right' },
							{ header: 'Receive',			dataIndex: 'rcv',			width: 80, renderer: rcv, 		align: 'right' },
							{ header: 'Adjustment',			dataIndex: 'adj',			width: 80, renderer: adj, 		align: 'right' },
							{ header: 'Issue',				dataIndex: 'iss',			width: 80, renderer: iss, 		align: 'right' },
							{ header: 'Inventory',			dataIndex: 'bal',			width: 80, renderer: bal, 		align: 'right' },
							{ header: 'Stock<br>Op Name',	dataIndex: 'stockopname',	width: 80, renderer: stockopname, align: 'right' },
							{ header: 'Diff.',				dataIndex: 'diff',			width: 80, renderer: diff, 		align: 'right' },
							{ header: 'Periode',			dataIndex: 'periode',		width: 100, align: 'center' },
							{ header: 'Ket',				dataIndex: 'ket',			width: 200 }
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
								xtype		: 'label',
								text		: Ext.Date.format(new Date(), 'l, d F Y'),
								style		: 'font-weight:bold;',
								margins		: '20 10 0 5'
							}, 
							'->',
							{
								xtype	: 'button',
								id		: 'btn_filter',
								iconCls	: 'search',
								text	: 'Search',
								tooltip	: 'Search',
								handler	: fnc_filter
							}, {
								xtype	: 'button',
								id		: 'btn_refresh',
								iconCls	: 'refresh',
								text 	: 'Refresh',
								tooltip	: 'Refresh',
								handler : function (){
									ds_scrap.proxy.setExtraParam('periode', '' );
									ds_scrap.proxy.setExtraParam('idfield', '' );
									ds_scrap.proxy.setExtraParam('srcfield', '' );
									ds_scrap.loadPage(1);
								}
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
												fieldLabel		: 'Month',
												displayField	: 'name',
												valueField		: 'num',
												queryMode		: 'local',
												emptyText		: 'Month',
												margin  		: '5 0 0 0',
												flex			: 1,
												forceSelection	: true,
												store			: monthsStore
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
											var form = this.up('form').getForm();
											var popwindow = this.up('window');
											if (form.isValid()) {
												form.submit({
													url		: 'response/downld.php',
													waitMsg	: 'sending data',
													
													success	: function(form, action) {
														popwindow.close();
														location.href="download/"+action.result.msg+"";
													},
											
													failure	: function(form, action) {
														Ext.Msg.show({
															title		:'Failure - Download File',
															icon		: Ext.Msg.ERROR,
															msg			: action.result.msg,
															buttons		: Ext.Msg.OK
														});
													}
											
												});
											}
										}
									}, {
										text	: 'Search',
										iconCls	: 'search',
										handler	: function() {
											var vmonth 		= Ext.getCmp('month').getValue();
											var val_year 		= Ext.getCmp('year').getValue();
											
											if( vmonth != null && val_year != null){
												var vmonth 		= Ext.getCmp('month').getValue();
												if( vmonth >= 1 && vmonth <= 9 ){
													val_month = '0'+ vmonth;
												}else{
													val_month = vmonth;
												}
												var val_periode 	= val_year+''+val_month;
											}else{
												val_periode = 000000;
											}
										
											ds_scrap.proxy.setExtraParam('periode', val_periode );
											ds_scrap.loadPage(1);
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
								modal			: false,
								constrain		: true,
								layout			: 'fit',
								animateTarget	: 'btn_filter',
								items			: frm_filter
							});
						}
						win_filter.show();
					}
				//	end of form filter
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
		<div style="float:left;"> MACHINE </div> <div style="float:right"> Welcome: <?=$img .' <b>'. $sesiis_username?> </b></div>
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