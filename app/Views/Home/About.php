<?php
/**
* About View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.1
*/

use Libs\Language;
?>
<div class="col-lg-8 col-md-8 col-sm-8">
	<div class="panel panel-default">
        <div class="panel-heading">
            <h1><?php echo Language::show('uap_about', 'Welcome'); ?></h1>
        </div>
        <div class="panel-body">
            <p><?=$bodyText?></p>
			<p><a href="../Home/" class="btn btn-primary btn-sm"><?php echo Language::show('openHome', 'Welcome'); ?></a>
            <a href="../Contact/" class="btn btn-primary btn-sm"><?php echo Language::show('openContact', 'Welcome'); ?></a></p>
        </div>
    </div>
</div>
