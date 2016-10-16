<?php

// Display a given message as requested by post from inbox or outbox

use Core\Language,
  Libs\ErrorMessages,
  Libs\SuccessMessages,
  Libs\Form;

if($data['msg_error'] == 'true'){$panelclass = "panel-danger";}else{$panelclass = "panel-default";}

?>

<div class='col-lg-8 col-md-8'>


	<div class='panel <?php echo $panelclass; ?>'>
		<div class='panel-heading'>
			<h3 class='jumbotron-heading'><?php echo $data['title'] ?></h3>
		</div>
		<div class='panel-body'>
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
                echo Form::open(array('method' => 'post', 'action' => DIR.'NewMessage'));
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
              echo "</td></tr>";
						}
            echo "</table>";
					}
				?>
		</div>
	</div>
</div>
