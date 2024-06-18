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
		
		
		.save {
             background-image:url(icons/save.png) !important;
        }
		.update {
             background-image:url(icons/update.png) !important;
        }
	</style>
	
	<script type="text/javascript">
		Ext.Loader.setConfig({enabled: true});
		
		Ext.Loader.setPath('Ext.ux', '../extjs-4.1.1/examples/ux/');
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
			
			//	Form panel
				var frm_panel = Ext.create('Ext.form.Panel', {
					frame			: true,
					renderTo		: 'frm_panel',
					title			: 'Change Password',
					bodyStyle		: 'padding:5px 5px 0',
					width			: 350,
					fieldDefaults	: {
						labelWidth	: 110,
						labelStyle	: 'font-weight:bold',
						msgTarget	: 'side'
					},
					defaultType		: 'textfield',
					defaults: {
						anchor		: '100%'
					},
					items			: [
						{
							xtype		: 'textfield',
							id			: 'userid',
							name		: 'userid',
							fieldLabel	: 'User ID',
							readOnly	: true,
							value		: '<?=$sesiis_userid?>'
						}, {
							xtype				: 'textfield',
							inputType			: 'password',
							id					: 'old_userpass',
							name				: 'old_userpass',
							fieldLabel			: 'Old Password',
							maxLength			: 25,
							allowBlank			: false,
							afterLabelTextTpl	: required,
							listeners 			: {
								afterrender	: function(field) {
									field.focus(false, 1000);
								}
							}
						}, {
							xtype				: 'textfield',
							inputType			: 'password',
							name				: 'new_userpass',
							fieldLabel			: 'New Password',
							maxLength			: 25,
							allowBlank			: false,
							afterLabelTextTpl	: required
						}
					],
					buttons: [
						{
							text		: 'Update',
							iconCls		: 'save',
							formBind	: true,
							handler		: function() {
								var form = this.up('form').getForm();
								var popwindow = this.up('window');
								if (form.isValid()) {
									form.submit({
										url		: 'response/set_pswd_upd.php',
										waitMsg	: 'sending data',
										
										success	: function(form, action) {
											Ext.Msg.show({
												title		:'Success - Change Password',
												icon		: Ext.Msg.INFO,
												msg			: action.result.msg,
												buttons		: Ext.Msg.OK
											});
										},
								
										failure	: function(form, action) {
											Ext.getCmp('old_userpass').focus(false, 1000);
											Ext.Msg.show({
												title		:'Failure - Change Password',
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
		<div style="float:left;"> CHANGE PASSWORD </div> <div style="float:right"> Welcome: <?=$img .' <b>'. $sesiis_username?> </b></div>
		<br><br><br><br>
		
		<div id="frm_panel"></div>
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