<?php
	session_start();
	
	include 'config.php';
	include 'include/language.php';
	include 'include/functions.php';
	include 'include/pages-config.php';
	
	fingerprint();
?>
<!DOCTYPE html>
<html lang="<?php print $language_code; ?>">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php print $lang['site_title'].' - '.$server_name; ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/market.css" rel="stylesheet">
	
    <link href="css/fonts.css" rel="stylesheet">

	<link rel="shortcut icon" href="images/favicon.ico?v=1" />
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-fixed-top" role="navigation">
		<div class="header_bg">
			<div id="BG_top">
				<div class="header_logo">
					<a href="index.php">
						<img src="images/logo.png" width="230" height="112">
					</a>
				</div>

				<?php if(is_loggedin()) { ?>
					<div class="user_info row">
							<div class="col-sm-2">
								<img src="images/misc/<?php print char_big_lvl(); ?>.png" width="38" height="38">
							</div>
							<div class="col-sm-10">
								<?php print $lang['balance'].': '.number_format(total_gold(), 0, '', '.'); ?> yang
							<br/>
							<a href="?p=characters"><?php print $lang['view_inventory']; ?></a> <?php if(is_loggedin()) print '<a href="?p=logout" class="btn btn-danger btn-xs" style="float: right !important;">'.$lang['logout'].'</a>'; ?>
							</div>
					</div>
				<?php } else { ?>
					<div class="user_info_logged_off row">
						<a href="?p=login" class="btn btn-info btn-lg btn-block"><?php print $lang['login']; ?></a>
					</div>
				<?php } ?>
					<div class="user_lang row">
						<div class="dropdown">
							<button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								<?php print $language_codes[$language_code]; ?>
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
								<?php
									foreach($language_codes as $key => $value)
										print '<li><a href="?lang='.$key.'">'.$value.'</a></li>';
								?>
							</ul>
						</div>
					</div>
			</div>
		</div>
    </nav>

    <!-- Page Content -->
    <div class="container">
	
		<?php
			switch ($current_page) {
				case 'home':
					include 'pages/home.php';
					break;
				case 'characters':
					include 'pages/characters.php';
					break;
				case 'inventory':
					include 'pages/inventory.php';
					break;
				case 'sell':
					include 'pages/sell.php';
					break;
				case 'item':
					include 'pages/item.php';
					break;
				case 'buy':
					include 'pages/buy.php';
					break;
				case 'claim':
					include 'pages/claim.php';
					break;
				case 'admin':
					include 'pages/admin.php';
					break;
				case 'login':
					include 'pages/login.php';
					break;
				case 'logout':
					include 'pages/logout.php';
					break;
				default:
					include 'pages/home.php';
			}
		?>

    </div>
    <!-- /.container -->
        <!-- Footer -->
        <footer class="footer">
            <div class="row">
                <div class="col-lg-2">
                </div>
                <div class="col-lg-10">
                    <p>&copy; 2016 <a href="http://metin2cms.cf/">Metin2CMS</a></p>
                </div>
            </div>
            <!-- /.row -->
        </footer>
    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
