<?php
	session_start();

	unset( $_SESSION['sesiis_userid'] );
	session_destroy();
?>
<!doctype html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title> IT INVENTORY </title>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="shortcut icon" href= "icons/iconcustoms.ico"/>
	
	<link rel="stylesheet" type="text/css" href="./extjs/resources/css/ext-all.css" />
	<!-- <link rel="stylesheet" type="text/css" href="../extjs-4.1.1/resources/css/ext-all.css"/> -->
		<link rel="stylesheet" type="text/css" href="./extjs/resources/css/ext-all.css"/>
    <!-- <link rel="stylesheet" type="text/css" href="../extjs-4.1.1/examples/shared/example.css" /> -->
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
		
		
		.login {
             background-image:url(icons/login.png) !important;
        }
	</style>
	
	<script type="text/javascript">
		//Ext.Loader.setConfig({enabled: true});
		Ext.Loader.setConfig({enabled: true, disableCaching: true});
		
		// // Ext.Loader.setPath('Ext.ux', '../extjs-4.1.1/examples/ux/');
			Ext.Loader.setPath('Ext.ux', './extjs/examples/ux/');
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
			
			var win_login_iis;
			var clock = Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'g:i:s A')});
			
			if (!win_login_iis) {
				var form = Ext.widget('form', {
					layout		: {
						type	: 'vbox',
						align	: 'stretch'
					},
					border		: false,
					bodyPadding	: 10,

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
							xtype		: 'textfield',
							id			: 'userid',
							name		: 'userid',
							fieldLabel	: 'User ID',
							maxLength	: 25,
							allowBlank	: false,
							listeners 	: {
								afterrender	: function(field) {
									field.focus(false, 1000);
								},
								change	: function(f,new_val) {
									f.setValue(new_val.toLowerCase());
								}
							}
						}, {
							xtype		: 'textfield',
							inputType	: 'password',
							name		: 'userpass',
							fieldLabel	: 'Password',
							maxLength	: 25,
							allowBlank	: false
						}
					],
					buttons			: [
						{
							text		: 'Login',
							iconCls		: 'login',
							formBind	: true,
							handler		: function() {
								var form = this.up('form').getForm();
								var popwindow = this.up('window');
								if (form.isValid()) {
									form.submit({
										url		: 'response/cek_login.php',
										waitMsg	: 'sending data',
										
										success	: function(form, action) {
											location.href=action.result.msg;
										},
								
										failure	: function(form, action) {
											Ext.getCmp('userid').focus(false, 1000);
											Ext.Msg.show({
												title		:'Failure - Login',
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

				win_login_iis = Ext.widget('window', {				
					title			: 'Login',
					width			: 400,
					minWidth		: 400,
					height			: 200,
					minHeight		: 200,
					constrain		: true,
					closable		: false,
					layout			: 'fit',
					items			: form,
					listeners		: {
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
					tbar			: Ext.create('Ext.ux.StatusBar', {
						id			: 'sb_log_iis',
						defaultText	: Ext.Date.format(new Date(), 'l, d F Y'),
						items		: [
							clock, ' '
						]
					})
				});
			}
			win_login_iis.show();
		});
	</script>
</head>
<body id="body">

<header>
	<?php //include "header.php"; ?> 
</header> 

<section>
	<div class="wrapper_section">
		<div id="separator"></div>
		<div style="float:left;"> LOGIN </div> <div style="float:right"> Welcome </b></div>
		<br><br><br><br>
		
		<div id=""></div>
	</div>
</section>

<footer>
	<?php include "footer.php"; ?>
</footer>

</body>
</html>