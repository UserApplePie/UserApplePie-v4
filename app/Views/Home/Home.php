<?php use Libs\Language; ?>
<div class="col-lg-8 col-md-8 col-sm-8">
	<div class="panel panel-default">
        <div class="panel-heading">
            <h1><?php echo Language::show('uap_home', 'Welcome'); ?></h1>
        </div>
        <div class="panel-body">
			<center><img src='<?=SITE_URL?>/Templates/<?=DEFAULT_TEMPLATE?>/Assets/images/uap3logolg.gif' class='img-responsive' /></center>
            <p><?=$bodyText?></p>
            <p><a href="../About/" class="btn btn-primary btn-sm"><?php echo Language::show('openAbout', 'Welcome'); ?></a>
            <a href="../Contact/" class="btn btn-primary btn-sm"><?php echo Language::show('openContact', 'Welcome'); ?></a></p>
        </div>
    </div>
</div>
