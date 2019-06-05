<?php
/**
* Default Header
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

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
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
        <title><?=SITE_TITLE?><?=(isset($pageTitle)) ? " - ".$pageTitle : " - ".$title ?></title>
        <meta name="keywords" content="<?=SITE_KEYWORDS?>">
        <meta name="description" content="<?=SITE_DESCRIPTION?>">
        <link rel='shortcut icon' href='<?=Url::templatePath()?>images/favicon.ico'>
        <?=Assets::css([
            'https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css',
            SITE_URL.'assets/themes/'.SITE_THEME.'/bootstrap.css',
            'https://bootswatch.com/_assets/css/custom.min.css',
            'https://cdn.rawgit.com/google/code-prettify/master/src/prettify.css',
            SITE_URL.'Templates/Default/Assets/css/style.css'
        ])?>
        <?=(isset($css)) ? $css : ""?>
        <?=(isset($header)) ? $header : ""?>
    </head>
    <body class="fixed-nav sticky-footer bg-light" id="page-top">
      <div class="navbar navbar-expand-lg fixed-top navbar-dark bg-primary">

          <img class='navbar-brand' src='<?php echo Url::templatePath(); ?>images/logo.gif'>
          <a href="<?=SITE_URL?>" class="navbar-brand"><?=SITE_TITLE?></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav">
              <?php echo PageFunctions::getLinks('header_main'); ?>
            </ul>

            <ul class="nav navbar-nav ml-auto">
              <?php if(!$isLoggedIn){ ?>
                <li><a class='nav-link' href="<?=SITE_URL?>Login"><?=Language::show('login_button', 'Auth');?></a></li>
                <li><a class='nav-link' href="<?=SITE_URL?>Register"><?=Language::show('register_button', 'Auth');?></a></li>
              <?php }else{ ?>
                  <li class='nav-item dropdown'>
                    <a href='#' title='<?php echo $currentUserData[0]->username; ?>' class='nav-link dropdown-toggle' data-toggle='dropdown' id='themes'>
                    <span class='fas fa-user' aria-hidden='true'></span> <?php echo $currentUserData[0]->username; ?>
                    <?php
                      /** Check to see if Friends Plugin is installed, if it is show link **/
                      if(file_exists(ROOTDIR.'app/Plugins/Friends/Controllers/Friends.php')){
                          /** Check to see if there are any pending friend request for current user **/
                          $notifi_count_fr = \Libs\CurrentUserData::getFriendRequests($currentUserData[0]->userID);
                      }
                      /** Check to see if Private Message Plugin is installed, if it is show link **/
                      if(file_exists(ROOTDIR.'app/Plugins/Messages/Controllers/Messages.php')){
                        /** Check to see if there are any unread messages in inbox **/
                        $notifi_count = \Libs\CurrentUserData::getUnreadMessages($currentUserData[0]->userID);
                      }
                      if($notifi_count_fr >= "1" || $notifi_count >= "1"){
                          $notifi_total = $notifi_count_fr + $notifi_count;
                          if($notifi_total >= "1"){
                          echo "<span class='badge badge-light'>".$notifi_total."</span>";
                          }
                      }
                    ?>
                    <span class='caret'></span> </a>
                      <ul class='dropdown-menu dropdown-menu-right'>
                        <li>
                          <div class="navbar-login">
                            <div class="row">
                              <div class="col-4" align="center">
                                <div class="col-centered" align="center">
                                  <?php // Check to see if user has a profile image
                                    $user_image_display = \Libs\CurrentUserData::getUserImage($currentUserData[0]->userID);
                                    if(!empty($user_image_display)){
                                      echo "<img src='".SITE_URL.IMG_DIR_PROFILE.$user_image_display."' class='rounded img-fluid'>";
                                    }else{
                                      echo "<span class='fas fa-user icon-size'></span>";
                                    }
                                  ?>
                                </div>
                              </div>
                              <div class="col-8">
                                <p class="h5"><?php echo $currentUserData[0]->username; if(isset($currentUserData[0]->firstName)){echo " <small class='navbar-login-text'>".$currentUserData[0]->firstName."</small>";}; if(isset($currentUserData[0]->lastName)){echo "  <small class='navbar-login-text'>".$currentUserData[0]->lastName."</small>";} ?></p>
                                <p class="h6"><?php echo $currentUserData[0]->email; ?></p>
                                <p>
                                  <a href='<?php echo SITE_URL."Profile/".$currentUserData[0]->username; ?>' title='View Your Profile' class='btn btn-primary btn-block btn-sm'> <span class='fas fa-user' aria-hidden='true'></span> <?=Language::show('uap_view_profile', 'Welcome');?></a>
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
                        <a href='<?=SITE_URL?>Account-Settings' title='Change Your Account Settings' class='btn btn-info btn-block btn-sm'> <span class='fas fa-briefcase' aria-hidden='true'></span> <?=Language::show('uap_account_settings', 'Welcome');?></a>
                        <?php
                          /** Check to see if Friends Plugin is installed, if it is show link **/
                          if(file_exists(ROOTDIR.'app/Plugins/Friends/Controllers/Friends.php')){
                              echo "<a href='".SITE_URL."Friends' title='Friends' class='btn btn-danger btn-block btn-sm'> <span class='fas fa-user' aria-hidden='true'></span> ".Language::show('uap_friends', 'Welcome');
                                  /** Check to see if there are any pending friend requests **/
                                  $new_friend_count = \Libs\CurrentUserData::getFriendRequests($currentUserData[0]->userID);
                                  if($new_friend_count >= "1"){
                                      echo " <span class='badge badge-light'>".$new_friend_count."</span>";
                                  }
                              echo " </a>";
                          }
                          /** Check to see if Private Message Plugin is installed, if it is show link **/
                          if(file_exists(ROOTDIR.'app/Plugins/Messages/Controllers/Messages.php')){
                            echo "<a href='".SITE_URL."Messages' title='Private Messages' class='btn btn-danger btn-block btn-sm'> <span class='fas fa-envelope' aria-hidden='true'></span> ".Language::show('uap_private_messages', 'Welcome');
                              /** Check to see if there are any unread messages in inbox **/
                              $new_msg_count = \Libs\CurrentUserData::getUnreadMessages($currentUserData[0]->userID);
                              if($new_msg_count >= "1"){
                                echo " <span class='badge badge-light'>".$new_msg_count."</span>";
                              }
                            echo " </a>";
                          }
                        ?>
                        <?php if($isAdmin == 'true'){ // Display Admin Panel Links if User Is Admin ?>
                          <a href='<?php echo SITE_URL; ?>AdminPanel' title='Open Admin Panel' class='btn btn-warning btn-block btn-sm'> <span class='fas fa-tachometer-alt' aria-hidden='true'></span> <?=Language::show('uap_admin_panel', 'Welcome');?></a>
                        <?php } ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                      </li>
                    </ul>
                  <li><a class='nav-link' href='<?php echo SITE_URL; ?>Logout'><?=Language::show('uap_logout', 'Welcome');?></a></li>
              <?php }?>
            </ul>

          </div>
      </div>


        <div class="container-fluid">
            <div class="row">

              <!-- BreadCrumbs -->
              <?php
              // Display Breadcrumbs if set
              if(isset($breadcrumbs)){
                echo "<div class='col-lg-12 col-md-12 col-sm-12'>";
                  echo "<ol class='breadcrumb'>";
                    echo "<li class='breadcrumb-item'><a href='".SITE_URL."'>".Language::show('uap_home', 'Welcome')."</a></li>";
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
