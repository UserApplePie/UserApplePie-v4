<?php use App\System\Libraries\Assets; ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title><?=SITE_TITLE?><?=(isset($pageTitle)) ? " - ".$pageTitle : "" ?></title>
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
    <body>&nbsp;
        <div class="navbar navbar-default navbar-fixed-top">
          <div class="container">
            <div class="navbar-header">
                <img class='navbar-brand' src='<?=SITE_URL?>Templates/Default/Assets/images/logo.gif' />
                <a href="../" class="navbar-brand"><?=SITE_TITLE?></a>
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse" id="navbar-main">
              <ul class="nav navbar-nav">
                <li>
                  <a href="../About/">About</a>
                </li>
                <li>
                  <a href="../Contact/">Contact</a>
                </li>
              </ul>

              <ul class="nav navbar-nav navbar-right">
                <li><a href="../Login/">Login</a></li>
                <li><a href="../Register/">Register</a></li>
              </ul>

            </div>
          </div>
        </div>


        <div class="container">
            <div class="row">
