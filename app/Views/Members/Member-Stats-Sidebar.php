<div class="col-lg-4 col-md-4 col-sm-4">
    <div class='panel panel-default'>
        <div class='panel-heading'>
            <h3><?=Language::show('members_user_stats', 'Members'); ?></h3>
        </div>
        <ul class="list-group">
            <li class="list-group-item"><a href="<?php echo DIR; ?>Members"><?=Language::show('members_title', 'Members'); ?>: <?php echo $data['activatedAccounts']; ?></a></li>
            <li class="list-group-item"><a href="<?php echo DIR; ?>Online-Members"><?=Language::show('members_online_title', 'Members'); ?>: <?php echo $data['onlineAccounts']; ?></a></li>
        </ul>
    </div>
</div>
