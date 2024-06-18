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
	<title> IIS - Setup User Login </title>
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
				//	json stp_userlogin
					var itemperpage = 50;
					
					Ext.define('stp_userlogin', {
						extend	: 'Ext.data.Model',
						fields	: [ 'userid', 'username', 'userpass', 'userlevel' ]
					});
					
					var ds_stp_userlogin = Ext.create('Ext.data.Store', {
						model		: 'stp_userlogin',
						autoLoad	: true,
						pageSize	: itemperpage,
						fields		: [ 'userid', 'username', 'userpass', 'userlevel' ],
						proxy		: {
							type	: 'ajax',
							url		: 'json/json_stp_userlogin.php',
							reader	: {
								type			: 'json',
								root			: 'rows',
								totalProperty	: 'totalCount'
							}
						}
					});
				//	end of json stp_userlogin
				
				//	json cbx field
					Ext.define('field', {
						extend	: 'Ext.data.Model',
						fields	: [ 'idfield', 'srcfield' ]
					});
					
					var ds_cbx_field = Ext.create('Ext.data.Store', {
						model	: 'field',
						data	: [
							{ 'idfield': 'userid', 	 'srcfield': 'USER ID' },
							{ 'idfield': 'username', 'srcfield': 'USER NAME' }
						]
					});
				//	end of json cbx field
				
				//	json cbx userlevel
					Ext.define('cbx_userlevel', {
						extend	: 'Ext.data.Model',
						fields	: [ 'userlevel' ]
					});
					
					var ds_cbx_userlevel = Ext.create('Ext.data.Store', {
						model	: 'cbx_userlevel',
						data	: [
							{ 'userlevel': 'ADMIN' },
							{ 'userlevel': 'USER' }
						]
					});
				//	end of json cbx userlevel
			//	----***----  //
			
			
			//	Grid data
			//	***
				//	grid panel
					var sm = Ext.create('Ext.selection.CheckboxModel');
					var clock = Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'g:i:s A')});
					var grd_stp_userlogin = Ext.create('Ext.grid.Panel', {
						stateId		: 'grd_stp_userlogin',
						renderTo	: 'grd_stp_userlogin',
						title		: 'Data User Login',
						store		: ds_stp_userlogin,
						width		: '100%',
						height		: 500,
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
							{ header: 'User ID', 		dataIndex: 'userid',	flex: 1 },
							{ header: 'User Name', 		dataIndex: 'username',	flex: 1 },
							{ header: 'User Pass', 		dataIndex: 'userpass',	flex: 1, hidden: true },
							{ header: 'User Level', 	dataIndex: 'userlevel',	flex: 1 }
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
								id		: 'btn_add',
								iconCls	: 'add',
								text 	: 'Add',
								tooltip	: 'Add',
								handler	: fnc_add_userlogin
							}, {
								xtype	: 'button',
								iconCls	: 'delete',
								text 	: 'Delete',
								tooltip	: 'Delete Data User Login',
								handler	: fnc_cbdel_userlogin
							}, {
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
									ds_stp_userlogin.proxy.setExtraParam('userlevel', '' );
									ds_stp_userlogin.proxy.setExtraParam('idfield', '' );
									ds_stp_userlogin.proxy.setExtraParam('srcfield', '' );
									ds_stp_userlogin.loadPage(1);
								}
							},
							'-',
							clock
						],
						bbar		: Ext.create('Ext.PagingToolbar', {
							pageSize	: itemperpage,
							store		: ds_stp_userlogin,
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
												xtype			: 'combo',
												id				: 'userlevel',
												name			: 'userlevel',
												fieldLabel		: 'User Level',
												queryMode		: 'local',
												displayField	: 'userlevel',
												valueField		: 'userlevel',
												emptyText		: 'Select a level..',
												flex			: 1,
												editable		: false,
												store			: ds_cbx_userlevel
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
												xtype			: 'combo',
												id				: 'idfield',
												name			: 'idfield',
												fieldLabel		: 'Field Name',
												queryMode		: 'local',
												displayField	: 'srcfield',
												valueField		: 'idfield',
												emptyText		: 'Select a field..',
												flex			: 1,
												editable		: false,
												store			: ds_cbx_field
											}, {
												xtype		: 'splitter'
											}, {
												xtype		: 'textfield',
												id			: 'srcfield',
												name		: 'srcfield',
												fieldLabel	: 'Enter a search',
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
										text   	: 'Reset',
										iconCls	: 'refresh',
										handler	: function() {
											this.up('form').getForm().reset();
										}
									}, {
										text	: 'Search',
										iconCls	: 'search',
										handler	: function() {
											ds_stp_userlogin.proxy.setExtraParam('userlevel', Ext.getCmp('userlevel').getValue() );
											ds_stp_userlogin.proxy.setExtraParam('idfield', Ext.getCmp('idfield').getValue() );
											ds_stp_userlogin.proxy.setExtraParam('srcfield', Ext.getCmp('srcfield').getValue() );
											ds_stp_userlogin.loadPage(1);
										}
									}
								]
							});
							
							win_filter = Ext.widget('window', {
								title			: 'Search by field',
								width			: 450,
								minWidth		: 450,
								height			: 200,
								minHeight		: 200,
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
				
				//	form add userlogin
					function fnc_add_userlogin() {
						var win_add_userlogin;
						
						if (!win_add_userlogin) {
							var frm_add_userlogin = Ext.widget('form', {
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
										xtype				: 'textfield',
										id					: 'userid',
										name				: 'userid',
										fieldLabel			: 'User ID',
										maxLength			: 25,
										allowBlank			: false,
										afterLabelTextTpl	: required,
										listeners			: {
											afterrender	: function(field) {
												field.focus(false, 1000);
											},
											change	: function(f,new_val) {
												f.setValue(new_val.toLowerCase());
											}
										}
									}, {
										xtype				: 'textfield',
										name				: 'username',
										fieldLabel			: 'User Name',
										maxLength			: 100,
										allowBlank			: false,
										afterLabelTextTpl	: required,
										listeners			: {
											change	: function(f,new_val) {
												f.setValue(new_val.toUpperCase());
											}
										}
									}, {
										xtype				: 'textfield',
										inputType			: 'password',
										name				: 'userpass',
										fieldLabel			: 'Password',
										maxLength			: 25,
										allowBlank			: false,
										afterLabelTextTpl	: required
									}, {
										xtype				: 'combo',
										name				: 'userlevel',
										fieldLabel			: 'User Level',
										queryMode			: 'local',
										displayField		: 'userlevel',
										valueField			: 'userlevel',
										emptyText			: 'Select a level..',
										editable			: false,
										allowBlank			: false,
										afterLabelTextTpl	: required,
										store				: ds_cbx_userlevel
									},
									//	type form
									{
										xtype	: 'hiddenfield',
										name	: 'typeform',
										value	: 'add'
									}
								],
								buttons			: [
									{
										text		: 'Submit',
										iconCls		: 'save',
										formBind	: true,
										handler		: function() {
											var form = this.up('form').getForm();
											var popwindow = this.up('window');
											if (form.isValid()) {
												form.submit({
													url		: 'response/resp_stp_userlogin.php',
													waitMsg	: 'sending data',
													
													success	: function(form, action) {
														form.reset();
														Ext.getCmp('userid').focus(false, 1000);
														ds_stp_userlogin.load();
													},
											
													failure	: function(form, action) {
														Ext.Msg.show({
															title		:'Failure - Add User Login',
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
							win_add_userlogin = Ext.widget('window', {
								title			: 'Add User Login',
								width			: 600,
								minWidth		: 600,
								height			: 230,
								minHeight		: 230,
								modal			: true,
								constrain		: true,
								layout			: 'fit',
								animateTarget	: 'btn_add',
								items			: frm_add_userlogin
							});
						}
						win_add_userlogin.show();
					}
				//	end of form add userlogin
				
				//	form upd userlogin
					var fnc_upd_userlogin = Ext.create('Ext.Action', {
						iconCls		: 'update',
						text		: 'Update Data User Login',
						handler		: function(widget, event) {
							var win_upd_userlogin;
							var rec_upd_userlogin = grd_stp_userlogin.getSelectionModel().getSelection()[0];
							
							if (!win_upd_userlogin) {
								var frm_upd_userlogin = Ext.widget('form', {
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
											xtype				: 'textfield',
											name				: 'userid',
											fieldLabel			: 'User ID',
											maxLength			: 25,
											readOnly			: true,
											value				: rec_upd_userlogin.get('userid')
										}, {
											xtype				: 'textfield',
											name				: 'username',
											fieldLabel			: 'User Name',
											maxLength			: 100,
											allowBlank			: false,
											afterLabelTextTpl	: required,
											value				: rec_upd_userlogin.get('username'),
											listeners			: {
												afterrender	: function(field) {
													field.focus(false, 1000);
												},
												change	: function(f,new_val) {
													f.setValue(new_val.toUpperCase());
												}
											}
										}, {
											xtype				: 'combo',
											id					: 'userlevel',
											name				: 'userlevel',
											fieldLabel			: 'User Level',
											queryMode			: 'local',
											displayField		: 'userlevel',
											valueField			: 'userlevel',
											editable			: false,
											allowBlank			: false,
											afterLabelTextTpl	: required,
											store				: ds_cbx_userlevel,
											value				: rec_upd_userlogin.get('userlevel')
										},
										//	type form
										{
											xtype	: 'hiddenfield',
											name	: 'typeform',
											value	: 'update'
										}
									],
									buttons			: [
										{
											text	: 'Update',
											iconCls	: 'save',
											formBind: true,
											handler	: function() {
												var form = this.up('form').getForm();
												var popwindow = this.up('window');
												if (form.isValid()) {
													form.submit({
														url		: 'response/resp_stp_userlogin.php',
														waitMsg	: 'sending data',
														
														success	: function(form, action) {
															popwindow.close();
															ds_stp_userlogin.load();
														},
												
														failure	: function(form, action) {
															Ext.Msg.show({
																title		:'Failure - Update User Login',
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
								
								win_upd_userlogin = Ext.widget('window', {
									title			: 'Update User Login',
									width			: 600,
									minWidth		: 600,
									height			: 190,
									minHeight		: 190,
									modal			: true,
									constrain		: true,
									layout			: 'fit',
									animateTarget	: 'btn_refresh',
									items			: frm_upd_userlogin
								});
							}
							win_upd_userlogin.show();
						}
					});
				//	end of form upd userlogin
				
				//	form del userlogin
					var fnc_del_userlogin = Ext.create('Ext.Action', {
						iconCls		: 'delete',
						text		: 'Delete Data User Login',
						handler		: function(widget, event) {
							var win_del_userlogin;
							var rec_del_userlogin = grd_stp_userlogin.getSelectionModel().getSelection()[0];
							
							if (!win_del_userlogin) {
								var frm_del_userlogin = Ext.widget('form', {
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
											xtype	: 'label',
											text	: 'Are you sure to delete : '+ rec_del_userlogin.get('userid') +' ??'
										}, {
											xtype	: 'hiddenfield',
											name	: 'userid',
											value	: rec_del_userlogin.get('userid')
										}, 
										//	type form
										{
											xtype	: 'hiddenfield',
											name	: 'typeform',
											value	: 'delete'
										}
									],
									buttons			: [
										{
											text	: 'Delete',
											iconCls	: 'delete',
											handler	: function() {
												var form = this.up('form').getForm();
												var popwindow = this.up('window');
												if (form.isValid()) {
													form.submit({
														url		: 'response/resp_stp_userlogin.php',
														waitMsg	: 'sending data',
														
														success	: function(form, action) {
															popwindow.close();
															ds_stp_userlogin.load();
														},
												
														failure	: function(form, action) {
															Ext.Msg.show({
																title		:'Failure - Delete User Login',
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
								
								win_del_userlogin = Ext.widget('window', {
									title			: 'Delete User Login',
									width			: 300,
									minWidth		: 300,
									height			: 150,
									minHeight		: 150,
									modal			: true,
									constrain		: true,
									layout			: 'fit',
									animateTarget	: 'btn_refresh',
									items			: frm_del_userlogin
								});
							}
							win_del_userlogin.show();
						}
					});
				//	end of form del userlogin
				
				//	form cbdel userlogin
					function fnc_cbdel_userlogin(widget, event) {
						var rec = grd_stp_userlogin.getSelectionModel().getSelection();
						
						var reclength = rec.length;
						var i = 0;
						var a = '';
						var b = '';
						var total = 0;
						
						for (var i=0; i < reclength; i++) {
							cb 	= a + '' + rec[i].data.userid;
							a 	= a + '' + rec[i].data.userid + '/';
							
							lbl = b + '' + rec[i].data.userid;
							b 	= b + '' + rec[i].data.userid + ', ';
							
							total++;
						}
						
						var win_cbdel_userlogin;
						if (!win_cbdel_userlogin) {
							var frm_cbdel_userlogin = Ext.widget('form', {
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
										xtype	: 'label',
										text	: 'Are you sure to delete this data '+ lbl +' ??'
									}, {
										xtype	: 'hiddenfield',
										name	: 'total',
										value	: total
									}, {
										xtype	: 'hiddenfield',
										name	: 'cb',
										value	: cb
									}, 
									//	type form
									{
										xtype	: 'hiddenfield',
										name	: 'typeform',
										value	: 'cbdelete'
									}
								],
								buttons			: [
									{
										text	: 'Delete',
										iconCls	: 'delete',
										handler	: function() {
											var form = this.up('form').getForm();
											var popwindow = this.up('window');
											if (form.isValid()) {
												form.submit({
													url		: 'response/resp_stp_userlogin.php',
													waitMsg	: 'sending data',
													
													success	: function(form, action) {
														popwindow.close();
														ds_stp_userlogin.load();
													},
											
													failure	: function(form, action) {
														Ext.Msg.show({
															title		:'Failure - Delete User Login',
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
							
							win_cbdel_userlogin = Ext.widget('window', {
								title			: 'Delete User Login',
								width			: 300,
								minWidth		: 300,
								height			: 150,
								minHeight		: 150,
								modal			: true,
								constrain		: true,
								layout			: 'fit',
								animateTarget	: 'btn_refresh',
								items			: frm_cbdel_userlogin
							});
						}
						win_cbdel_userlogin.show();
					}
				//	end of form cbdel userlogin
				
				//	Context menu
					var action_menu = Ext.create('Ext.menu.Menu', {
						items: [
							fnc_upd_userlogin,
							fnc_del_userlogin
						]
					});
				//	end context menu
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
		<div style="float:left;"> SETUP USER LOGIN </div> <div style="float:right"> Welcome: <?=$img .' <b>'. $sesiis_username?> </b></div>
		<br><br><br><br>
		
		<div id="grd_stp_userlogin"></div>
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