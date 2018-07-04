<?php
/**
* UserApplePie v4 Messages View Plugin Messages Home
*
* UserApplePie - Messages Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.0 for UAP v.4.2.1
*/

// Displays a list of all message for current user
// Only displays From Subject, Status, Date Read, Date Sent

use Libs\Language,
  Libs\ErrorMessages,
  Libs\SuccessMessages,
  Libs\Form;

?>

<div class='col-lg-8 col-md-8'>

	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title'] ?>
		</div>
		<div class='card-body'>
			<p><?php echo $data['welcome_message'] ?></p>
			<table class='table table-striped table-bordered responsive'>
				<tr>
					<th colspan='2'>Private Messages</th>
				</tr>
        <tr><td>
          You have <?php echo $data['unread_messages'] ?> Unread Messages
        <br>
          You have <?php echo $data['total_messages'] ?> Messages in your Inbox
        <hr>
          <?php
            // Display Quta Info for Inbox
            echo "<b>Total Inbox Messages:</b> ${data['quota_msg_ttl']} - <b>Limit:</b> ${data['quota_msg_limit']}";
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
            if($data['quota_msg_percentage'] >= "100"){
              $full_message = "WARNING ! INBOX FULL";
            }else{
              $full_message = "${data['quota_msg_percentage']}&#37;";
            }
            echo "<div class='progress'>
                    <div class='progress-bar $set_prog_style' role='progressbar' aria-valuenow='${data['quota_msg_percentage']}' aria-valuemin='0' aria-valuemax='100' style='min-width: 2em; width:${data['quota_msg_percentage']}%'>
                      $full_message
                    </div>
                  </div>";
          echo "</div>";
          ?>
          <hr>
            <?php
              // Display Quta Info for Outbox
              echo "<b>Total Outbox Messages:</b> ${data['quota_msg_ttl_ob']} - <b>Limit:</b> ${data['quota_msg_limit_ob']}";
              // Check to see how full the inbox or outbox is and set color of progress bar
              if($data['quota_msg_percentage_ob'] >= '90'){
                $set_prog_style_ob = "progress-bar-danger";
              }else if($data['quota_msg_percentage_ob'] >= '80'){
                $set_prog_style_ob = "progress-bar-warning";
              }else if($data['quota_msg_percentage_ob'] >= '70'){
                $set_prog_style_ob = "progress-bar-info";
              }else{
                $set_prog_style_ob = "progress-bar-success";
              }
              if($data['quota_msg_percentage_ob'] >= "100"){
                $full_message_ob = "WARNING ! OUTBOX FULL";
              }else{
                $full_message_ob = "${data['quota_msg_percentage_ob']}&#37;";
              }
              echo "<div class='progress'>
                      <div class='progress-bar $set_prog_style_ob' role='progressbar' aria-valuenow='${data['quota_msg_percentage_ob']}' aria-valuemin='0' aria-valuemax='100' style='min-width: 2em; width:${data['quota_msg_percentage_ob']}%'>
                        $full_message_ob
                      </div>
                    </div>";
            echo "</div>";
            ?>
        </td></tr>
			</table>
		</div>
	</div>
</div>
