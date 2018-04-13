<?php
/**
* Admin Panel Header
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.0
*/

use Libs\ErrorMessages,
	Libs\SuccessMessages,
	Libs\PageFunctions,
	Libs\Url,
	Libs\Assets;

	// Check to see what page is being viewed
	// If not Home, Login, Register, etc..
	// Send url to Session
	PageFunctions::prevpage();
?>

<!DOCTYPE html>
<html lang="<?php echo LANGUAGE_CODE; ?>">
<head>
    <meta charset="utf-8">
		<meta http-equiv='X-UA-Compatible' content='IE=edge'>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
    <title><?php echo $title.' - '.SITE_TITLE.' Admin Panel';?></title>
		<link rel='shortcut icon' href='<?=Url::templatePath()?>images/favicon.ico'>
    <?=Assets::css([
        'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css',
		'https://cdn.rawgit.com/google/code-prettify/master/src/prettify.css',
        SITE_URL.'Templates/AdminPanel/Assets/css/datepicker3.css',
		SITE_URL.'Templates/AdminPanel/Assets/css/styles.css',
    ]);
    ?>
</head>
<body>

	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand hidden-xs" href="<?=DIR?>AdminPanel"><span>UAP</span>Admin<span>Panel</span></a>
				<a class="navbar-brand hidden-sm hidden-md hidden-lg" href="<?=DIR?>AdminPanel"><span>UAP</span>A<span>P</span></a>
				<ul class="user-menu">
					<li class="dropdown pull-right">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class='fas fa-user' aria-hidden='true'></span> <?php echo $data['currentUserData'][0]->username; ?> <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?=DIR?>"><span class='fas fa-eject' aria-hidden='true'></span> Back To Main Site</a></li>
							<li><a href='<?php echo DIR; ?>Logout'><span class='fas fa-off' aria-hidden='true'></span> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>

		</div><!-- /.container-fluid -->
	</nav>

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
	<div class="row">

		<!-- BreadCrumbs -->
		<?php
		// Display Breadcrumbs if set
		if(isset($data['breadcrumbs'])){
				echo "<ol class='breadcrumb'>";
					echo $data['breadcrumbs'];
				echo "</ol>";
		}
		?>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<h1 class="page-header"><?=$title?></h1>
		</div>
	</div><!--/.row-->
	<div class="row">
		<?php
		// Setup the Error and Success Messages Libs
		// Display Success and Error Messages if any
		echo ErrorMessages::display();
		echo SuccessMessages::display();
		if(isset($error)) { echo ErrorMessages::display_raw($error); }
		if(isset($success)) { echo SuccessMessages::display_raw($success); }
		?>
	</div>
	<div class="row">
