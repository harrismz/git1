<?php
	$userid = $_SESSION['sesiis_userid'];
	$userlevel = $_SESSION['sesiis_level'];

	echo "<ul id=\"nav\">";
	if( $userlevel == 'ADMIN' ){
		if($userid == 'admin' || $userid == '31530' || $userid == '33109' ){
			echo "<li><a href=\"#\"> SETUP </a>".
					"<ul>".
						"<li><a href=\"stp_regbc25.php\"> SETUP REGISTER </a>".
						"<li><a href=\"stp_userlogin.php\"> SETUP USER </a>".
						"<li><a href=\"userlog.php\"> LOG USER </a></li>".
					"</ul>".
				 "</li>";
		}else{
			echo "<li><a href=\"#\"> SETUP </a>".
					"<ul>".
						"<li><a href=\"userlog.php\"> LOG USER </a></li>".
					"</ul>".
				 "</li>";
		}
			 
		echo "<li><a href=\"#\"> UPLOAD FILE </a>".
				"<ul>".
					"<li><a href=\"scrap_temp.php\"> SCRAP </a></li>".
					"<li><a href=\"material_temp.php\"> MATERIAL </a></li>".
					"<li><a href=\"fgood_temp.php\"> FINISH GOOD </a></li>".
				"</ul>".
			 "</li>";
			 
		echo "<li><a href=\"#\"> LAPORAN 4 BULAN </a>".
				"<ul>".
					"<li><a href=\"scrap.php\"> SCRAP </a></li>".
					"<li><a href=\"material.php\"> MATERIAL </a></li>".
					"<li><a href=\"finishgoods.php\"> FINISH GOODS </a></li>".
					"<li><a href=\"famachine.php\"> FIXED ASSET & MACHINE </a></li>".
				"</ul>".
			 "</li>";
			 
		echo "<li><a href=\"#\"> LAPORAN PERBULAN </a>".
				"<ul>".
					"<li><a href=\"inpermonth.php\"> PEMASUKAN PERDOKUMEN </a></li>".
					"<li><a href=\"outpermonth.php\"> PENGELUARAN PERBULAN</a></li>".
					"<li><a href=\"wip.php\"> BARANG DALAM PROSES (WIP) </a></li>".
				"</ul>".
			 "</li>";
		
		echo "<li class=\"last\"><a href=\"#\"> <img src=\"icons/myaccount.png\" style=\"margin-bottom:-4px;\" /> MY ACCOUNT </a>".
				"<ul>".
					"<li><a href=\"set_pswd.php\"> CHANGE PASSWORD </a></li>".
					"<li><a href=\"index.php\"> <img src=\"icons/logout.png\" style=\"margin-bottom:-4px;\" /> LOGOUT </a></li>".
				"</ul>".
			 "</li>";
	}
	elseif( $userlevel == 'USER' ){
		echo "<li><a href=\"#\"> LAPORAN 4 BULAN </a>".
				"<ul>".
					"<li><a href=\"material.php\"> MATERIAL </a></li>".
					"<li><a href=\"scrap.php\"> SCRAP </a></li>".
					"<li><a href=\"finishgoods.php\"> FINISH GOODS </a></li>".
					"<li><a href=\"famachine.php\"> FIXED ASSET & MACHINE </a></li>".
				"</ul>".
			 "</li>";
		
		echo "<li><a href=\"#\"> LAPORAN PERBULAN </a>".
				"<ul>".
					"<li><a href=\"inpermonth.php\"> PEMASUKAN PERDOKUMEN </a></li>".
					"<li><a href=\"outpermonth.php\"> PENGELUARAN PERBULAN</a></li>".
					"<li><a href=\"wip.php\"> BARANG DALAM PROSES (WIP) </a></li>".
				"</ul>".
			 "</li>";
	
		echo "<li class=\"last\"><a href=\"#\"> <img src=\"icons/myaccount.png\" style=\"margin-bottom:-4px;\" /> MY ACCOUNT </a>".
				"<ul>".
					"<li><a href=\"set_pswd.php\"> CHANGE PASSWORD </a></li>".
					"<li><a href=\"index.php\"> <img src=\"icons/logout.png\" style=\"margin-bottom:-4px;\" /> LOGOUT </a></li>".
				"</ul>".
			 "</li>";
	}
	
	echo "</ul>";
	echo "<div class=\"clear\"></div>";
?>