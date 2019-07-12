<?php
/**
* Admin Panel Site Links View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

/** Forum Categories Admin Panel View **/

use Libs\Form,
    Libs\ErrorMessages,
    Libs\SuccessMessages,
    Libs\Language,
    Libs\PageFunctions,
    Libs\Url,
    Libs\CurrentUserData;

?>

<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title'];  ?>
      <?php echo PageFunctions::displayPopover('Site Links', 'Site Links allow the Admin to edit links within a given area of the site and set who can view the links based on groups.', false, 'btn btn-sm btn-light'); ?>
		</div>
		<div class='card-body'>
			<p><?php echo $data['welcome_message'] ?></p>
      <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <img class='navbar-brand' src='<?php echo Url::templatePath(); ?>images/logo.gif'>
        <a class="navbar-brand" href="#"><?=SITE_TITLE?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <?php echo PageFunctions::getLinks('header_main', $currentUserData[0]->userID); ?>
          </ul>
        </div>
      </nav>
    </div>
	</div>

  <div class='card mb-3'>
    <div class='card-header h4'>
      Header Main Site Links
      <?php echo PageFunctions::displayPopover('Site Header Links', 'Site Header Links are located in the top Navbar on the site.  They can be edited here.', false, 'btn btn-sm btn-light'); ?>
    </div>
    <table class='table table-hover responsive'>
      <tr>
        <th>Link Title</th>
        <th>URL</th>
        <th class='d-none d-md-table-cell'>Alt Text</th>
        <th class='d-none d-md-table-cell'>Drop Down</th>
        <th class='d-none d-md-table-cell'>Require Plugin</th>
        <th class='d-none d-md-table-cell'>Permission</th>
        <th class='d-none d-md-table-cell'>Icon</th>
        <th></th>
      </tr>
      <?php
        if(isset($main_site_links)){
          foreach ($main_site_links as $link) {
            echo "<tr>";
            echo "<td>".$link->title."</td>";
            echo "<td>".$link->url."</td>";
            echo "<td class='d-none d-md-table-cell'>".$link->alt_text."</td>";
            echo "<td class='d-none d-md-table-cell'>";
              if($link->drop_down == "1"){ echo "Drop Down Link"; }
            echo "</td>";
            echo "<td class='d-none d-md-table-cell'>".$link->require_plugin."</td>";
            echo "<td class='d-none d-md-table-cell'>".CurrentUserData::getGroupData($link->permission)."</td>";
            echo "<td class='d-none d-md-table-cell'> <i class='$link->icon'></i> </td>";
            echo "<td align='right'>";
            /** Check to see if object is at top **/
            if($link->link_order > 1){
              echo "<a href='".DIR."AdminPanel-SiteLinks/LinkUp/$link->location/$link->id/' class='btn btn-primary btn-sm' role='button'><span class='fa fa-fw fa-caret-up' aria-hidden='true'></span></a> ";
            }
            /** Check to see if object is at bottom **/
            if($link_order_last != $link->link_order){
              echo "<a href='".DIR."AdminPanel-SiteLinks/LinkDown/$link->location/$link->id/' class='btn btn-primary btn-sm' role='button'><span class='fa fa-fw fa-caret-down' aria-hidden='true'></span></a> ";
            }
            echo "<a href='".DIR."AdminPanel-SiteLink/$link->id' class='btn btn-sm btn-success'><span class='fas fa-edit'></span></a>";
            echo "</td>";
            echo "</tr>";
          }
        }
      ?>
    </table>
    <div class='card-footer'>
      <a href='<?=DIR?>AdminPanel-SiteLink/New' class='btn btn-sm btn-success'>Add New Site Link</a>
    </div>
  </div>

</div>
