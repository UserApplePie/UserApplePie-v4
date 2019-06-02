<?php
/**
* About View
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
            <?php echo Language::show('uap_about', 'Welcome'); ?>
        </div>
        <div class="card-body">
            <p><?=$bodyText?></p>
			<p><a href="../Home/" class="btn btn-primary btn-sm"><?php echo Language::show('openHome', 'Welcome'); ?></a>
            <a href="../Contact/" class="btn btn-primary btn-sm"><?php echo Language::show('openContact', 'Welcome'); ?></a></p>
        </div>
    </div>
</div>
