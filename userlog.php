<?php	
	session_start();
	$sesmis_userid 		= $_SESSION['sesiis_userid'];
	$sesmis_username	= $_SESSION['sesiis_username'];
	$sesmis_level		= $_SESSION['sesiis_level'];
	$sesmis_dept		= $_SESSION['sesiis_dept'];
	
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
					return;
				}
			);

		});
	</script>
		
	<!-- <link rel="stylesheet" type="text/css" href="../extjs-4.1.1/resources/css/ext-all.css"/> -->
		<link rel="stylesheet" type="text/css" href="./extjs/resources/css/ext-all.css"/>
    <!-- <link rel="stylesheet" type="text/css" href="../extjs-4.1.1/examples/shared/example.css" /> -->
	<link rel="stylesheet" type="text/css" href="./extjs/resources/css/ext-all.css" />
    <!-- <link rel="stylesheet" type="text/css" href="./extjs/examples/shared/example.css" /> -->
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
	</style>
	
	<script type="text/javascript">
		Ext.Loader.setConfig({enabled: true});
		
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
				//	json stp_userlog
					var itemperpage = 50;
					Ext.define('stp_userlog', {
						extend	: 'Ext.data.Model',
						fields	: [ 'id_log', 'user_action', 'action', 'target', 'description', 'date_action' ]
					});
					
					var ds_stp_userlog = Ext.create('Ext.data.Store', {
						model		: 'stp_userlog',
						autoLoad	: true,
						pageSize	: itemperpage,
						fields		: [ 'id_log', 'user_action', 'action', 'target', 'description', 'date_action' ],
						proxy		: {
							type	: 'ajax',
							url		: 'json/json_userlog.php',
							reader	: {
								type			: 'json',
								root			: 'rows',
								totalProperty	: 'totalCount'
							}
						}
					});
				//	end of json stp_userlog
				
				//	json cbx field
					Ext.define('field', {
						extend	: 'Ext.data.Model',
						fields	: [ 'idfield', 'srcfield' ]
					});
					
					var ds_cbx_field = Ext.create('Ext.data.Store', {
						model	: 'field',
						data	: [
							{ 'idfield': 'user_action',	'srcfield': 'USER' },
							{ 'idfield': 'action',		'srcfield': 'ACTION' }
						]
					});
				//	end of json cbx field
			//	----***----  //
			
			
			//	Grid data
			//	***
				//	grid panel
					var sm = Ext.create('Ext.selection.CheckboxModel');
					var clock = Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'g:i:s A')});
					var grd_stp_userlog = Ext.create('Ext.grid.Panel', {
						stateId		: 'grd_stp_userlog',
						renderTo	: 'grd_stp_userlog',
						title		: 'Data User Log',
						store		: ds_stp_userlog,
						width		: '100%',
						height		: window.innerHeight - 320,
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
							{ header: 'ID Log', 		dataIndex: 'id_log',		flex: 1 },
							{ header: 'User Action', 	dataIndex: 'user_action',	flex: 1 },
							{ header: 'Action', 		dataIndex: 'action',		flex: 1 },
							{ header: 'Target', 		dataIndex: 'target',		flex: 1 },
							{ header: 'Description', 	dataIndex: 'description',	flex: 1 },
							{ header: 'Date Action', 	dataIndex: 'date_action',	flex: 1 }
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
								text		: 'Search by field :',
								margins		: '20 10 0 10'
							}, {
								xtype			: 'combo',
								id				: 'idfield',
								name			: 'idfield',
								queryMode		: 'local',
								displayField	: 'srcfield',
								valueField		: 'idfield',
								emptyText		: 'field',
								flex			: 1,
								editable		: false,
								store			: ds_cbx_field
							}, {
								xtype		: 'textfield',
								id			: 'srcfield',
								flex		: 1,
								emptyText	: '...',
								listeners 	: {
									change	: function(f,new_val) {
										f.setValue(new_val.toUpperCase());
									}
								}
							}, {
								xtype		: 'button',
								iconCls		: 'search',
								tooltip		: 'Search',
								handler 	: function(){
									var idfield 			= Ext.getCmp('idfield').getValue();
									var val_search 			= Ext.getCmp('srcfield').getValue();
									if(idfield == null)
									  {
										val_idfield = "";
									  }
									else
									  {
										val_idfield = idfield;
									  }
										
									if (val_idfield != "" || val_search != "")
									  {
										ds_stp_userlog.load({ url: 'http://136.198.117.18:85/customs/json/json_stp_userlog.php?idfield='+ val_idfield +'&srcfield='+ val_search +'' });
									  }
									else
									  {
										ds_stp_userlog.load({ url: 'http://136.198.117.18:85/customs/json/json_stp_userlog.php?idfield=nik&srcfield=nik' });
									  }
								}
							},
							'->',
							{
								xtype	: 'button',
								id		: 'btn_refresh',
								iconCls	: 'refresh',
								text 	: 'Refresh',
								tooltip	: 'Refresh',
								handler: function (){
									ds_stp_userlog.load();
									Ext.getCmp('idfield').setValue("");
									Ext.getCmp('srcfield').setValue("");
								}
							},
							'-',
							clock
						],
						bbar		: Ext.create('Ext.PagingToolbar', {
							pageSize	: itemperpage,
							store		: ds_stp_userlog,
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
		<div style="float:left;"> Setup User Log </div> <div style="float:right"> Welcome: <?=$img .' <b>'. $sesmis_username?> </b></div>
		<br><br><br><br>
		
		<div id="grd_stp_userlog"></div>
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