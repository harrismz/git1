<?php
	session_start();
	
	//SM

	//unset( $_SESSION['sescstm_userid'] );
	//session_destroy();
	$sesmis_username 	= $_SESSION['sesiis_username'];
	$sesmis_userid	 	= $_SESSION['sesiis_userid'];
	
	if (!isset($_SESSION['sesiis_userid']))
	{
		echo '<script language="javascript">location.href="index.php";</script>'; 
	}
	else
	{
		include "iconlevel.php";
			?>
		<!doctype html>
		<html>
		<head>
			<meta http-equiv="content-type" content="text/html;charset=utf-8" />
			<title> IIS - WIP </title>
			<link rel="stylesheet" type="text/css" href="css/style.css" />
			<link rel="shortcut icon" href= "icons/iconcstm.ico"/>
			
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
			<link rel="stylesheet" type="text/css" href="../extjs-4.1.1/examples/shared/example.css" />
			<script type="text/javascript" src="../extjs-4.1.1/ext-all.js"></script>
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
				
				.add {
					 background-image:url(icons/add.gif) !important;
				}
				.delete {
					 background-image:url(icons/delete.png) !important;
				}
				.refresh {
					 background-image:url(icons/refresh.gif) !important;
				}
				.search {
					 background-image:url(icons/search.png) !important;
				}
				.update {
					 background-image:url(icons/update.png) !important;
				}
				.save {
					 background-image:url(icons/save.png) !important;
				}
				.upload {
					 background-image:url(icons/upload.png) !important;
				}
				
				.login {
					 background-image:url(icons/login.png) !important;
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
					'Ext.ux.statusbar.StatusBar',
					'Ext.layout.container.Border',
					'Ext.layout.container.Column',
					'Ext.selection.CheckboxModel',
					'Ext.toolbar.Paging'
				]);
				
				Ext.onReady(function(){
					Ext.QuickTips.init();
					
				//json for grid finish goods
					var itemperhal = 50;
				
					Ext.define('data_ipp', {
						extend : 'Ext.data.Model',
						fields :  ['jnsdok', 'supp', 'partno', 'partname', 'sat', 'qty', 'nilai']
					});
					
					var data_ipp = Ext.create('Ext.data.Store', {
						model 		: 'data_ipp',
						autoLoad	: true,
						pageSize	: itemperhal,
						fields		: ['jnsdok', 'supp', 'partno', 'partname', 'sat', 'qty', 'nilai'],
						proxy		: {
							type	: 'ajax',
							url		: 'json/json_bonza.php',
							reader	: {
								type 			: 'json',
								root	 		: 'rows',
								totalProperty	: 'totalCount'
							}
						}
					});	
					
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
					
					//	grid panel
					var sm = Ext.create('Ext.selection.CheckboxModel');
					var clock = Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'g:i:s A')});
					var grid_ipp= Ext.create('Ext.grid.Panel', {
						stateId		: 'grid_ipp',
						renderTo	: 'grid_ipp',
						title		: 'Incoming Parts list',
						store		: data_ipp,
						width		: '100%',
						autoHeight	: true,
						x 			: 0, 
						y 			: 0,
						border		: true,
						columnLines	: true,
						multiSelect	: true,
						selModel	: sm,
						viewConfig	: {
							stripeRows			: true,
							enableTextSelection	: true,
							listeners			: {
								itemcontextmenu	: function(view, rec, node, index, e) {
									e.stopEvent();
									action_menu.showAt(e.getXY());
									return false;
								}
							}
						},
						//	columns
						columns		: [
							{ header: 'No.', xtype: 'rownumberer', width: 50, height: 40, sortable: false },
							{ header: 'Kode Barang',  	dataIndex: 'jnsdok', flex : 1},
							{ header: 'Nama Barang',  width : 125,		dataIndex: 'supp'},
							{ header: 'Sat',  	width : 150,	dataIndex: 'partno'},
							{ header: 'Jumlah',	width : 200,		dataIndex: 'partname'},
							{ header: 'Keterangan', 		dataIndex: 'sat', flex : 1}
							
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
								text 	: 'Search',
								tooltip	: 'Search By Periode',
								handler	: fnc_find
							},{
								xtype	: 'button',
								id		: 'btn_refresh',
								iconCls	: 'refresh',
								text 	: 'Refresh',
								tooltip	: 'Refresh',
								handler	: function() {
										data_ipp.loadPage(1);
										}
							},
							'-',
							clock
						],
						bbar		: Ext.create('Ext.PagingToolbar', {
							PageSize	: itemperhal,
							store		: data_ipp,
							displayInfo	: true,
							plugins		: Ext.create('Ext.ux.ProgressBarPager', {}),
							listeners	: {
											afterrender : function(cmp){
											cmp.getComponent("refresh").hide();
											}
										}
						})
					});
				//	end of grid panel
					
				//Search by field
				function fnc_find() {
					var win_fin;
						
					if (!win_fin) {
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
									
										data_ipp.proxy.setExtraParam('periode', val_periode );
										data_ipp.loadPage(1);
									}
								}
							]
						});
						
						win_fin = Ext.widget('window', {
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
					win_fin.show();
				}
			//	end of form filter	
				
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
				<div style="float:left;"><b>Work In Process </b></div> <div style="float:right"> Welcome: <?=$img .' <b>'. $sesmis_username?> </b></div>
				<br><br><br><br>
				
				<div id="grid_ipp"></div>
			</div>
		</section>
		<footer>
			<?php include "footer.php"; ?>
		</footer>
		</body>	
<?php		
	}
?>
</html>