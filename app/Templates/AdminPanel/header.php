<?php
use Helpers\ErrorHelper,
	Helpers\SuccessHelper,
	Helpers\PageFunctions;

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
    <title><?php echo $title.' - '.SITETITLE.' Admin Panel';?></title>
		<link rel='shortcut icon' href='<?php echo Url::templatePath(); ?>images/favicon.ico'>
    <?php
    echo $meta;//place to pass data / plugable hook zone
    Assets::css([
        'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css',
				'https://cdn.rawgit.com/google/code-prettify/master/src/prettify.css',
        Url::templatePath('AdminPanel').'css/datepicker3.css',
				Url::templatePath('AdminPanel').'css/styles.css',
    ]);
    echo $css; //place to pass data / plugable hook zone
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
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> <?php echo $data['currentUserData'][0]->username; ?> <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?=DIR?>"><svg class="glyph stroked cancel"><use xlink:href="#stroked-cancel"></use></svg> Back To Main Site</a></li>
						</ul>
					</li>
				</ul>
			</div>

		</div><!-- /.container-fluid -->
	</nav>

<?php echo $afterBody; //place to pass data / plugable hook zone?>

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
		// Setup the Error and Success Messages Helpers
		// Display Success and Error Messages if any
		echo ErrorHelper::display();
		echo SuccessHelper::display();
		echo ErrorHelper::display_raw($error);
		echo SuccessHelper::display_raw($success);
		?>
	</div>
	<div class="row">
