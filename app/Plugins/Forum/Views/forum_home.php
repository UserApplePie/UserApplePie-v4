<?php
/**  
* UserApplePie v3 Forum Plugin
* @author David (DaVaR) Sargent
* @email davar@thedavar.net
* @website http://www.userapplepie.com
* @version 1.0.0
* @release_date 04/27/2016
**/

/** Forum Home Page View **/

use Core\Language,
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
				<?php
        foreach($data['forum_categories'] as $row)
      	{
      		$f_title = $row->forum_title;
      		$f_id = $row->forum_id;
      		$f_order_title = $row->forum_order_title;

      		echo "<div class='panel panel-default'>";
      			echo "<div class='panel-heading' style='font-weight: bold'>";

              // Title Output
              echo "$f_title";

      			echo "</div>";
      			echo "<ul class='list-group'>";
      				foreach($data['forum_titles'] as $row2)
      				{
                if($f_title == $row2->forum_title){
        					echo "<ul class='list-group-item'>";
        						$f_cat = $row2->forum_cat;
        						$f_des = $row2->forum_des;
        						$f_id2 = $row2->forum_id;
        						$cat_order_id = $row2->forum_order_cat;

        						$f_des = stripslashes($f_des);
        						$f_cat = stripslashes($f_cat);

        						echo "<div class='media'>";
        							echo "<div class='media-body'>";
        								// Display Link To View Topics for Category.
                        echo "<h4><a href='".DIR."Topics/$f_id2/' title='$f_cat' ALT='$f_cat'>$f_cat</a></h4>";
        							echo "</div>";


        								// Displays when on mobile device
        								echo "<button href='#Bar$f_id2' class='btn btn-default btn-sm visible-xs' data-toggle='collapse' style='position: absolute; top: 3px; right: 3px'>";
        									echo "<span class='glyphicon glyphicon-plus' aria-hidden='true'></span>";
        								echo "</button>";

        								echo "<div id='Bar$f_id2' class='collapse hidden-sm hidden-md hidden-lg'>";
        								echo "<div style='text-align: center'>";
        									// Display total number of topics for this category
                          echo "<div class='btn btn-info btn-xs' style='margin-top: 5px'>";
                          echo "Topics <span class='badge'>$row2->total_topics_display</span>";
                          echo "</div>";
        									// Display total number of topic replys for this category
                          echo "<div class='btn btn-info btn-xs' style='margin-top: 3px'>";
                          echo "Replies <span class='badge'>$row2->total_topic_replys_display</span>";
                          echo "</div>";
        								echo "</div>";
        								echo "</div>";

        								// Displays when not on mobile device
        								echo "<div class='media-right hidden-xs' style='text-align: right'>";
                        // Display total number of topics for this category
                        echo "<div class='btn btn-info btn-xs' style='margin-top: 5px'>";
                        echo "Topics <span class='badge'>$row2->total_topics_display</span>";
                        echo "</div>";
                        echo "<br>";
                        // Display total number of topic replys for this category
                        echo "<div class='btn btn-info btn-xs' style='margin-top: 3px'>";
                        echo "Replies <span class='badge'>$row2->total_topic_replys_display</span>";
                        echo "</div>";
        								echo "</div>";

        						echo "</div>";
        					echo "</ul>";
                }
      				}
      			echo "</ul>";
      		echo "</div>";
      	}
				?>
		</div>
	</div>
</div>
