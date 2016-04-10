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
    <title><?php echo $title.' - '.SITETITLE;?></title>
		<link rel='shortcut icon' href='<?php echo Url::templatePath(); ?>images/favicon.ico'>
    <?php
    echo $meta;//place to pass data / plugable hook zone
    Assets::css([
        'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css',
		Url::templatePath().'css/bootstrap.css',
        Url::templatePath().'css/style.css',
    ]);
    echo $css; //place to pass data / plugable hook zone
    ?>
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
  			<a class="navbar-brand" href="<?=DIR?>"><?=SITETITLE?></a>
  		</div>

  		<!-- Collect the nav links, forms, and other content for toggling -->
  		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
  			<ul class="nav navbar-nav">
  				<li><a href="<?=DIR?>">Home</a></li>
					<?php
						/* Check to see if Private Message Module is installed, if it is show link */
						if(file_exists('../app/Modules/Forum/forum.module.php')){
							echo "<li><a href='".DIR."Forum' title='Forum'> Forum </a></li>";
						}
					?>
  			</ul>
  			<ul class="nav navbar-nav navbar-right">
  				<?php if(!$isLoggedIn){ ?>
  					<li><a href="<?=DIR?>Login">Login</a></li>
  					<li><a href="<?=DIR?>Register">Register</a></li>
  				<?php }else{ ?>
							<li class='dropdown'>
								<a href='#' title='<?php echo $data['currentUserData'][0]->username; ?>' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
								<span class='glyphicon glyphicon-user' aria-hidden='true'></span> <?php echo $data['currentUserData'][0]->username; ?>
								<?php
								          // Check to see if there are any unread messages in inbox
													$notifi_count = \Helpers\CurrentUserData::getUnreadMessages($data['currentUserData'][0]->userID);
								          if($notifi_count >= "1"){
								            echo "<span class='badge'>".$notifi_count."</span>";
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
															if(!empty($data['currentUserData'][0]->userImage)){
																echo "<img src='".DIR.$data['currentUserData'][0]->userImage."' class='img-responsive'>";
															}else{
																echo "<span class='glyphicon glyphicon-user icon-size'></span>";
															}
														?>
														</div>
													</div>
													<div class="col-lg-8 col-md-8">
														<p class="text-left"><strong><h5><?php echo $data['currentUserData'][0]->username; if(isset($data['currentUserData'][0]->firstName)){echo "  <small>".$data['currentUserData'][0]->firstName."</small>";}?></h5></strong></p>
														<p class="text-left small"><?php echo $data['currentUserData'][0]->email; ?></p>
														<p class="text-left">
															<a href='<?php echo DIR."Profile/".$data['currentUserData'][0]->username; ?>' title='View Your Profile' class='btn btn-primary btn-block btn-xs'> <span class='glyphicon glyphicon-user' aria-hidden='true'></span> View Profile</a>
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
																		<a href='<?=DIR?>Account-Settings' title='Change Your Account Settings' class='btn btn-info btn-block btn-xs'> <span class='glyphicon glyphicon-briefcase' aria-hidden='true'></span> Account Settings</a>
																		<?php
																			/* Check to see if Private Message Module is installed, if it is show link */
																			if(file_exists('../app/Modules/Messages/messages.module.php')){
																				echo "<a href='".DIR."Messages' title='Private Messages' class='btn btn-danger btn-block btn-xs'> <span class='glyphicon glyphicon-envelope' aria-hidden='true'></span> Private Messages ";
																					// Check to see if there are any unread messages in inbox
																					$new_msg_count = \Helpers\CurrentUserData::getUnreadMessages($data['currentUserData'][0]->userID);
																					if($new_msg_count >= "1"){
																						echo "<span class='badge'>".$new_msg_count."</span>";
																					}
																				echo " </a>";
																			}
																		?>
																		<?php if($data['isAdmin'] == 'true'){ // Display Admin Panel Links if User Is Admin ?>
																			<a href='<?php echo DIR; ?>AdminPanel' title='Open Admin Panel' class='btn btn-warning btn-block btn-xs'> <span class='glyphicon glyphicon-dashboard' aria-hidden='true'></span> Admin Panel</a>
																		<?php } ?>
                                </p>
                            </div>
                        </div>
                    </div>
									</li>
								</ul>
							<li><a href='<?php echo DIR; ?>Logout'>Logout</a></li>
  				<?php }?>
  			</ul>
  		</div><!-- /.navbar-collapse -->
  	</div><!-- /.container-fluid -->
  </nav>

<?php echo $afterBody; //place to pass data / plugable hook zone?>

<div class="container">
  <div class="row">

		<!-- BreadCrumbs -->
		<?php
		// Display Breadcrumbs if set
		if(isset($data['breadcrumbs'])){
			echo "<div class='col-lg-12 col-md-12'>";
				echo "<ol class='breadcrumb'>";
					echo "<li><a href='".DIR."'>Home</a></li>";
					echo $data['breadcrumbs'];
				echo "</ol>";
			echo "</div>";
		}
		?>

		<?php
		// Setup the Error and Success Messages Helpers
		// Display Success and Error Messages if any
		echo ErrorHelper::display();
		echo SuccessHelper::display();
		echo ErrorHelper::display_raw($error);
		echo SuccessHelper::display_raw($success);
		?>
