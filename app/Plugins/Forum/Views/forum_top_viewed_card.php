<?php
/**
* UserApplePie v4 Forum View Plugin Sidebar
*
* UserApplePie - Forum Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.0 for UAP v.4.2.0
*/

  use Libs\Language;
  use Libs\TimeDiff;
  use Libs\CurrentUserData;
  use Libs\ForumStats;

  if(empty($data['forum_top_posts'])){ $data['forum_top_posts'] = ForumStats::forum_top_posts();}

?>

  <div class='card mb-3'>
    <div class='card-header h4' style='font-weight: bold'>
      Top Viewed Forum Posts
    </div>
    <ul class='list-group list-group-flush'>
      <?php
        foreach($data['forum_top_posts'] as $row_tp)
        {
          $f_tv = $row_tp->total_views;
          $f_p_id = $row_tp->forum_post_id;
          $f_p_id_cat = $row_tp->forum_id;
          $f_p_title = $row_tp->forum_title;
          $f_p_timestamp = $row_tp->forum_timestamp;

          $f_p_title = stripslashes($f_p_title);

          // Set the incrament of each post
          if(isset($vm_id_a_rp)){ $vm_id_a_rp++; }else{ $vm_id_a_rp = "1"; };

          $f_p_title = strlen($f_p_title) > 30 ? substr($f_p_title, 0, 34) . ".." : $f_p_title;

            echo "<ul class='list-group-item'>";
              echo "<strong>";
              echo "<a href='".DIR."Topic/$f_p_id/' title='$f_p_title' ALT='$f_p_title'>$f_p_title</a>";
              echo "</strong>";

              //Display total views
              echo "<br><font color=green> " . $f_tv . " views.</font> ";
            echo "</ul>";
        } // End query
       ?>
    </ul>
  </div>
