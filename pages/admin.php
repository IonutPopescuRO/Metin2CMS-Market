<?php
	$admin_page = isset($_GET['a']) ? $_GET['a'] : null;
	switch ($admin_page) {
		case 'forbidden':
			include 'pages/admin/forbidden.php';
			break;
		default:
			include 'pages/admin/forbidden.php';
	}
?>