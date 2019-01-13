<?php
/**
* Adds Sidebar Bottom
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.1
*/

use Libs\Assets, Libs\Language, Libs\Adds;

// Get Add Data
$getAdds = Adds::getAdds('adds_sidebar_bottom');

if(!empty($getAdds)){
  echo "<div class='card bg-default mb-3'>";
    echo "<div class='card-body' align='center'>";
      echo $getAdds;
    echo "</div>";
  echo "</div>";
}
