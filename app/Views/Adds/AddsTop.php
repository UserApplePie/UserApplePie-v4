<?php
/**
* Adds Top
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Assets, Libs\Language, Libs\Adds;

// Get Add Data
$getAdds = Adds::getAdds('adds_top');

if(!empty($getAdds)){
  echo "<div class='col-lg-12 col-md-12 col-sm-12'>";
    echo "<div class='card bg-default mb-3'>";
      echo "<div class='card-body' align='center'>";
        echo $getAdds;
      echo "</div>";
    echo "</div>";
  echo "</div>";
}
