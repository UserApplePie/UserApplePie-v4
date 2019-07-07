<?php
/**
* Admin Panel Pages Permissions View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Language,
    Libs\Form,
    Libs\PageFunctions;

    /** Setup Sort By URL **/
    if(empty($data['orderby'])){
    	$ob_value = "URL-DESC";
    	$ob_icon = "";
    }
    else if($data['orderby'] == "URL-DESC"){
    	$ob_value = "URL-ASC";
    	$ob_icon = "<i class='fa fa-fw  fa-caret-down'></i>";
    }
    else if($data['orderby'] == "URL-ASC"){
    	$ob_value = "URL-DESC";
    	$ob_icon = "<i class='fa fa-fw  fa-caret-up'></i>";
    }else{
    	$ob_value = "URL-ASC";
    	$ob_icon = "";
    }
    /** Setup Sort By Controller **/
    if(empty($data['orderby'])){
    	$obc_value = "CON-DESC";
    	$obc_icon = "";
    }
    else if($data['orderby'] == "CON-DESC"){
    	$obc_value = "CON-ASC";
    	$obc_icon = "<i class='fa fa-fw  fa-caret-down'></i>";
    }
    else if($data['orderby'] == "CON-ASC"){
    	$obc_value = "CON-DESC";
    	$obc_icon = "<i class='fa fa-fw  fa-caret-up'></i>";
    }else{
    	$obc_value = "CON-ASC";
    	$obc_icon = "";
    }
    /** Setup Sort By Method **/
    if(empty($data['orderby'])){
    	$obm_value = "MET-DESC";
    	$obm_icon = "";
    }
    else if($data['orderby'] == "MET-DESC"){
    	$obm_value = "MET-ASC";
    	$obm_icon = "<i class='fa fa-fw  fa-caret-down'></i>";
    }
    else if($data['orderby'] == "MET-ASC"){
    	$obm_value = "MET-DESC";
    	$obm_icon = "<i class='fa fa-fw  fa-caret-up'></i>";
    }else{
    	$obm_value = "MET-ASC";
    	$obm_icon = "";
    }

?>


<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class='card mb-3'>
		<div class='card-header h4'>
			<?=$title?>
      <?php echo PageFunctions::displayPopover('Site Pages Permissions', 'Site Pages Permissions allows admin to set the permissions for each page on the site.  All pages listed are generated from the system routes.', false, 'btn btn-sm btn-light'); ?>
		</div>
        <div class='card-body'>
            <?=$welcomeMessage?>
        </div>
		<table class='table table-hover responsive'>
			<tr>
        <th><?php echo "<a href='".DIR."AdminPanel-PagesPermissions/$ob_value/' class='btn btn-info btn-sm'>URL Name $ob_icon</button>"; ?></th>
				<th class='d-none d-md-table-cell'><?php echo "<a href='".DIR."AdminPanel-PagesPermissions/$obc_value/' class='btn btn-info btn-sm'>Controller $obc_icon</button>"; ?></th>
        <th class='d-none d-md-table-cell'><?php echo "<a href='".DIR."AdminPanel-PagesPermissions/$obm_value/' class='btn btn-info btn-sm'>Method $obm_icon</button>"; ?></th>
        <th>Allowed User Groups</th>
        <th></th>
			</tr>
			<?php
				if(isset($data['all_pages'])){
					foreach($data['all_pages'] as $row) {
            echo "<tr>";
              echo "<td>$row->url</td>";
              echo "<td class='d-none d-md-table-cell'>$row->controller</td>";
              echo "<td class='d-none d-md-table-cell'>$row->method</td>";
              echo "<td>".PageFunctions::getPageGroupName($row->id)."</td>";
              echo "<td align='right'>";
              echo "<a href='".DIR."AdminPanel-PagePermissions/$row->id' class='btn btn-sm btn-primary'><span class='fas fa-fw fa-edit'></span></a>";
              echo "</td>";
            echo "</tr>";
					}
				}
			?>
		</table>
  </div>
</div>
