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
	<title> IIS - MATERIAL UPLOAD </title>
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
		.calc {
             background-image:url(icons/calc.png) !important;
        }
		.proses {
             background-image:url(icons/cog.gif) !important;
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
				//	end function untuk cost rupiah
			
				//	function untuk column sal_awal
					function sal_awal(val) {
						if (val > 0) {
							return '<span style="color:green; float:right;">' + convertToRupiah(val) + '</span>';
						} else if (val <= 0) {
							return '<span style="color:red; float:right;">' + convertToRupiah(val) + '</span>';
						}
						return val;
					}
				//	end function untuk column sal_awal
				
				//	function untuk column masuk
					function masuk(val) {
						if (val > 0) {
							return '<span style="color:green; float:right;">' + convertToRupiah(val) + '</span>';
						} else if (val <= 0) {
							return '<span style="color:red; float:right;">' + convertToRupiah(val) + '</span>';
						}
						return val;
					}
				//	end function untuk column masuk
				
				//	function untuk column keluar
					function keluar(val) {
						if (val > 0) {
							return '<span style="color:green; float:right;">' + convertToRupiah(val) + '</span>';
						} else if (val <= 0) {
							return '<span style="color:red; float:right;">' + convertToRupiah(val) + '</span>';
						}
						return val;
					}
				//	end function untuk column keluar
				
				//	function untuk column sal_akhir
					function sal_akhir(val) {
						if (val > 0) {
							return '<span style="color:green; float:right;">' + convertToRupiah(val) + '</span>';
						} else if (val <= 0) {
							return '<span style="color:red; float:right;">' + convertToRupiah(val) + '</span>';
						}
						return val;
					}
				//	end function untuk column sal_akhir
				
				//	function untuk column selisih
					function selisih(val) {
						if (val > 0) {
							return '<span style="color:green; float:right;">' + convertToRupiah(val) + '</span>';
						} else if (val <= 0) {
							return '<span style="color:red; float:right;">' + convertToRupiah(val) + '</span>';
						}
						return val;
					}
				//	end function untuk column selisih
				
				//	function required
					var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
				//	end of function required
			//	----***----  //
			
			
			//	All about json data
			//	***
				//	json
					var itemperpage = 15;
				
					Ext.define('mtr_temp', {
						extend	: 'Ext.data.Model',
						fields	: [ 'kodebarang', 'namabarang', 'sal_awal', 'masuk', 'keluar', 'sal_akhir', 'mtr_periode' ]
					});
					var ds_mtr_temp = Ext.create('Ext.data.Store', {
						model		: 'mtr_temp',
						autoLoad	: true,
						pageSize	: itemperpage,
						fields		: [ 'kodebarang', 'namabarang', 'sal_awal', 'masuk', 'keluar', 'sal_akhir', 'mtr_periode' ],
						proxy		: {
							type	: 'ajax',
							url		: 'json/json_material_temp.php',
							reader	: {
								type			: 'json',
								root			: 'rows',
								totalProperty	: 'totalCount'
							}
						},
						sorters: [{
							property	: 'kodebarang',
							direction	: 'ASC'
						}]
					});
					
					
					var itemperpage2 = 10;
					Ext.define('trx_mtr', {
						extend	: 'Ext.data.Model',
						fields	: [ 'kodebarang', 'namabarang', 'sal_awal', 'masuk', 'keluar', 'sal_akhir', 'selisih' ]
					});
					var ds_trx_mtr = Ext.create('Ext.data.Store', {
						model		: 'trx_mtr',
						autoLoad	: true,
						pageSize	: itemperpage2,
						fields		: [ 'kodebarang', 'namabarang', 'sal_awal', 'masuk', 'keluar', 'sal_akhir', 'selisih' ],
						proxy		: {
							type	: 'ajax',
							url		: 'json/json_trx_mtr.php',
							reader	: {
								type			: 'json',
								root			: 'rows',
								totalProperty	: 'totalCount'
							}
						},
						sorters: [{
							property	: 'kodebarang',
							direction	: 'ASC'
						}]
					});
				//	end of json
			//	----***----  //
			
			
			//	Grid data
			//	***
				//	grid panel
					var sm = Ext.create('Ext.selection.CheckboxModel');
					var clock = Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'g:i:s A')});
					
					var grd_mtr_temp = Ext.create('Ext.grid.Panel', {
						stateId		: 'grd_mtr_temp',
						renderTo	: 'grd_mtr_temp',
						title		: 'Data Material Temp File',
						store		: ds_mtr_temp,
						width		: '100%',
						autoHeight	: true,
						x 			: 0, 
						y 			: 0,
						border		: true,
						columnLines	: true,
						multiSelect	: true,
						viewConfig	: {
							stripeRows			: true,
							enableTextSelection	: true
						},
						//	columns
						columns		: [
							{ header: 'NO.', xtype: 'rownumberer', width: 50, height: 40, sortable: false },
							{ header: 'KODE BARANG', 		dataIndex: 'kodebarang',	flex: 1 },
							{ header: 'NAMA BARANG', 		dataIndex: 'namabarang',	flex: 2 },
							{ header: 'SALDO AWAL', 		dataIndex: 'sal_awal',		flex: 1, align: 'right', renderer : sal_awal },
							{ header: 'JML MASUK', 			dataIndex: 'masuk',			flex: 1, align: 'right', renderer : masuk },
							{ header: 'JML KELUAR', 		dataIndex: 'keluar',		flex: 1, align: 'right', renderer : keluar },
							{ header: 'SALDO AKHIR', 		dataIndex: 'sal_akhir',		flex: 1, align: 'right', renderer : sal_akhir },
							{ header: 'MATERIAL PERIODE', 	dataIndex: 'mtr_periode',	flex: 1, align: 'center' }
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
								id		: 'btn_refresh',
								iconCls	: 'refresh',
								text 	: 'Refresh',
								tooltip	: 'Refresh',
								handler : function (){
									ds_mtr_temp.loadPage(1);
								}
							}, {
								xtype	: 'button',
								id		: 'btn_upload',
								iconCls	: 'upload',
								text 	: 'Upload',
								tooltip	: 'Upload',
								handler	: fnc_upload
							}, {
								xtype	: 'button',
								id		: 'btn_calc',
								iconCls	: 'calc',
								text 	: 'Summary',
								tooltip	: 'Summary Per Month',
								handler	: fnc_transact
							}, {
								xtype	: 'button',
								id		: 'btn_proses',
								iconCls	: 'proses',
								text 	: 'Process',
								tooltip	: 'Proses',
								handler	: fnc_proses
							},
							'-',
							clock
						],
						bbar		: Ext.create('Ext.PagingToolbar', {
							pageSize	: itemperpage,
							store		: ds_mtr_temp,
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
									labelWidth	: 170,
									labelStyle	: 'font-weight:bold',
									msgTarget	: 'side'
								},
								defaults		: {
									margins	: '0 0 10 0'
								},
								items			: [
									{
										xtype				: 'fileuploadfield',
										name				: 'ufile',
										fieldLabel			: 'Upload MCS File (*.txt)',
										buttonText			: '',
										allowBlank			: false,
										afterLabelTextTpl	: required,
										buttonConfig		: {
											iconCls	: 'upload'
										},
										listeners 			: {
											afterrender	: function(field) {
												field.focus(false, 1000);
											}
										}
									}, {
										xtype				: 'numberfield',
										id					: 'material_periode',
										name				: 'material_periode',
										fieldLabel			: 'Material Periode (YYYYMM)',
										afterLabelTextTpl	: required
									},
								/*	
									{
										xtype				: 'datefield',
										id					: 'material_periode',
										name				: 'material_periode',
										fieldLabel			: 'Material Periode (YYYYMM)',
										format				: 'Ym',
										allowBlank			: false,
										afterLabelTextTpl	: required
									},
								*/
									//	type form
									{
										xtype	: 'hiddenfield',
										id		: 'typeform',
										name	: 'typeform',
										value	: 'upload_material'
									}
								],
								buttons			: [
									{
										text		: 'Upload',
										iconCls		: 'upload',
										formBind	: true,
										handler		: function() {
											var form = this.up('form').getForm();
											var popwindow = this.up('window');
											if (form.isValid()) {
												form.submit({
													url		: 'response/resp_upld_material.php',
													waitMsg	: 'sending data',
													
													success	: function(form, action) {
														ds_mtr_temp.load();
														popwindow.close();
													},
											
													failure	: function(form, action) {
														Ext.Msg.show({
															title		:'Failure - Upload MCS File',
															icon		: Ext.Msg.ERROR,
															msg			: action.result.msg,
															buttons		: Ext.Msg.OK
														});
													}
												});
											}
										}
									}
								]
							});
							win_upload = Ext.widget('window', {
								title			: 'Upload MCS File',
								width			: 450,
								minWidth		: 450,
								height			: 160,
								minHeight		: 160,
								modal			: false,
								constrain		: true,
								layout			: 'fit',
								animateTarget	: 'btn_upload',
								items			: frm_upload,
								listeners		:{
									activate:function(){
										Ext.getCmp('btn_upload').disable();
									},
									close:function(){
										Ext.getCmp('btn_upload').enable();
									}
								}
							});
						}
						win_upload.show();
					}
				//	end of form upload
				
				//	form transact
					function fnc_transact() {
						var win_transact;
						var clock2 = Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'g:i:s A')});
						
						if (!win_transact) {
							var grd_vw_transact = Ext.create('Ext.grid.Panel', {
								store		: ds_trx_mtr,
								width		: '100%',
								border		: false,
								columnLines	: true,
								multiSelect	: true,
								viewConfig	: {
									stripeRows			: true,
									enableTextSelection	: true
								},
								//	columns
								columns		: [
									{ header: 'NO.', xtype: 'rownumberer', width: 50, height: 40, sortable: false },
									{ header: 'KODE BARANG', 		dataIndex: 'kodebarang',	flex: 1 },
									{ header: 'NAMA BARANG', 		dataIndex: 'namabarang',	flex: 2 },
									{ header: 'SALDO AWAL', 		dataIndex: 'sal_awal',		flex: 1, align: 'right', renderer : sal_awal },
									{ header: 'JML MASUK', 			dataIndex: 'masuk',			flex: 1, align: 'right', renderer : masuk },
									{ header: 'JML KELUAR', 		dataIndex: 'keluar',		flex: 1, align: 'right', renderer : keluar },
									{ header: 'SALDO AKHIR', 		dataIndex: 'sal_akhir',		flex: 1, align: 'right', renderer : sal_akhir },
									{ header: 'SELISIH', 			dataIndex: 'selisih',		flex: 1, align: 'right', renderer : selisih }
								],
								//	end columns
								listeners: {
									render: {
										fn: function(){
											Ext.fly(clock2.getEl().parent()).addCls('x-status-text-panel').createChild({cls:'spacer'});

										 Ext.TaskManager.start({
											 run: function(){
												 Ext.fly(clock2.getEl()).update(Ext.Date.format(new Date(), 'g:i:s A'));
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
										id		: 'btn_refresh2',
										iconCls	: 'refresh',
										text 	: 'Refresh',
										tooltip	: 'Refresh',
										handler : function (){
											ds_trx_mtr.proxy.setExtraParam('st_periode', '' );
											ds_trx_mtr.proxy.setExtraParam('en_periode', '' );
											ds_trx_mtr.proxy.setExtraParam('kodebarang', '' );
											ds_trx_mtr.loadPage(1);
										}
									}, {
										xtype	: 'button',
										id		: 'btn_filter',
										iconCls	: 'search',
										text	: 'Search',
										tooltip	: 'Search',
										handler : fnc_filter
									},
									'-',
									clock2
								],
								bbar		: Ext.create('Ext.PagingToolbar', {
									pageSize	: itemperpage2,
									store		: ds_trx_mtr,
									displayInfo	: true,
									plugins		: Ext.create('Ext.ux.ProgressBarPager', {}),
									listeners	: {
										afterrender: function(cmp) {
											cmp.getComponent("refresh").hide();
										}
									}
								})
							});
							win_transact = Ext.widget('window', {
								title			: 'Summary Per Month',
								width			: 900,
								minWidth		: 900,
								height			: 400,
								minHeight		: 400,
								modal			: true,
								constrain		: true,
								layout			: 'fit',
								animateTarget	: 'btn_calc',
								items			: grd_vw_transact
							});
						}
						win_transact.show();
					}
				//	end of form transact
				
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
												xtype				: 'datefield',
												id					: 'st_periode',
												name				: 'st_periode',
												fieldLabel			: 'Start Periode (YYYYMM)',
												format				: 'Ym',
												flex				: 1,
												allowBlank			: false,
												afterLabelTextTpl	: required,
												listeners 	: {
													afterrender	: function(field) {
														field.focus(false, 1000);
													}
												}
											}, {
												xtype		: 'splitter'
											}, {
												xtype				: 'datefield',
												id					: 'en_periode',
												name				: 'en_periode',
												fieldLabel			: 'End Periode (YYYYMM)',
												format				: 'Ym',
												flex				: 1,
												allowBlank			: false,
												afterLabelTextTpl	: required
											}
										]
									}, {
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
												xtype		: 'textfield',
												id			: 'kodebarang',
												name		: 'kodebarang',
												fieldLabel	: 'Enter a kode barang',
												flex		: 1,
												listeners 	: {
													change	: function(f,new_val) {
														f.setValue(new_val.toUpperCase());
													}
												}
											}
										]
									}
								],
								buttons			: [
									{
										text	: 'View',
										iconCls	: 'search',
										handler	: function() {
											ds_trx_mtr.proxy.setExtraParam('st_periode', Ext.Date.format(Ext.getCmp('st_periode').getValue(), 'Ym') );
											ds_trx_mtr.proxy.setExtraParam('en_periode', Ext.Date.format(Ext.getCmp('en_periode').getValue(), 'Ym') );
											ds_trx_mtr.proxy.setExtraParam('kodebarang', Ext.getCmp('kodebarang').getValue() );
											ds_trx_mtr.loadPage(1);
											
											var popwindow = this.up('window');
											popwindow.close();
										}
									}
								]
							});							
							win_filter = Ext.widget('window', {
								title			: 'Search by field',
								width			: 400,
								minWidth		: 400,
								height			: 200,
								minHeight		: 200,
								modal			: true,
								constrain		: true,
								layout			: 'fit',
								animateTarget	: 'btn_filter',
								items			: frm_filter,
								listeners		:{
									activate:function(){
										Ext.getCmp('btn_filter').disable();
									},
									close:function(){
										Ext.getCmp('btn_filter').enable();
									}
								}
							});
						}
						win_filter.show();
					}
				//	end of form filter
				
				//	form proses
					function fnc_proses() {
						var win_proses;
						
						if (!win_proses) {
							var frm_proses = Ext.widget('form', {
								layout			: {
									type	: 'vbox',
									align	: 'stretch'
								},
								border			: false,
								bodyPadding		: 10,

								fieldDefaults	: {
									labelWidth	: 170,
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
												xtype				: 'datefield',
												name				: 'st_periode',
												fieldLabel			: 'Start Periode (YYYYMM)',
												format				: 'Ym',
												flex				: 1,
												allowBlank			: false,
												afterLabelTextTpl	: required,
												listeners 	: {
													afterrender	: function(field) {
														field.focus(false, 1000);
													}
												}
											}, {
												xtype		: 'splitter'
											}, {
												xtype				: 'datefield',
												name				: 'en_periode',
												fieldLabel			: 'End Periode (YYYYMM)',
												format				: 'Ym',
												flex				: 1,
												allowBlank			: false,
												afterLabelTextTpl	: required
											}
										]
									},
									//	type form
									{
										xtype	: 'hiddenfield',
										name	: 'typeform',
										value	: 'proses_material'
									}
								],
								buttons			: [
									{
										text		: 'Process',
										iconCls		: 'proses',
										formBind	: true,
										handler		: function() {
											var form = this.up('form').getForm();
											var popwindow = this.up('window');
											if (form.isValid()) {
												form.submit({
													url		: 'response/resp_pros_mtr.php',
													waitMsg	: 'sending data',
													
													success	: function(form, action) {
														ds_mtr_temp.load();
														popwindow.close();
													},
											
													failure	: function(form, action) {
														Ext.Msg.show({
															title		:'Failure - Process Material File',
															icon		: Ext.Msg.ERROR,
															msg			: action.result.msg,
															buttons		: Ext.Msg.OK
														});
													}
												});
											}
										}
									}
								]
							});
							win_proses = Ext.widget('window', {
								title			: 'Process Material File',
								width			: 450,
								minWidth		: 450,
								height			: 140,
								minHeight		: 140,
								modal			: false,
								constrain		: true,
								layout			: 'fit',
								animateTarget	: 'btn_proses',
								items			: frm_proses,
								listeners		:{
									activate:function(){
										Ext.getCmp('btn_proses').disable();
									},
									close:function(){
										Ext.getCmp('btn_proses').enable();
									}
								}
							});
						}
						win_proses.show();
					}
				//	end of form proses
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
		<div style="float:left;"> DATA UPLOAD MATERIAL TEMPORARY </div> <div style="float:right"> Welcome: <?=$img .' <b>'. $sesiis_username?> </b></div>
		<br><br><br><br>
		
		<div id="grd_mtr_temp"></div>
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