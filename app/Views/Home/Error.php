<?php
/**
* Error View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/
?>
<div class="col-lg-12 col-md-12 col-sm-12">
  <div class="card text-center">
      <div class="card-header bg-danger h4">
          <?=$error_code?> Error!
      </div>
      <div class="card-body">
          <p class="lead"><?=$bodyText?></p>
          <?php if($data['isAdmin'] == 'true'){
              echo "<hr><b>Admin</b>: If you just added a Controller and Method to the site, check
                    the system routes to make sure it has been added. Also make sure the URL is configured as well.<br>
                    <a href='".SITE_URL."AdminPanel-SystemRoutes/' title='Admin Panel System Routes' class='btn btn-info btn-sm'>Admin Panel - System Routes</a> ";
          } ?>
      </div>
  </div>
</div>
