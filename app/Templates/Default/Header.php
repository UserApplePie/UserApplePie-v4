<?php
use Libs\Assets,
    Libs\Language,
    Libs\SuccessMessages,
    Libs\ErrorMessages,
    Libs\PageFunctions,
    Libs\Url;

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
        <title><?=SITE_TITLE?><?=(isset($pageTitle)) ? " - ".$pageTitle : "" ?></title>
        <meta name="keywords" content="<?=SITE_KEYWORDS?>">
        <meta name="description" content="<?=SITE_DESCRIPTION?>">
        <?=Assets::css([
            'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
            'https://bootswatch.com/cerulean/bootstrap.css',
            'https://bootswatch.com/assets/css/custom.min.css',
            'https://cdn.rawgit.com/google/code-prettify/master/src/prettify.css',
            SITE_URL.'Templates/Default/Assets/css/style.css'
        ])?>
        <?=(isset($css)) ? $css : ""?>
        <?=(isset($header)) ? $header : ""?>
    </head>
    <body>
    <nav class="navbar navbar-default navbar-fixed-top">
  	<div class="container">
  		<!-- Brand and toggle get grouped for better mobile display -->
  		<div class="navbar-header">
  			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
  				<span class="sr-only">Toggle navigation</span>
  				<span class="icon-bar"></span>
  				<span class="icon-bar"></span>
  				<span class="icon-bar"></span>
  			</button>
			<img class='navbar-brand' src='<?php echo Url::templatePath(); ?>images/logo.gif'>
  			<a class="navbar-brand" href="<?=DIR?>"><?=SITE_TITLE?></a>
  		</div>

  		<!-- Collect the nav links, forms, and other content for toggling -->
  		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
  			<ul class="nav navbar-nav">
  				<li><a href="<?=DIR?>"><?=Language::show('uap_home', 'Welcome');?></a></li>
					<?php
						/* Check to see if Private Message Module is installed, if it is show link */
						if(file_exists('../app/Plugins/Forum/Controllers/Forum.php')){
							echo "<li><a href='".DIR."Forum' title='Forum'> ".Language::show('uap_forum', 'Welcome')." </a></li>";
						}
					?>
  			</ul>
  			<ul class="nav navbar-nav navbar-right">
  				<?php if(!$isLoggedIn){ ?>
  					<li><a href="<?=DIR?>Login"><?=Language::show('login_button', 'Auth');?></a></li>
  					<li><a href="<?=DIR?>Register"><?=Language::show('register_button', 'Auth');?></a></li>
  				<?php }else{ ?>
							<li class='dropdown'>
								<a href='#' title='<?php echo $currentUserData[0]->username; ?>' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
								<span class='glyphicon glyphicon-user' aria-hidden='true'></span> <?php echo $currentUserData[0]->username; ?>
								<?php
									/* Check to see if Private Message Module is installed, if it is show link */
									if(file_exists('../app/Plugins/Messages/Controllers/Messages.php')){
										// Check to see if there are any unread messages in inbox
										$notifi_count = \Libs\CurrentUserData::getUnreadMessages($currentUserData[0]->userID);
										if($notifi_count >= "1"){
										echo "<span class='badge'>".$notifi_count."</span>";
										}
									}
								?>
								<span class='caret'></span> </a>
									<ul class='dropdown-menu'>
										<li>
											<div class="navbar-login">
												<div class="row">
													<div class="col-lg-4 col-md-4" align="center">
														<div class="col-centered" align="center">
														<?php // Check to see if user has a profile image
															if(!empty($currentUserData[0]->userImage)){
																echo "<img src='".SITE_URL.$currentUserData[0]->userImage."' class='img-responsive'>";
															}else{
																echo "<span class='glyphicon glyphicon-user icon-size'></span>";
															}
														?>
														</div>
													</div>
													<div class="col-lg-8 col-md-8">
														<p class="text-left"><strong><h5><?php echo $currentUserData[0]->username; if(isset($currentUserData[0]->firstName)){echo "  <small>".$currentUserData[0]->firstName."</small>";}; if(isset($currentUserData[0]->lastName)){echo "  <small>".$currentUserData[0]->lastName."</small>";} ?></h5></strong></p>
														<p class="text-left small"><?php echo $currentUserData[0]->email; ?></p>
														<p class="text-left">
															<a href='<?php echo DIR."Profile/".$currentUserData[0]->username; ?>' title='View Your Profile' class='btn btn-primary btn-block btn-xs'> <span class='glyphicon glyphicon-user' aria-hidden='true'></span> <?=Language::show('uap_view_profile', 'Welcome');?></a>
														</p>
													</div>
												</div>
											</div>
                      <li class="divider"></li>
                      <li>
                      <div class="navbar-login navbar-login-session">
                          <div class="row">
                              <div class="col-lg-12">
                                  <p>
										<a href='<?=DIR?>Account-Settings' title='Change Your Account Settings' class='btn btn-info btn-block btn-xs'> <span class='glyphicon glyphicon-briefcase' aria-hidden='true'></span> <?=Language::show('uap_account_settings', 'Welcome');?></a>
										<?php
											/* Check to see if Private Message Module is installed, if it is show link */
											if(file_exists('../app/Plugins/Messages/Controllers/Messages.php')){
												echo "<a href='".DIR."Messages' title='Private Messages' class='btn btn-danger btn-block btn-xs'> <span class='glyphicon glyphicon-envelope' aria-hidden='true'></span> ".Language::show('uap_private_messages', 'Welcome');
													// Check to see if there are any unread messages in inbox
													$new_msg_count = \Libs\CurrentUserData::getUnreadMessages($currentUserData[0]->userID);
													if($new_msg_count >= "1"){
														echo "<span class='badge'>".$new_msg_count."</span>";
													}
												echo " </a>";
											}
										?>
										<?php if($isAdmin == 'true'){ // Display Admin Panel Links if User Is Admin ?>
											<a href='<?php echo DIR; ?>AdminPanel' title='Open Admin Panel' class='btn btn-warning btn-block btn-xs'> <span class='glyphicon glyphicon-dashboard' aria-hidden='true'></span> <?=Language::show('uap_admin_panel', 'Welcome');?></a>
										<?php } ?>
                                </p>
                            </div>
                        </div>
                    </div>
									</li>
								</ul>
							<li><a href='<?php echo DIR; ?>Logout'><?=Language::show('uap_logout', 'Welcome');?></a></li>
  				<?php }?>
  			</ul>
  		</div><!-- /.navbar-collapse -->
  	</div><!-- /.container-fluid -->
</nav>


        <div class="container">
            <div class="row">

              <!-- BreadCrumbs -->
              <?php
              // Display Breadcrumbs if set
              if(isset($breadcrumbs)){
                echo "<div class='col-lg-12 col-md-12 col-sm-12'>";
                  echo "<ol class='breadcrumb'>";
                    echo "<li><a href='".DIR."'>".Language::show('uap_home', 'Welcome')."</a></li>";
                    echo $breadcrumbs;
                  echo "</ol>";
                echo "</div>";
              }
              ?>

              <?php
              // Setup the Error and Success Messages Libs
              // Display Success and Error Messages if any
              echo ErrorMessages::display();
              echo SuccessMessages::display();
              if(isset($error)) { echo ErrorMessages::display_raw($error); }
              if(isset($success)) { echo SuccessMessages::display_raw($success); }
              ?>
