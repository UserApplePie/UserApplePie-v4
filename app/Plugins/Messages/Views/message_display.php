<?php
/**
* UserApplePie v4 Messages View Plugin Message Display
*
* UserApplePie - Messages Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.1 for UAP v.4.3.0
*/


// Display a given message as requested by post from inbox or outbox

use Libs\Language,
  Libs\ErrorMessages,
  Libs\SuccessMessages,
  Libs\Form;

if(empty($data['msg_error'])){ $data['msg_error'] = ""; }
if($data['msg_error'] == 'true'){$panelclass = "card-danger";}else{$panelclass = "card-secondary";}

?>

<div class='col-lg-9 col-md-8'>


	<div class='card <?php echo $panelclass; ?>'>
		<div class='card-header h4'>
			<?php echo $data['title'] ?>
		</div>
		<div class='card-body'>
			<p><?php echo $data['welcome_message'] ?></p>
				<?php
					if(isset($data['message'])){
            echo "<table class='table table-bordered table-striped responsive'>";
						foreach($data['message'] as $row) {
							echo "<tr>";
              echo "<td>$row->subject</td>";
              echo "</tr><tr><td>";
              echo "<b>Date Sent:</b> ".date("F d, Y - g:i A",strtotime($row->date_sent))."<br>";
              // Check to see if message is marked as read yet
              if(isset($row->date_read)){
                echo "<b>Date Read:</b> ".date("F d, Y - g:i A",strtotime($row->date_read))."<br>";
              }
							echo "<b>From:</b> <a href='".DIR."Profile/$row->username'>$row->username</a>";
              echo "</td></tr><tr>";
							echo "<td>$row->content</td>";
							echo "</tr><tr><td>";
                echo Form::open(array('method' => 'post', 'action' => SITE_URL.'NewMessage', 'style' => 'display:inline'));
                  echo "<input type='hidden' name='token_messages' value='${data['csrf_token']}' />";
                  echo "<input type='hidden' name='reply' value='true' />";
                  echo "<input type='hidden' name='to_username' value='$row->username' />";
                  echo "<input type='hidden' name='subject' value=\"$row->subject\" />";
                  echo "<input type='hidden' name='content' value=\"$row->content\" />";
                  echo "<input type='hidden' name='date_sent' value='".date("F d, Y - g:i A",strtotime($row->date_sent))."' />";
                  echo "<button class='btn btn-md btn-success' name='submit' type='submit'>";
                    echo "Reply";
                  echo "</button>";
                echo Form::close();
                echo "<a href='#DeleteModal' class='btn btn-sm btn-danger trigger-btn float-right' data-toggle='modal'>Delete Message</a>";
              echo "</td></tr>";
						}
            echo "</table>";
					}
				?>
		</div>
	</div>
</div>

  <div class='modal fade' id='DeleteModal' tabindex='-1' role='dialog' aria-labelledby='DeleteLabel' aria-hidden='true'>
    <div class='modal-dialog' role='document'>
      <div class='modal-content'>
        <div class='modal-header'>
          <h5 class='modal-title' id='DeleteLabel'>Message Delete</h5>
          <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
        <div class='modal-body'>
          Do you want to delete this message?
        </div>
        <div class='modal-footer'>
          <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
          <?php
            echo Form::open(array('method' => 'post', 'action' => '', 'style' => 'display:inline'));
              echo "<input type='hidden' name='token_messages' value='${data['csrf_token']}' />";
              echo "<input type='hidden' name='delete' value='true' />";
              echo "<input type='hidden' name='message_id' value='$row->id' />";
              echo "<button class='btn btn-md btn-danger' name='submit' type='submit'>";
                echo "Delete";
              echo "</button>";
            echo Form::close();
          ?>
        </div>
      </div>
    </div>
  </div>
