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
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $title.' - '.SITE_TITLE.' Admin Panel';?></title>
		<link rel='shortcut icon' href='<?=Url::templatePath()?>images/favicon.ico'>
    <?=Assets::css([
		SITE_URL.'Templates/AdminPanel/Assets/css/bootstrap.min.css',
		SITE_URL.'Templates/AdminPanel/Assets/css/font-awesome.min.css',
		SITE_URL.'Templates/AdminPanel/Assets/css/sb-admin.css'
    ]);
    ?>
</head>
<body class="fixed-nav sticky-footer bg-dark" id="page-top">

	<!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="#">UAP Admin Panel</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
          <a class="nav-link" href="<?php echo DIR; ?>AdminPanel">
            <i class="fa fa-fw fa-tachometer"></i>
            <span class="nav-link-text">Dashboard</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
          <a class="nav-link" href="<?php echo DIR; ?>AdminPanel-Settings">
            <i class="fa fa-fw fa-cog"></i>
            <span class="nav-link-text">Settings</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
          <a class="nav-link" href="<?php echo DIR; ?>AdminPanel-SystemRoutes">
            <i class="fa fa-fw fa-map-signs"></i>
            <span class="nav-link-text">System Routes</span>
          </a>
        </li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
					<a class="nav-link" href="<?php echo DIR; ?>AdminPanel-Users">
						<i class="fa fa-fw fa-users"></i>
						<span class="nav-link-text">Users</span>
					</a>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
					<a class="nav-link" href="<?php echo DIR; ?>AdminPanel-Groups">
						<i class="fa fa-fw fa-balance-scale"></i>
						<span class="nav-link-text">Groups</span>
					</a>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
					<a class="nav-link" href="<?php echo DIR; ?>AdminPanel-MassEmail">
						<i class="fa fa-fw fa-envelope-square"></i>
						<span class="nav-link-text">Mass Email</span>
					</a>
				</li>
			<?php
	      /* Check to see if Forum Plugin is installed. If so then show forum admin links */
	      if(file_exists(ROOTDIR.'app/Plugins/Forum/Controllers/ForumAdmin.php')){
	    ?>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Forum">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseForum" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-wrench"></i>
            <span class="nav-link-text">Forum</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseForum">
            <li>
              <a href="<?php echo DIR; ?>AdminPanel-Forum-Settings">Settings</a>
            </li>
            <li>
              <a href="<?php echo DIR; ?>AdminPanel-Forum-Categories">Categories</a>
            </li>
						<li>
							<a href="<?php echo DIR; ?>AdminPanel-Forum-Blocked-Content">Blocked Content</a>
						</li>
          </ul>
        </li>
			<?php } ?>
		</ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?php echo SITE_URL ?>">
            <i class="fa fa-fw fa-sign-out"></i>Main Site
					</a>
        </li>
      </ul>
    </div>
  </nav>

	<div class="content-wrapper">
    <div class="container-fluid">

		<!-- BreadCrumbs -->
		<?php
		// Display Breadcrumbs if set
		if(isset($data['breadcrumbs'])){
				echo "<ol class='breadcrumb'>";
					echo $data['breadcrumbs'];
				echo "</ol>";
		}
		?>
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
