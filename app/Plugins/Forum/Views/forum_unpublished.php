<?php
/**
* UserApplePie v4 Forum View Plugin Blocked Content
*
* UserApplePie - Forum Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.2 for UAP v.4.3.0
*/

/** Forum Categories Admin Panel View **/

use Libs\Form,
  Core\Success,
  Core\Language,
  Libs\TimeDiff,
  Libs\BBCode,
  Libs\CurrentUserData;

?>

<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class='card mb-3'>
		<div class='card-header h4'>
			<h3 class='jumbotron-heading'><?php echo $data['title'];  ?></h3>
		</div>
		<div class='card-body'>
			<p><?php echo $data['welcome_message'] ?></p>
    </div>
	</div>
      <?php
        // Display List of blocked topics
        if(isset($data['unpublished_topics'])){
          echo "<div class='card border-danger mb-3'>";
            echo "<div class='card-header h4'>";
              echo "Blocked Forum Topics List";
            echo "</div>";
            echo "<table class='table table-hover responsive'><tr><th>";
              echo "Title";
            echo "</th><th class='hidden-xs'>";
              echo "Poster";
            echo "</th><th class='hidden-xs'>";
              echo "Created";
            echo "</th></tr>";
            foreach ($data['unpublished_topics'] as $row) {
              echo "<tr><td>";
                echo "<a href='#FPModal$row->forum_post_id' class='btn btn-sm btn-danger trigger-btn' data-toggle='modal'>View Topic</a>";
              echo "</td><td class='hidden-xs'>";
                $poster_user_name = CurrentUserData::getUserName($row->forum_user_id);
                echo "$poster_user_name";
              echo "</td><td class='hidden-xs'>";
                echo "<font color=green> " . TimeDiff::dateDiff("now", "$row->forum_timestamp", 1) . " ago</font> ";
                echo "<div class='pull-right'>";
                /** Setup Delete Button Form **/
                $button_display_publish = Form::open(array('method' => 'post', 'style' => 'display:inline'));
                  $button_display_publish .= " <input type='hidden' name='action' value='publish_topic' /> ";
                  $button_display_publish .= " <input type='hidden' name='forum_post_id' value='$row->forum_post_id' /> ";
                  $button_display_publish .= " <input type='hidden' name='token_unpublished' value='$csrfToken' /> ";
                  $button_display_publish .= " <button type='submit' class='btn btn-warning' value='submit' name='submit'>Publish</button> ";
                $button_display_publish .= Form::close();;
                echo "<a href='#PublishModalFP$row->forum_post_id' class='btn btn-sm btn-warning trigger-btn' data-toggle='modal'>Publish</a>";
                  /** Setup Delete Button Form **/
                  $button_display_delete = Form::open(array('method' => 'post', 'style' => 'display:inline'));
                    $button_display_delete .= " <input type='hidden' name='action' value='delete_topic' /> ";
                    $button_display_delete .= " <input type='hidden' name='forum_post_id' value='$row->forum_post_id' /> ";
                    $button_display_delete .= " <input type='hidden' name='token_unpublished' value='$csrfToken' /> ";
                    $button_display_delete .= " <button type='submit' class='btn btn-danger' value='submit' name='submit'>Delete</button> ";
                  $button_display_delete .= Form::close();;
                  echo "<a href='#DeleteModalFP$row->forum_post_id' class='btn btn-sm btn-danger trigger-btn' data-toggle='modal'>Delete</a>";
                echo "</div>";
              echo "</td></tr>";

              echo "
                <div class='modal fade' id='FPModal$row->forum_post_id' tabindex='-1' role='dialog' aria-labelledby='FPLabel' aria-hidden='true'>
                  <div class='modal-dialog' role='document'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h5 class='modal-title' id='FPLabel'>Forum Post Content</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                          <span aria-hidden='true'>&times;</span>
                        </button>
                      </div>
                      <div class='modal-body'>
                        <h4>$row->forum_title</h4>
                        <hr>
                        ".BBCode::getHtml($row->forum_content)."
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                      </div>
                    </div>
                  </div>
                </div>
              ";

              echo "
                <div class='modal fade' id='DeleteModalFP$row->forum_post_id' tabindex='-1' role='dialog' aria-labelledby='DeleteLabel' aria-hidden='true'>
                  <div class='modal-dialog' role='document'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h5 class='modal-title' id='DeleteLabel'>Delete Forum Post Reply</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                          <span aria-hidden='true'>&times;</span>
                        </button>
                      </div>
                      <div class='modal-body'>
                        Do you want to delete this forum post reply?
                        <hr>
                        forum_post_id : $row->forum_post_id
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                        $button_display_delete
                      </div>
                    </div>
                  </div>
                </div>
              ";

              echo "
                <div class='modal fade' id='PublishModalFP$row->forum_post_id' tabindex='-1' role='dialog' aria-labelledby='PublishLabel' aria-hidden='true'>
                  <div class='modal-dialog' role='document'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h5 class='modal-title' id='PublishLabel'>Publish Forum Post Topic</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                          <span aria-hidden='true'>&times;</span>
                        </button>
                      </div>
                      <div class='modal-body'>
                        Do you want to publish this forum post reply?
                        <hr>
                        forum_post_id : $row->forum_post_id
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                        $button_display_publish
                      </div>
                    </div>
                  </div>
                </div>
              ";
            }
            echo "</table>";
          echo "</div>";
        }

        // Display List of blocked topics
        if(isset($data['unpublished_replies'])){
          echo "<div class='card border-danger mb-3'>";
            echo "<div class='card-header h4'>";
              echo "Blocked Forum Replies List";
            echo "</div>";
            echo "<table class='table table-hover responsive'><tr><th>";
              echo "Reply ID";
            echo "</th><th class='hidden-xs'>";
              echo "Poster";
            echo "</th><th class='hidden-xs'>";
              echo "Created";
            echo "</th></tr>";
            foreach ($data['unpublished_replies'] as $row) {
              echo "<tr><td>";
                echo "<a href='#FPRModal$row->id' class='btn btn-sm btn-danger trigger-btn' data-toggle='modal'>View Reply</a>";
              echo "</td><td class='hidden-xs'>";
                $poster_user_name = CurrentUserData::getUserName($row->fpr_user_id);
                echo "$poster_user_name";
              echo "</td><td class='hidden-xs'>";
                echo "<font color=green> " . TimeDiff::dateDiff("now", "$row->fpr_timestamp", 1) . " ago</font> ";
                echo "<div class='pull-right'>";
                  /** Setup Delete Button Form **/
                  $button_display_publish = Form::open(array('method' => 'post', 'style' => 'display:inline'));
                    $button_display_publish .= " <input type='hidden' name='action' value='publish_reply' /> ";
                    $button_display_publish .= " <input type='hidden' name='id' value='$row->id' /> ";
                    $button_display_publish .= " <input type='hidden' name='token_unpublished' value='$csrfToken' /> ";
                    $button_display_publish .= " <button type='submit' class='btn btn-warning' value='submit' name='submit'>Publish</button> ";
                  $button_display_publish .= Form::close();;
                  echo "<a href='#PublishModalFPR$row->id' class='btn btn-sm btn-warning trigger-btn' data-toggle='modal'>Publish</a>";
                  /** Setup Delete Button Form **/
                  $button_display_delete = Form::open(array('method' => 'post', 'style' => 'display:inline'));
                    $button_display_delete .= " <input type='hidden' name='action' value='delete_reply' /> ";
                    $button_display_delete .= " <input type='hidden' name='id' value='$row->id' /> ";
                    $button_display_delete .= " <input type='hidden' name='token_unpublished' value='$csrfToken' /> ";
                    $button_display_delete .= " <button type='submit' class='btn btn-danger' value='submit' name='submit'>Delete</button> ";
                  $button_display_delete .= Form::close();;
                  echo "<a href='#DeleteModalFPR$row->id' class='btn btn-sm btn-danger trigger-btn' data-toggle='modal'>Delete</a>";
                echo "</div>";
              echo "</td></tr>";

              echo "
                <div class='modal fade' id='FPRModal$row->id' tabindex='-1' role='dialog' aria-labelledby='FPRLabel' aria-hidden='true'>
                  <div class='modal-dialog' role='document'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h5 class='modal-title' id='FPRLabel'>Forum Post Reply Content</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                          <span aria-hidden='true'>&times;</span>
                        </button>
                      </div>
                      <div class='modal-body'>
                        ".BBCode::getHtml($row->fpr_content)."
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                      </div>
                    </div>
                  </div>
                </div>
              ";

              echo "
                <div class='modal fade' id='DeleteModalFPR$row->id' tabindex='-1' role='dialog' aria-labelledby='DeleteLabel' aria-hidden='true'>
                  <div class='modal-dialog' role='document'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h5 class='modal-title' id='DeleteLabel'>Delete Forum Post Reply</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                          <span aria-hidden='true'>&times;</span>
                        </button>
                      </div>
                      <div class='modal-body'>
                        Do you want to delete this forum post reply?
                        <hr>
                        id : $row->id
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                        $button_display_delete
                      </div>
                    </div>
                  </div>
                </div>
              ";

              echo "
                <div class='modal fade' id='PublishModalFPR$row->id' tabindex='-1' role='dialog' aria-labelledby='PublishLabel' aria-hidden='true'>
                  <div class='modal-dialog' role='document'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h5 class='modal-title' id='PublishLabel'>Publish Forum Post Reply</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                          <span aria-hidden='true'>&times;</span>
                        </button>
                      </div>
                      <div class='modal-body'>
                        Do you want to publish this forum post reply?
                        <hr>
                        id : $row->id
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                        $button_display_publish
                      </div>
                    </div>
                  </div>
                </div>
              ";
            }
            echo "</table>";
          echo "</div>";
        }
       ?>

</div>
