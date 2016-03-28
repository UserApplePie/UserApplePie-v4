<?php
use Helpers\ErrorHelper,
	Helpers\SuccessHelper;

?>

<!DOCTYPE html>
<html lang="<?php echo LANGUAGE_CODE; ?>">
<head>
    <meta charset="utf-8">
		<meta http-equiv='X-UA-Compatible' content='IE=edge'>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
    <title><?php echo $title.' - '.SITETITLE;?></title>
    <?php
    echo $meta;//place to pass data / plugable hook zone
    Assets::css([
        'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css',
        Url::templatePath().'css/style.css',
    ]);
    echo $css; //place to pass data / plugable hook zone
    ?>
</head>
<body>

  <nav class="navbar navbar-default">
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
  			</ul>
  			<ul class="nav navbar-nav navbar-right">
  				<?php if(!$data['isLoggedIn']){ ?>
  					<li><a href="<?=DIR?>Resend-Activation-Email">Resend Activation</a></li>
  					<li><a href="<?=DIR?>Forgot-Password">Forgot Password</a></li>
  					<li><a href="<?=DIR?>Login">Login</a></li>
  					<li><a href="<?=DIR?>Register">Register</a></li>
  				<?php }else{ ?>
						<li><a href="<?=DIR?>Account-Settings">Account Settings</a></li>
  					<li><a href="<?=DIR?>Logout">Logout</a></li>
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
