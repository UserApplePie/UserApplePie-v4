<?php
/**
* UserApplePie v4 Forum View Plugin New Topic
*
* UserApplePie - Forum Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.0 for UAP v.4.2.0
*/

/** Forum New Topic View **/

use Core\Language,
  Libs\ErrorMessages,
  Libs\SuccessMessages,
  Libs\Form,
  Libs\TimeDiff,
  Libs\CurrentUserData,
  Libs\BBCode;

?>

<div class='col-lg-8 col-md-8'>

	<div class='card mb-3'>
		<div class='card-header h4'>
			<h3 class='jumbotron-heading'><?php echo $data['title'] ?></h3>
		</div>
		<div class='card-body'>
				<?php echo $data['welcome_message']; ?>


            <?php echo Form::open(array('method' => 'post', 'files' => '')); ?>

            <!-- Topic Title -->
            <div class='input-group' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
                <span class='input-group-text'><i class='fas fa-book'></i> </span>
              </div>
              <?php echo Form::input(array('type' => 'text', 'name' => 'forum_title', 'class' => 'form-control', 'value' => $data['forum_title'], 'placeholder' => 'Topic Title', 'maxlength' => '100')); ?>
            </div>

            <!-- Topic Content -->
            <div class='input-group' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
                <span class='input-group-text'><i class='fas fa-alt-pencil'></i> </span>
              </div>
              <?php echo Form::textBox(array('type' => 'text', 'name' => 'forum_content', 'class' => 'form-control', 'value' => $data['forum_content'], 'placeholder' => 'Topic Content', 'rows' => '6')); ?>
            </div>

            <?php
              // Check to see if user is a new user.  If so then disable image uploads
              if($data['is_new_user'] != true){
             ?>
                <!-- Image Upload -->
                <div class='input-group' style='margin-bottom: 25px'>
                  <div class="input-group-prepend">
                    <span class='input-group-text'><i class='fas fa-image'></i> </span>
                  </div>
                  <?php echo Form::input(array('type' => 'file', 'name' => 'forumImage', 'id' => 'forumImage', 'class' => 'form-control', 'accept' => 'image/jpeg,image/png,image/gif')); ?>
                </div>
            <?php } ?>

              <!-- CSRF Token -->
              <input type="hidden" name="token_forum" value="<?php echo $data['csrf_token']; ?>" />
              <button class="btn btn-md btn-success" name="submit" type="submit">
                <?php // echo Language::show('update_profile', 'Auth'); ?>
                Submit New Topic
              </button>
            <?php echo Form::close(); ?>


		</div>
	</div>
</div>
