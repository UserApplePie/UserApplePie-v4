<?php
/**
* Contact View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.1
*/

use Libs\Language;
?>
<div class="col-lg-8 col-md-8 col-sm-12">
	<div class="card mb-3">
        <div class="card-header h4">
            <?php echo Language::show('uap_contact', 'Welcome'); ?>
        </div>
        <div class="card-body">
            <p><?=$bodyText?></p>
			<p><a href="../Home/" class="btn btn-primary btn-sm"><?php echo Language::show('openHome', 'Welcome'); ?></a>
            <a href="../About/" class="btn btn-primary btn-sm"><?php echo Language::show('openAbout', 'Welcome'); ?></a></p>
        </div>
    </div>
</div>
