<?php
/**
* Admin Panel Groups View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.1
*/

use Libs\Language,
    Libs\Form;

$orderby = $data['orderby'];

?>


  <div class='col-lg-12 col-md-12 col-sm-12'>
    <div class='card mb-3 mw-100'>
      <div class='card-header h4'>
        Create New Group
      </div>
      <div class='card-body'>
        <?php echo Form::open(array('method' => 'post')); ?>
        <?php echo Form::input(array('type' => 'text', 'name' => 'ag_groupName', 'class' => 'form-control', 'placeholder' => 'New Group Name', 'maxlength' => '150')); ?>
        <input type='hidden' name='token_groups' value='<?php echo $data['csrfToken'] ?>'>
        <input type='hidden' name='create_group' value='true' />
      </div>
      <div class='card-footer text-muted'>
        <button name='submit' type='submit' class="btn btn-sm btn-success">Create New Group</button>
        <?php echo Form::close(); ?>
      </div>
    </div>
  </div>


  <div class='col-lg-12 col-md-12 col-sm-12'>
  	<div class='card mb-3'>
  		<div class='card-header h4'>
  			All Site Groups
  		</div>
  		<table class='table table-hover responsive'>
  			<tr>
  				<th>
            <?php
              if(empty($data['orderby'])){
                $ob_value = "ID-DESC";
                $ob_icon = "";
              }
              else if($data['orderby'] == "ID-DESC"){
                $ob_value = "ID-ASC";
                $ob_icon = "<i class='fa fa-fw  fa-caret-down'></i>";
              }
              else if($data['orderby'] == "ID-ASC"){
                $ob_value = "ID-DESC";
                $ob_icon = "<i class='fa fa-fw  fa-caret-up'></i>";
              }else{
                  $ob_value = "ID-DESC";
                  $ob_icon = "";
              }
                // Setup the order by id button
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='orderby' value='$ob_value'>";
                echo "<button type='submit' class='btn btn-info btn-sm'>ID $ob_icon</button>";
                echo "</form>";
            ?>
          </th>
  				<th>
            <?php
              if(empty($data['orderby'])){
                $obu_value = "UN-DESC";
                $obu_icon = "";
              }
              else if($data['orderby'] == "UN-DESC"){
                $obu_value = "UN-ASC";
                $obu_icon = "<i class='fa fa-fw  fa-caret-down'></i>";
              }
              else if($data['orderby'] == "UN-ASC"){
                $obu_value = "UN-DESC";
                $obu_icon = "<i class='fa fa-fw  fa-caret-up'></i>";
              }else{
                  $obu_value = "UN-DESC";
                  $obu_icon = "";
              }
                // Setup the order by id button
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='orderby' value='$obu_value'>";
                echo "<button type='submit' class='btn btn-info btn-sm'>Group Name $obu_icon</button>";
                echo "</form>";
            ?>
          </th>
          <th>Display</th>
          <th></th>
  			</tr>
  			<?php
  				if(isset($data['groups_list'])){
  					foreach($data['groups_list'] as $row) {
  						echo "<tr>";
              echo "<td>$row->groupID</td>";
  						/** Check to make sure group has a name/title **/
  						$group_name = (!empty($row->groupName) ? $row->groupName : "UnNamed Group");
  						echo "<td><button type='button' class='btn btn-secondary btn-sm' data-toggle='modal' data-target='#myModal-$row->groupID'>$group_name</button></td>";
              echo "<td><font color='$row->groupFontColor' style='font-weight: $row->groupFontWeight'>$row->groupName</font></td>";
              echo "<td align='right'>";
              echo "<a href='".DIR."AdminPanel-Group/$row->groupID' class='btn btn-sm btn-primary'><span class='fa fa-fw  fa-pencil'></span></a>";
              echo "</td>";
  						echo "</tr>";
              echo "
                <!-- Modal -->
                <div class='modal fade' id='myModal-$row->groupID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                  <div class='modal-dialog' role='document'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                        <h4 class='modal-title' id='myModalLabel'><span class='fa fa-fw  fa-tower'></span> ".$row->groupName." Information</h4>
                      </div>
                      <div class='modal-body'>
                        <b style='border-bottom: 1px solid #ccc'>Group Name Display:</b><br>
                        <font color='$row->groupFontColor' style='font-weight: $row->groupFontWeight'>$row->groupName</font>
                        <br><br>
                        <b style='border-bottom: 1px solid #ccc'>Group Description:</b><br>
                        $row->groupDescription
                        <br><br>
                        <b style='border-bottom: 1px solid #ccc'>Total Group Members:</b><br>
                        ".\Libs\CurrentUserData::getGroupMembersCount($row->groupID)."
                      </div>
                      <div class='modal-footer'>
                        <a class='btn btn-primary btn-sm' href='".DIR."AdminPanel-Group/$row->groupID'>Edit Group Info</a>
                        <button type='button' class='btn btn-secondary btn-sm' data-dismiss='modal'>Close</button>
                      </div>
                    </div>
                  </div>
                </div>
              ";
  					}
  				}
  			?>
  		</table>
    </div>
  </div>
