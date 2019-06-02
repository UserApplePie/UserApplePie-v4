<?php
/**
* Admin Panel Adds View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/
use Libs\Form,
    Libs\ErrorMessages,
    Libs\SuccessMessages,
    Libs\Language;

?>
<div class='col-lg-12 col-md-12 col-sm-12'>
  <div class='row'>
    <div class='col-lg-12 col-md-12 col-sm-12'>
    	<div class='card mb-3'>
    		<div class='card-header h4'>
    			<h3 class='jumbotron-heading'><?php echo $data['title'];  ?></h3>
    		</div>
    		<div class='card-body'>
    			<p><?php echo $data['welcomeMessage'] ?></p>
<?php
          if($uap_files_version > $uap_database_version){
          	echo "<div class='col-lg-12 col-md-12 col-sm-12'>";
          	echo "<div class='alert alert-danger'>";
          		echo "<b>UAP Database is out of Date. <br>";
          		echo "New Version:</b> $uap_files_version <br>";
          		echo "<b>Current Version:</b> $uap_database_version <br>";
          	echo "</div>";
          	echo "</div>";
?>
      			<?php echo Form::open(array('method' => 'post')); ?>

            <button class="btn btn-md btn-success" name="submit" type="submit">
                Start the Upgrade
            </button>
            <!-- CSRF Token and What is Being Updated -->
            <input type="hidden" name="token_upgrade" value="<?php echo $data['csrfToken']; ?>" />
            <?php
              if($uap_database_version == "4.2.1" && $uap_files_version == "4.3.0"){
                echo "<input type='hidden' name='upgrade_database' value='update421to430' />";
              }
            ?>
            <?php echo Form::close(); ?>

<?php
          }else{
            echo "Your Database is Up to Date!  No actions needed.";
          }
?>

        </div>
    	</div>
    </div>
  </div>
</div>
