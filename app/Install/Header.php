<?php

/**
* Header for Install Script
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.1
*/

/** Check to see where the user is at within the install **/
if(isset($_GET['install_step'])){
  /** Check to see what step user is on **/
  if($_GET['install_step'] == "2"){
    /** User is on step 2 **/
    $step1_style = "btn-success";
    $step2_style = "btn-warning";
    $step3_style = "btn-info";
    $step4_style = "btn-info";
    $percentage = "25";
  }else if($_GET['install_step'] == "3"){
    /** User is on step 3 **/
    $step1_style = "btn-success";
    $step2_style = "btn-success";
    $step3_style = "btn-warning";
    $step4_style = "btn-info";
    $percentage = "50";
  }else if($_GET['install_step'] == "4"){
    /** User is on step 4 **/
    $step1_style = "btn-success";
    $step2_style = "btn-success";
    $step3_style = "btn-success";
    $step4_style = "btn-warning";
    $percentage = "75";
  }else if($_GET['install_step'] == "5"){
    /** User is on step 4 **/
    $step1_style = "btn-success";
    $step2_style = "btn-success";
    $step3_style = "btn-success";
    $step4_style = "btn-success";
    $percentage = "100";
  }
}else{
  /** User is on step 1 **/
  $step1_style = "btn-warning";
  $step2_style = "btn-info";
  $step3_style = "btn-info";
  $step4_style = "btn-info";
  $percentage = "0";
}

?>

<!DOCTYPE html>
<html lang="En">
<head>
    <meta charset="utf-8">
	<meta http-equiv='X-UA-Compatible' content='IE=edge'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>UAP v4 Installation</title>
	<link rel='shortcut icon' href='http://uap3demo.userapplepie.com/templates/default/assets/images/favicon.ico'>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div class='container'>
		<div class='row'>

			<div class='col-lg-12 col-md-12 col-sm-12'>

				<div class='panel panel-primary'>
          <div class='panel-heading'>
						<div class='row'>
              <div class='col-lg-12 col-md-12 col-sm-12' align='center'>
                <h3>UserApplePie v4 Installation</h3>
              </div>
              <div class='col-lg-12 col-md-12 col-sm-12'>
                <hr>
              </div>
							<div class='col-lg-3 col-md-3 col-sm-3' align='center'>
								<div class='btn <?=$step1_style?> btn-lg'>Step 1</div><br>
                <small>System Check</small>
							</div>
              <div class='col-lg-3 col-md-3 col-sm-3' align='center'>
                <div href='/?install_step=2' class='btn <?=$step2_style?> btn-lg'>Step 2</div><br>
                <small>System Settings</small>
              </div>
              <div class='col-lg-3 col-md-3 col-sm-3' align='center'>
                <div href='/?install_step=3' class='btn <?=$step3_style?> btn-lg'>Step 3</div><br>
                <small>Create Database</small>
              </div>
              <div class='col-lg-3 col-md-3 col-sm-3' align='center'>
                <div href='/?install_step=4' class='btn <?=$step4_style?> btn-lg'>Step 4</div><br>
                <small>Finalize Install</small>
              </div>
              <div class='col-lg-12 col-md-12 col-sm-12'>
                <hr>
              </div>
							<div class='col-lg-12 col-md-12 col-sm-12'>
                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
								  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?=$percentage?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$percentage?>%;"> &nbsp;<?=$percentage?>% </div>
                </div>
							</div>
						</div>
					</div>
				</div>
