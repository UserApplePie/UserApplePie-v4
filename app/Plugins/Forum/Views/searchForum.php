<?php
/**
* UserApplePie v4 Forum View Plugin Search
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.1
*/

/** Forum Topics List View **/

use Core\Language,
  Libs\ErrorMessages,
  Libs\SuccessMessages,
  Libs\Form,
  Libs\TimeDiff,
  Libs\CurrentUserData,
  Libs\PageViews,
  Libs\Sweets,
  Libs\BBCode,
  Libs\Images;

  /* Hightlight Search Text Function */
  /**
   * Highlighting matching string
   * @param   string  $text           subject
   * @param   string  $words          search string
   * @return  string  highlighted text
   */
  function highlight_search_text($text, $words) {
    $keywords = implode('|',explode(' ',preg_quote($words)));
    //var_dump($keyword);
    $highlighted = preg_replace("/($keywords)/i","<mark>$0</mark>",$text);
    return $highlighted;
  }

?>
<div class='col-lg-8 col-md-8'>

	<div class='panel panel-default'>
		<div class='panel-heading'>
			<h3 class='jumbotron-heading'><?php echo $data['title'] ?></h3>
		</div>
		<div class='panel-body'>
			<p><?php echo $data['welcome_message'] ?></p>
      <div class="text-center">
        Search found <?php echo $data['results_count']; ?> matches: <?php echo $data['search_text']; ?>
      </div><br>

      <?php
      if(empty($data['error'])){
      // Display Paginator Links
      // Check to see if there is more than one page
      if($data['pageLinks'] > "1"){
        echo "<div class='panel panel-info'>";
          echo "<div class='panel-heading text-center'>";
            echo $data['pageLinks'];
          echo "</div>";
        echo "</div>";
      }
      ?>

				<?php
        // Setup form list table stuff
        echo "<div class='row'>";
          echo "<div class='col-lg-12 col-md-12 col-sm-12'>";
        foreach($data['forum_topics'] as $row2)
        {
                      echo "<hr>";
                      echo "<div class='panel panel-default'>";
                        echo "<div class='panel-heading'>";
                          echo "<h4>";
                          $title = stripslashes($row2->title);
                          $title_output = highlight_search_text($title, $data['search_text']);
                          if($row2->post_type == "reply_post"){ echo "Reply to: "; }
                          echo "<a href='".DIR."Topic/$row2->forum_post_id/' title='$title' ALT='$title'>$title_output</a>";
                          echo "</h4>";
                        echo "</div>";
                        echo "<div class='panel-body'>";
                          echo "<div class='row'>";
                            echo "<div class='col-lg-12 col-md-12 col-sm-12'>";
                            if(!empty($row2->content)){
                              $bb_content = BBCode::getHtml($row2->content);
                              $countent_output = highlight_search_text($bb_content, $data['search_text']);
                              echo $countent_output;
                            }
                            echo "</div>";
                          echo "</div>";
                        echo "</div>";
                      echo "<div class='panel-footer'>";
                        echo "<div class='text small'>";
                          $poster_username = CurrentUserData::getUserName($row2->forum_user_id);
                          echo " Posted by <a href='".DIR."Profile/$row2->forum_user_id' style='font-weight: bold'>$poster_username</a> - ";
                          //Display how long ago this was posted
                          $timestart = $row2->tstamp;  //Time of post
                          echo " " . TimeDiff::dateDiff("now", "$timestart", 1) . " ago ";
                          // Display Locked Message if Topic has been locked by admin
                          if($row2->forum_status == 2){
                            echo " <strong><font color='red'>Topic Locked</font></strong> ";
                          }
                        echo "</div>";
                      echo "</div>";
                    echo "</div>";
          } // End query

            echo "</div>";
          echo "</div>";


            // Display Paginator Links
            // Check to see if there is more than one page
            if($data['pageLinks'] > "1"){
              echo "<div class='panel panel-info'>";
                echo "<div class='panel-heading text-center'>";
                  echo $data['pageLinks'];
                echo "</div>";
              echo "</div>";
            }
          }
				?>
		</div>
	</div>
</div>
