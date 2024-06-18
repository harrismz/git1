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
	<title> IIS - Setup No Register BC25 </title>
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
		.preview {
			 background-image:url(icons/preview.png) !important;
		}
		.add {
             background-image:url(icons/add.gif) !important;
        }
		.save {
             background-image:url(icons/save.png) !important;
        }
		.update {
             background-image:url(icons/update.png) !important;
        }
		.delete {
             background-image:url(icons/delete.png) !important;
        }
		.upload {
             background-image:url(icons/upload.png) !important;
        }
		.download {
			 background-image:url(icons/download.png) !important;
		}
		.biggertext{ font: 14pt arial,sans-serif !important;} 
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
				//	function required
					var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
				//	end of function required
			//	----***----  //
			
			//	All about json data
			//	***
				//	json
					var itemperpage = 50;
					
					Ext.define('regbc25', {
						extend	: 'Ext.data.Model',
						fields	: [ 'id', 'noaju', 'nodaft', 'action_user', 'action_date' ]
					});
					
					var datastore = Ext.create('Ext.data.Store', {
						model		: 'regbc25',
						autoLoad	: true,
						pageSize	: itemperpage,
						proxy		: {
							type	: 'ajax',
							url		: 'json/json_stp_regbc25.php',
							reader	: {
								type			: 'json',
								root			: 'rows',
								totalProperty	: 'totalCount'
							}
						}
					});
				
					var responregbc25 = Ext.create('Ext.data.JsonStore', {
						proxy		: {
							type	: 'ajax',
							url		: 'response/resp_regbc25.php',
							reader	: {
								type			: 'json',
								root			: 'rows'
							}
						}
					});
				//	end of json
			//	----***----  //
			
			
			//	Grid data
			//	***
				//	grid panel
					var sm = Ext.create('Ext.selection.CheckboxModel');
					var clock = Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'g:i:s A')});
					var gridpanel = Ext.create('Ext.grid.Panel', {
						stateId		: 'gridpanel',
						renderTo	: 'gridpanel',
						title		: 'Data No Register BC25',
						store		: datastore,
						width		: '100%',
						height		: 420,
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
							{ header: 'No Pengajuan', 	dataIndex: 'noaju',			flex: 2 },
							{ header: 'No Pendaftaran', dataIndex: 'nodaft',		flex: 1 },
							{ header: 'User', 			dataIndex: 'action_user',	flex: 1, align: 'center' },
							{ header: 'Last Update', 	dataIndex: 'action_date',	flex: 1, align: 'center' },
							{ header: 'ID', 			dataIndex: 'id',			flex: 1, hidden: true }
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
									Ext.getCmp('valnoaju').setValue();
									Ext.getCmp('valnodaft').setValue();
								
									datastore.proxy.setExtraParam('valnoaju',	'');
									datastore.proxy.setExtraParam('valnodaft', 	'');
									datastore.loadPage(1);
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
								id		: 'btn_delete',
								iconCls	: 'delete',
								text	: 'Delete Data',
								tooltip	: 'Delete Data',
								handler : function (widget, event){
									Ext.Msg.confirm('Confirm', 'Are you sure want to delete data ?', function(btn){
										if (btn == 'yes'){
											var rec = gridpanel.getSelectionModel().getSelection();
									
											var reclength = rec.length;
											var i = 0;
											
											for (var i=0; i < reclength; i++) {
												responregbc25.proxy.setExtraParam('id', 		rec[i].data.id);
												responregbc25.proxy.setExtraParam('typeform',	'del');
												responregbc25.load();
											}
											datastore.load();
										}
									});
								}
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
				//	end of grid panel
			//	----***----  //
			
			
			//	All about form
			//	***
				Ext.widget('form', {
					renderTo		: 'formsearch',
					bodyPadding		: 5,
					frame			: false,
					border			: false,
					bodyStyle		: 'background:transparent;',
					width			: '100%',
					fieldDefaults	: {
						labelAlign	: 'left',
						labelStyle	: 'font-weight:bold',
						anchor		: '100%'
					},
					items: [						
						{
							xtype		: 'fieldcontainer',
							fieldLabel	: 'Field ',
							labelWidth	: 60,
							layout		: 'hbox',
							items		: [
								{
									xtype		: 'textfield',
									id			: 'valnoaju',
									name		: 'valnoaju',
									emptyText	: 'Nomor Aju',
									fieldCls	: 'biggertext',
									margins		: '0 6 0 0',
									height 		: 49,
									flex		: 1,
									listeners	: {
										change	: function(f,new_val) {
											f.setValue(new_val.toUpperCase());
										}
									}
								}, {
									xtype		: 'textfield',
									id			: 'valnodaft',
									name		: 'valnodaft',
									emptyText	: 'Nomor Daftar',
									fieldCls	: 'biggertext',
									margins		: '0 6 0 0',
									height 		: 49,
									flex		: 1,
									listeners	: {
										change	: function(f,new_val) {
											f.setValue(new_val.toUpperCase());
										}
									}
								}, 
								//	type form
								//	****
								//	****
								{
									xtype	: 'hiddenfield',
									name	: 'typeform',
									value	: 'add'
								},
								{
									xtype		: 'fieldcontainer',
									layout		: 'vbox',
									items		: [
										{
											xtype: 'button',
											text : 'Add',
											width: 100,
											handler: function(){
												var noaju	= Ext.getCmp('valnoaju').getValue();
												var nodaft 	= Ext.getCmp('valnodaft').getValue();
												if(noaju == '' || nodaft == ''){
													Ext.Msg.show({
														title		: 'Error Message',
														icon		: Ext.Msg.ERROR,
														msg			: 'Field not empty',
														buttons		: Ext.Msg.OK
													});
												}
												else{
													var form = this.up('form').getForm();
													if (form.isValid()) {
														form.submit({
															url				: 'response/resp_regbc25.php',
															waitMsg			: 'sending data',
															submitEmptyText	: false,
															
															success	: function(form, action) {
																datastore.loadPage(1);
																form.reset();
															},
													
															failure	: function(form, action) {
																datastore.loadPage(1);
																Ext.Msg.show({
																	title		: 'Error Message',
																	icon		: Ext.Msg.ERROR,
																	msg			: action.result.msg,
																	buttons		: Ext.Msg.OK
																});
															}
														});
													}
												}
											}
										},
										{xtype:'splitter'},
										{
											xtype: 'button',
											text : 'Search',
											width: 100,
											handler: function(){
												datastore.proxy.setExtraParam('valnoaju', 	Ext.getCmp('valnoaju').getValue());
												datastore.proxy.setExtraParam('valnodaft', 	Ext.getCmp('valnodaft').getValue());
												datastore.loadPage(1);
											}
										}
									]
								}
							]
						}
					]
				});
			
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
									fieldLabel			: 'Upload File (*.csv)',
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
								}, 
								//	type form
								{
									xtype	: 'hiddenfield',
									name	: 'typeform',
									value	: 'upload'
								}
							],
							buttons			: [
								{
									text		: 'Format CSV',
									iconCls		: 'download',
									handler		: function() { 
										window.open('response/resp_format_regbc25.php');
									}
								}, {
									text		: 'Upload',
									iconCls		: 'upload',
									formBind	: true,
									handler		: function() {
										var form = this.up('form').getForm();
										var popwindow = this.up('window');
										if (form.isValid()) {
											form.submit({
												url		: 'response/resp_regbc25.php',
												waitMsg	: 'sending data',
												
												success	: function(form, action) {
													datastore.load();
													popwindow.close();
												},
										
												failure	: function(form, action) {
													datastore.load();
													Ext.Msg.show({
														title		:'Failure - Upload INVT69B2 File',
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
							title			: 'Upload INVT69B2 File',
							width			: 450,
							minWidth		: 450,
							height			: 140,
							minHeight		: 140,
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
		<div style="float:left;"> SETUP NO REGISTER BC25 </div> <div style="float:right"> Welcome: <?=$img .' <b>'. $sesiis_username?> </b></div>
		<br><br><br>
		
		<div id="formsearch"></div>
		<div id="gridpanel"></div>
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