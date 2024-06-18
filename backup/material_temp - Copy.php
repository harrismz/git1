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
				
				//	function required
					var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
				//	end of function required
			//	----***----  //
			
			
			//	All about json data
			//	***
				//	json
					var itemperpage = 15;
				
					Ext.define('material_temp', {
						extend	: 'Ext.data.Model',
						fields	: [ 'kodebarang', 'namabarang', 'sal_awal', 'masuk', 'keluar', 'sal_akhir', 'mtr_periode' ]
					});
					
					var ds_material_temp = Ext.create('Ext.data.Store', {
						model		: 'material_temp',
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
				//	end of json
			//	----***----  //
			
			
			//	Grid data
			//	***
				//	grid panel
					var sm = Ext.create('Ext.selection.CheckboxModel');
					var clock = Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'g:i:s A')});
					
					var grd_material_temp = Ext.create('Ext.grid.Panel', {
						stateId		: 'grd_material_temp',
						renderTo	: 'grd_material_temp',
						title		: 'Data Material Temp File',
						store		: ds_material_temp,
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
							{ header: 'MASUK', 				dataIndex: 'masuk',			flex: 1, align: 'right', renderer : masuk },
							{ header: 'KELUAR', 			dataIndex: 'keluar',		flex: 1, align: 'right', renderer : keluar },
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
									ds_material_temp.loadPage(1);
								}
							}, {
								xtype	: 'button',
								id		: 'btn_upload',
								iconCls	: 'upload',
								text 	: 'Upload',
								tooltip	: 'Upload',
								handler	: fnc_upload
							},
							'-',
							clock
						],
						bbar		: Ext.create('Ext.PagingToolbar', {
							pageSize	: itemperpage,
							store		: ds_material_temp,
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
										xtype				: 'datefield',
										id					: 'material_periode',
										name				: 'material_periode',
										fieldLabel			: 'Material Periode (YYYYMM)',
										format				: 'Ym',
										allowBlank			: false,
										afterLabelTextTpl	: required
									},
									//	type form
									{
										xtype	: 'hiddenfield',
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
														ds_material_temp.load();
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
		
		<div id="grd_material_temp"></div>
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