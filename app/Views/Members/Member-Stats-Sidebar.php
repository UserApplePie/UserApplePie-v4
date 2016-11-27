<?php
/**
* Members Sidebar View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.0
*/

use Libs\Language;
?>

<script>
function process()
  {
  var url="<?php echo SITE_URL; ?>Members/UN-ASC/1/" + document.getElementById("forumSearch").value;
  location.href=url;
  return false;
  }
</script>

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

<?php
    // Display Search Members Form
    if($members_page){
?>
        <div class='panel panel-default'>
          <form onSubmit="return process();" class="form" method="post">
          <div class='panel-heading' style='font-weight: bold'>
            <?=Language::show('search_members', 'Members'); ?>
          </div>
          <div class='panel-body'>
            <div class="form-group">
              <input type="forumSearch" class="form-control" id="forumSearch" placeholder="<?=Language::show('search_members', 'Members'); ?>" value="<?php if(isset($data['search'])){ echo $data['search']; } ?>">
            </div>
            <button type="submit" class="btn btn-default"><?=Language::show('search', 'Members'); ?></button>
          </div>
          </form>
        </div>
<?php } ?>

</div>
