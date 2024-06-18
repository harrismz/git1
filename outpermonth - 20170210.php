<?php
	session_start();
	
	//SM

	//unset( $_SESSION['sescstm_userid'] );
	//session_destroy();
	$sesmis_username 	= $_SESSION['sesiis_username'];
	$sesmis_userid	 	= $_SESSION['sesiis_userid'];
	$sesmis_level	 	= $_SESSION['sesiis_level'];
	
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
			<title> IIS - OUT PERMONTH </title>
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
				.x-column-header-inner { 
					font-weight: bold;
					text-align: center;
				}
				.x-grid-cell {
					padding: 2px;
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
				.download {
					 background-image:url(icons/download.png) !important;
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
					
					//	All about function
					//	***
						//	function required
							var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
						//	end of function required
						
						//	function untuk cost rupiah
							function convertToRupiah(angka)
							{
								var rupiah 		= '';
								var angkarev 	= angka.toString().split('').reverse().join('');
								for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
								return rupiah.split('',rupiah.length-1).reverse().join('');
							}
						//	end function untuk cost rupiah
						
						//	function untuk column jumlah
							function jumlah(val) {
								if (val > 0) {
									return '<span style="color:green;">' + convertToRupiah(val) + '</span>';
								} else if (val <= 0) {
									return '<span style="color:red;">' + convertToRupiah(val) + '</span>';
								}
								return val;
							}
						//	end function untuk column jumlah
						
						//	function untuk column numeric
							function numeric(val) {
								if (val > 0) {
									return '<span style="color:green;">' + val + '</span>';
								} else if (val <= 0) {
									return '<span style="color:red;">' + val + '</span>';
								}
								return val;
							}
						//	end function untuk column numeric
						
						//	function untuk column tooltip
							function addtooltip(value,metaData,record,colIndex,store,view) {
								metaData.tdAttr = 'data-qtip="' + value + '"';
								return value;
							}
						//	end of function untuk column tooltip
					//	----***----  //
					
					
					//	All about json data
					//	***					
						//json
							var itemperhal = 15;
						
							Ext.define('outpart', {
								extend : 'Ext.data.Model',
								fields : ['jns_dok', 'dp_no', 'dp_tgl', 'bpb_no', 'bpb_tgl', 'pemasok', 'partno', 'partname', 'sat', 'jumlah', 'nilai', 'periode', 'currency']
							});
							
							var data_opp = Ext.create('Ext.data.Store', {
								model 		: 'outpart',
								autoLoad	: true,
								pageSize	: itemperhal,
								fields 		: ['jns_dok', 'dp_no', 'dp_tgl', 'bpb_no', 'bpb_tgl', 'pemasok', 'partno', 'partname', 'sat', 'jumlah', 'nilai', 'periode', 'currency'],
								groupField	: 'partno',
								proxy		: {
									type	: 'ajax',
									url		: 'json/json_tbl_outpart_new.php',
									reader	: {
										type 			: 'json',
										root	 		: 'rows',
										totalProperty	: 'totalCount'
									}
								}
							});
							
							
							
							Ext.define('cbx_jnsdok_out', {
								extend	: 'Ext.data.Model',
								fields	: [ 'jns_dok' ]
							});
							var ds_cbx_jnsdok_out = Ext.create('Ext.data.Store', {
								model	: 'cbx_jnsdok_out',
								data	: [
									{ 'jns_dok': '25BC' },
									{ 'jns_dok': '25BCScrap' },
									{ 'jns_dok': '27BC' },
									{ 'jns_dok': '30BC' },
									{ 'jns_dok': '261BC' }
								]
							});
					//	----***----  //
					
					
					//	Grid data
					//	***
						//	grid panel
							var clock = Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'g:i:s A')});
							
							var grid_opp = Ext.create('Ext.grid.Panel', {
								stateId		: 'grid_opp',
								renderTo	: 'grid_opp',
								title		: 'OUTGOING PARTS LIST',
								store		: data_opp,
								width		: '100%',
								width		: '100%',
								autoHeight	: true,
								border		: true,
								columnLines	: true,
								multiSelect	: true,
								viewConfig	: {
									stripeRows			: true,
									enableTextSelection	: true
								},
								features: [
									{
										id					: 'group',
										ftype				: 'groupingsummary',
										groupHeaderTpl		: 'Part Number: {name} ({rows.length} Item{[values.rows.length > 1 ? "s" : ""]})',
										hideGroupedHeader	: true
									}
								],
								//	columns
								columns		: [
									{ header: 'No.', xtype: 'rownumberer', width: 50, height: 40, sortable: false },
									{ header: 'JENIS<br>DOKUMEN',  	dataIndex: 'jns_dok', 	width: 80 },
									{
										header: 'DOKUMEN PABEAN', width : 150,
										columns : [
											{ header: 'NO.', 		dataIndex: 'dp_no', 	width: 100 },
											{ header: 'TANGGAL', 	dataIndex: 'dp_tgl',	width: 100, align: 'center' }
										]
									},
									{
										header: 'BUKTI PENERIMAAN BARANG', width:120,
										columns: [	
											{ header: 'NO.', 		dataIndex: 'bpb_no', 	width: 100, renderer: addtooltip },
											{ header: 'TANGGAL', 	dataIndex: 'bpb_tgl',	width: 100, align: 'center' }
										]
									},
									{ header: 'PEMASOK',  		dataIndex: 'pemasok', 	width : 200, renderer: addtooltip },
									{ header: 'PARTNO',  		dataIndex: 'partno',	width : 125 },
									{ header: 'PARTNAME',		dataIndex: 'partname', 	width : 150 },
									{ header: 'SAT', 			dataIndex: 'sat', 		flex : 1, summaryRenderer: function() { return Ext.String.format('Total :'); } },
									{ header: 'JUMLAH', 		dataIndex: 'jumlah', 	flex : 1, align: 'right', renderer: jumlah, summaryType: 'sum', summaryRenderer: Ext.util.Format.numberRenderer('0,000,000') },
									{ header: 'MATA<br>UANG', 	dataIndex: 'currency', 	flex : 1 },
									{ header: 'NILAI', 			dataIndex: 'nilai', 	flex : 1, align: 'right', renderer: numeric }
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
										handler	: function() {
											data_opp.proxy.setExtraParam('st_date', '' );
											data_opp.proxy.setExtraParam('en_date', '' );
											data_opp.proxy.setExtraParam('dp_no', '' );
											data_opp.proxy.setExtraParam('partno', '' );
											data_opp.proxy.setExtraParam('files', '' );
											data_opp.proxy.setExtraParam('jns_dok', '' );
											data_opp.loadPage(1);
											
											document.getElementById("lbl_date").innerHTML = ''
											document.getElementById("lbl_jnsdok").innerHTML = ''
											document.getElementById("lbl_dpno").innerHTML = ''
											document.getElementById("lbl_partno").innerHTML = ''
											document.getElementById("lbl_files").innerHTML = ''
										}
									}, {
										xtype	: 'button',
										id		: 'btn_filter',
										iconCls	: 'search',
										text 	: 'Search',
										tooltip	: 'Search',
								<?php 	if( $sesmis_level == 'ADMIN' ){ ?>
										handler	: fnc_find_admin
								<?php	}else{?>
										handler	: fnc_find
								<?php	}?>
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
									pageSize	: itemperhal,
									store		: data_opp,
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
						//Search by field
							function fnc_find_admin() {
								var win_fin;
								var date = new Date();
									
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
														xtype			: 'datefield',
														id				: 'st_date',
														name			: 'st_date',
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
														id				: 'en_date',
														name			: 'en_date',
														fieldLabel		: 'END DATE',
														format			: 'Ymd',
														value			: Ext.Date.format(new Date(), 'Ymd'),
														editable		: false,
														margin  		: '5 0 0 0',
														flex			: 1
													}
												]
											}, {
												xtype				: 'combo',
												id					: 'jns_dok',
												name				: 'jns_dok',
												fieldLabel			: 'JENIS DOKUMEN',
												queryMode			: 'local',
												displayField		: 'jns_dok',
												valueField			: 'jns_dok',
												emptyText			: '27BC',
												editable			: false,
												allowBlank			: false,
												store				: ds_cbx_jnsdok_out
											}, {
												xtype				: 'textfield',
												id					: 'dp_no',
												name				: 'dp_no',
												fieldLabel			: 'BC NUMBER',
												listeners 			: {
													change	: function(f,new_val) {
														f.setValue(new_val.toUpperCase());
													}
												}
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
											}, {
												xtype				: 'textfield',
												id					: 'files',
												name				: 'files',
												fieldLabel			: 'EPTE REPORT',
												listeners 			: {
													change	: function(f,new_val) {
														f.setValue(new_val.toUpperCase());
													}
												}
											},
											//	type modul
											{
												xtype	: 'hiddenfield',
												id		: 'typemodul',
												name	: 'typemodul',
												value	: 'outpermonth'
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
												iconCls		: 'download',
												text		: 'Download',
												formBind	: true,
												handler		: function() {
													window.open('response/download_1month.php?st_date='+ Ext.Date.format(Ext.getCmp('st_date').getValue(), 'Ymd') +
																														'&en_date='+ Ext.Date.format(Ext.getCmp('en_date').getValue(), 'Ymd') +
																														'&jns_dok='+ Ext.getCmp('jns_dok').getValue() +
																														'&partno='+ Ext.getCmp('partno').getValue() +
																														'&dp_no='+ Ext.getCmp('dp_no').getValue() +
																														'&files='+ Ext.getCmp('files').getValue() +
																														'&typemodul='+ Ext.getCmp('typemodul').getValue());
												}
											}, {
												text	: 'Search',
												iconCls	: 'search',
												handler	: function() {
													var st_date = Ext.Date.format(Ext.getCmp('st_date').getValue(), 'Ymd');
													var en_date = Ext.Date.format(Ext.getCmp('en_date').getValue(), 'Ymd');
													var jns_dok	= Ext.getCmp('jns_dok').getValue();
													var dp_no 	= Ext.getCmp('dp_no').getValue();
													var partno 	= Ext.getCmp('partno').getValue();
													var files 	= Ext.getCmp('files').getValue();
													
													data_opp.proxy.setExtraParam('st_date', st_date);
													data_opp.proxy.setExtraParam('en_date', en_date);
													data_opp.proxy.setExtraParam('jns_dok', jns_dok );
													data_opp.proxy.setExtraParam('dp_no', 	dp_no );
													data_opp.proxy.setExtraParam('partno', 	partno );
													data_opp.proxy.setExtraParam('files', 	files );
													data_opp.loadPage(1);
													
													var vwst_date = Ext.Date.format(Ext.getCmp('st_date').getValue(), 'd M Y');
													var vwen_date = Ext.Date.format(Ext.getCmp('en_date').getValue(), 'd M Y');
													
													if( vwst_date != '' && vwen_date != '' ){
														document.getElementById("lbl_date").innerHTML = 'Search by Period Date <br> <b>'+vwst_date+' &nbsp;s.d&nbsp; '+vwen_date+'</b>';
													}
													
													if( jns_dok != null){
														document.getElementById("lbl_jnsdok").innerHTML = 'Search by Jenis Dokumen <br> <b>'+jns_dok+'</b>';
													}
													
													if( dp_no != ''){
														document.getElementById("lbl_dpno").innerHTML = 'Search by No BC <br> <b>'+dp_no+'</b>';
													}
													
													if( partno != ''){
														document.getElementById("lbl_partno").innerHTML = 'Search by Part Number <br> <b>'+partno+'</b>';
													}
													
													if( files != ''){
														document.getElementById("lbl_files").innerHTML = 'Search by Data From <br> <b>'+files+'</b>';
													}
													
													var popwindow = this.up('window');
													popwindow.close();
												}
											}
										]
									});
									
									win_fin = Ext.widget('window', {
										title			: 'Search by field',
										width			: 450,
										minWidth		: 450,
										height			: 280,
										minHeight		: 280,
										modal			: true,
										constrain		: true,
										layout			: 'fit',
										animateTarget	: 'btn_filter',
										items			: frm_filter
									});
								}
								win_fin.show();
							}
						//	end of form filter	
						
						//Search by field
							function fnc_find() {
								var win_fin;
								var date = new Date();
									
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
														xtype			: 'datefield',
														id				: 'st_date',
														name			: 'st_date',
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
														id				: 'en_date',
														name			: 'en_date',
														fieldLabel		: 'END DATE',
														format			: 'Ymd',
														value			: Ext.Date.format(new Date(), 'Ymd'),
														editable		: false,
														margin  		: '5 0 0 0',
														flex			: 1
													}
												]
											}, {
												xtype				: 'combo',
												id					: 'jns_dok',
												name				: 'jns_dok',
												fieldLabel			: 'JENIS DOKUMEN',
												queryMode			: 'local',
												displayField		: 'jns_dok',
												valueField			: 'jns_dok',
												emptyText			: '27BC',
												editable			: false,
												allowBlank			: false,
												store				: ds_cbx_jnsdok_out
											}, {
												xtype				: 'textfield',
												id					: 'dp_no',
												name				: 'dp_no',
												fieldLabel			: 'BC NUMBER',
												listeners 			: {
													change	: function(f,new_val) {
														f.setValue(new_val.toUpperCase());
													}
												}
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
											},
											//	type modul
											{
												xtype	: 'hiddenfield',
												id		: 'typemodul',
												name	: 'typemodul',
												value	: 'outpermonth'
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
												iconCls		: 'download',
												text		: 'Download',
												formBind	: true,
												handler		: function() {
													window.open('response/download_1month.php?st_date='+ Ext.Date.format(Ext.getCmp('st_date').getValue(), 'Ymd') +
																														'&en_date='+ Ext.Date.format(Ext.getCmp('en_date').getValue(), 'Ymd') +
																														'&jns_dok='+ Ext.getCmp('jns_dok').getValue() +
																														'&partno='+ Ext.getCmp('partno').getValue() +
																														'&dp_no='+ Ext.getCmp('dp_no').getValue() +
																														'&files=&typemodul='+ Ext.getCmp('typemodul').getValue());
												}
											}, {
												text	: 'Search',
												iconCls	: 'search',
												handler	: function() {
													var st_date = Ext.Date.format(Ext.getCmp('st_date').getValue(), 'Ymd');
													var en_date = Ext.Date.format(Ext.getCmp('en_date').getValue(), 'Ymd');
													var jns_dok	= Ext.getCmp('jns_dok').getValue();
													var dp_no 	= Ext.getCmp('dp_no').getValue();
													var partno 	= Ext.getCmp('partno').getValue();
													var files 	= '';
													
													data_opp.proxy.setExtraParam('st_date', st_date);
													data_opp.proxy.setExtraParam('en_date', en_date);
													data_opp.proxy.setExtraParam('jns_dok', jns_dok );
													data_opp.proxy.setExtraParam('dp_no', 	dp_no );
													data_opp.proxy.setExtraParam('partno', 	partno );
													data_opp.proxy.setExtraParam('files', 	files );
													data_opp.loadPage(1);
													
													var vwst_date = Ext.Date.format(Ext.getCmp('st_date').getValue(), 'd M Y');
													var vwen_date = Ext.Date.format(Ext.getCmp('en_date').getValue(), 'd M Y');
													
													if( vwst_date != '' && vwen_date != '' ){
														document.getElementById("lbl_date").innerHTML = 'Search by Period Date <br> <b>'+vwst_date+' &nbsp;s.d&nbsp; '+vwen_date+'</b>';
													}
													
													if( jns_dok != null){
														document.getElementById("lbl_jnsdok").innerHTML = 'Search by Jenis Dokumen <br> <b>'+jns_dok+'</b>';
													}
													
													if( dp_no != ''){
														document.getElementById("lbl_dpno").innerHTML = 'Search by No BC <br> <b>'+dp_no+'</b>';
													}
													
													if( partno != ''){
														document.getElementById("lbl_partno").innerHTML = 'Search by Part Number <br> <b>'+partno+'</b>';
													}
													
													if( files != ''){
														document.getElementById("lbl_files").innerHTML = 'Search by Data From <br> <b>'+files+'</b>';
													}
													
													var popwindow = this.up('window');
													popwindow.close();
												
												}
											}
										]
									});
									
									win_fin = Ext.widget('window', {
										title			: 'Search by field',
										width			: 450,
										minWidth		: 450,
										height			: 250,
										minHeight		: 250,
										modal			: true,
										constrain		: true,
										layout			: 'fit',
										animateTarget	: 'btn_filter',
										items			: frm_filter
									});
								}
								win_fin.show();
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
				<div style="float:left;">OUTGOING PARTS PERMONTH</div> <div style="float:right"> Welcome: <?=$img .' <b>'. $sesmis_username?> </b></div>
				<br><br>
					<table width="100%" border="0">
						<tr bgcolor="ebf4ff">
							<td align="center" width="20%"> <label id="lbl_date"></label> </td>
							<td align="center" width="20%"> <label id="lbl_jnsdok"></label> </td>
							<td align="center" width="20%"> <label id="lbl_dpno"></label> </td>
							<td align="center" width="20%"> <label id="lbl_partno"></label> </td>
							<td align="center" width="20%"> <label id="lbl_files"></label> </td>
						</tr>
					</table>
				<br>
				
				<div id="grid_opp"></div>
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