<?php
/**
* Home View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Language;
?>

<div class="col-lg-8 col-md-8 col-sm-12">
	<div class="card mb-3">
        <div class="card-header h4">
            <?php echo Language::show('uap_home', 'Welcome'); ?>
        </div>
        <div class="card-body">
						<center><img src='<?=SITE_URL?>/Templates/<?=DEFAULT_TEMPLATE?>/Assets/images/uap3logolg.gif' class='img-fluid' /></center>
            <?=$bodyText?><br>
            <a href="../About/" class="btn btn-primary btn-sm"><?php echo Language::show('openAbout', 'Welcome'); ?></a>
            <a href="../Contact/" class="btn btn-primary btn-sm"><?php echo Language::show('openContact', 'Welcome'); ?></a>
        </div>
    </div>
</div>
<br><br>
