<?php

// Displays a list of all message for current user
// Only displays From Subject, Status, Date Read, Date Sent

use Libs\Language,
  Libs\ErrorMessages,
  Libs\SuccessMessages,
  Libs\Form;

?>

<div class='col-lg-8 col-md-8'>


	<div class='panel panel-default'>
		<div class='panel-heading'>
			<h3 class='jumbotron-heading'><?php echo $data['title'] ?></h3>
		</div>
		<div class='panel-body'>
			<p><?php echo $data['welcome_message'] ?></p>
			<table class='table table-striped table-hover table-bordered responsive'>
				<tr>
					<th colspan='2'>Message</th>
          <th><div align='center'><INPUT type='checkbox' onchange='checkAll(this)' name='msg_id[]' /></div></th>
				</tr>
				<?php
					if(!empty($data['messages'])){
            $this_url = DIR."Messages${data['what_box']}";
            echo Form::open(array('method' => 'post', 'action' => $this_url));
						foreach($data['messages'] as $row) {
							echo "<tr>";
              echo "<td align='center' valign='middle'>";
                //Check to see if message is new
                if($row->date_read == NULL){
                  // Unread
                  echo "<span class='glyphicon glyphicon-star' aria-hidden='true' style='font-size:25px; color:#419641'></span>";
                }else{
                  // Read
                  echo "<span class='glyphicon glyphicon-star-empty' aria-hidden='true' style='font-size:25px; color:#CCC'></span>";
                }
              echo "</td>";
              echo "<td><a href='".DIR."ViewMessage/$row->id'><b>Subject:</b> $row->subject</a><br>";
							echo $data['tofrom'];
              echo " <a href='".DIR."Profile/$row->username'>$row->username</a>";
							echo " &raquo; ";
							echo  date("F d, Y - g:i A",strtotime($row->date_sent));
              echo "</td>";
              echo "<td>";
              echo Form::input(array('type' => 'checkbox', 'name' => 'msg_id[]', 'class' => 'form-control', 'value' => $row->id));
              echo "</td>";
							echo "</tr>";
						}
            echo "<input type='hidden' name='token_messages' value='".$data['csrf_token']."' />";
            echo "</tr><td colspan='3'>";
            echo "<div class='col-lg-7 col-md-7 col-sm-7 pull-left' style='font-size:12px;margin-bottom:0px;'>";
              // Display Quta Info
              echo "<b>Total ${data['what_box']} Messages:</b> ${data['quota_msg_ttl']} - <b>Limit:</b> ${data['quota_msg_limit']}";
              // Check to see how full the inbox or outbox is and set color of progress bar
              if($data['quota_msg_percentage'] >= '90'){
                $set_prog_style = "progress-bar-danger";
              }else if($data['quota_msg_percentage'] >= '80'){
                $set_prog_style = "progress-bar-warning";
              }else if($data['quota_msg_percentage'] >= '70'){
                $set_prog_style = "progress-bar-info";
              }else{
                $set_prog_style = "progress-bar-success";
              }
              echo "<div class='progress'>
                      <div class='progress-bar $set_prog_style' role='progressbar' aria-valuenow='${data['quota_msg_percentage']}' aria-valuemin='0' aria-valuemax='100' style='min-width: 2em; width:${data['quota_msg_percentage']}%'>
                        ${data['quota_msg_percentage']}&#37;
                      </div>
                    </div>";
            echo "</div>";
            echo "<div class='col-lg-5 col-md-5 col-sm-5 input-group pull-right' style='margin-bottom:0px;'>";
              echo "<span class='input-group-addon'>Actions</span>";
              echo "<select class='form-control' id='actions' name='actions'>";
                echo "<option>Select Action</option>";
                // Check to see if using inbox - oubox mark as read is disabled
                if($data['inbox'] == "true"){
                  echo "<option value='mark_read'>Make as Read</option>";
                }
                echo "<option value='delete'>Delete</option>";
              echo "</select>";
              echo "<span class='input-group-btn'><button class='btn btn-success' name='submit' type='submit'>GO</button></span>";
            echo "</div>";
            echo "</td></tr>";
            // Check to see if there is more than one page
            if($data['pageLinks'] > "1"){
              echo "<tr><td colspan='3' align='center'>";
              echo $data['pageLinks'];
              echo "</td></tr>";
            }
            echo Form::close();
					}else{
            echo "<tr><td>No Messages to Display</td></tr>";
          }
				?>
			</table>
		</div>
	</div>
</div>
