<?php
/**
* UserApplePie v4 Forum View Plugin Blocked Content
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.0
*/

/** Forum Categories Admin Panel View **/

use Libs\Form,
  Core\Success,
  Core\Language,
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
        if(isset($data['blocked_topics'])){
          echo "<div class='card border-danger mb-3'>";
            echo "<div class='card-header h4'>";
              echo "Blocked Forum Topics List";
            echo "</div>";
            echo "<table class='table table-hover responsive'><tr><th>";
              echo "Title";
            echo "</th><th class='hidden-xs'>";
              echo "Poster";
            echo "</th><th class='hidden-xs'>";
              echo "Blocker";
            echo "</th><th>";
              echo "Block Reason";
            echo "</th><th class='hidden-xs'>";
              echo "Block DateTime";
            echo "</th></tr>";
            foreach ($data['blocked_topics'] as $row) {
              echo "<tr><td>";
                echo "<a href='".DIR."Topic/$row->forum_post_id' target='_blank'>$row->forum_title</a>";
              echo "</td><td class='hidden-xs'>";
                $poster_user_name = CurrentUserData::getUserName($row->forum_user_id);
                echo "$poster_user_name";
              echo "</td><td class='hidden-xs'>";
                $hide_user_name = CurrentUserData::getUserName($row->hide_userID);
                echo "$hide_user_name";
              echo "</td><td>";
                echo "$row->hide_reason";
              echo "</td><td class='hidden-xs'>";
                echo "$row->hide_timestamp";
              echo "</td></tr>";
            }
            echo "</table>";
          echo "</div>";
        }

        // Display List of blocked topics
        if(isset($data['blocked_replies'])){
          echo "<div class='card border-danger mb-3'>";
            echo "<div class='card-header h4'>";
              echo "Blocked Forum Replies List";
            echo "</div>";
            echo "<table class='table table-hover responsive'><tr><th>";
              echo "Reply ID";
            echo "</th><th class='hidden-xs'>";
              echo "Poster";
            echo "</th><th class='hidden-xs'>";
              echo "Blocker";
            echo "</th><th class='hidden-xs'>";
              echo "Block DateTime";
            echo "</th></tr>";
            foreach ($data['blocked_replies'] as $row) {
              echo "<tr><td>";
                echo "<a href='".DIR."Topic/$row->fpr_post_id#topicreply$row->id' target='_blank'>$row->id</a>";
              echo "</td><td class='hidden-xs'>";
                $poster_user_name = CurrentUserData::getUserName($row->fpr_user_id);
                echo "$poster_user_name";
              echo "</td><td class='hidden-xs'>";
                $hide_user_name = CurrentUserData::getUserName($row->hide_userID);
                echo "$hide_user_name";
              echo "</td><td class='hidden-xs'>";
                echo "$row->hide_timestamp";
              echo "</td></tr>";
            }
            echo "</table>";
          echo "</div>";
        }
       ?>

</div>
