<?php
/**
* UserApplePie v4 Forum View Plugin New Topic
*
* UserApplePie - Forum Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.1 for UAP v.4.2.1
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
                <span class='input-group-text'><i class='fas fa-book'></i></span>
              </div>
              <?php echo Form::input(array('type' => 'text', 'id' => 'forum_title', 'name' => 'forum_title', 'class' => 'form-control', 'value' => $data['forum_title'], 'placeholder' => 'Topic Title', 'maxlength' => '100')); ?>
            </div>

            <!-- Topic Content -->
            <div class='input-group' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
                <span class='input-group-text'>
                  <!-- BBCode Buttons -->
                  <div class='btn-group-vertical'>
                    <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[b]','[/b]');"><i class='fas fa-bold'></i></button>
                    <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[i]','[/i]');"><i class='fas fa-italic'></i></button>
                    <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[u]','[/u]');"><i class='fas fa-underline'></i></button>
                    <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[youtube]','[/youtube]');"><i class='fab fa-youtube'></i></button>
                    <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[quote]','[/quote]');"><i class='fas fa-quote-right'></i></button>
                    <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[code]','[/code]');"><i class='fas fa-code'></i></button>
                    <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[url href=]','[/url]');"><i class='fas fa-link'></i></button>
                    <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[img]','[/img]');"><i class='fas fa-image'></i></button>
                  </div>
                </span>
              </div>
              <?php echo Form::textBox(array('type' => 'text', 'id' => 'forum_content', 'name' => 'forum_content', 'class' => 'form-control', 'value' => $data['forum_content'], 'placeholder' => 'Topic Content', 'rows' => '6')); ?>
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
              <input type="hidden" id="token_forum" name="token_forum" value="<?php echo $data['csrf_token']; ?>" />
              <input type="hidden" id="forum_cat_id" name="forum_cat_id" value="<?php echo $data['forum_cat_id']; ?>" />
              <input type="hidden" id="forum_post_id" name="forum_post_id" value="<?php echo $data['forum_post_id']; ?>" />
              <button class="btn btn-md btn-success" name="submit" type="submit" id="submit">
                <?php // echo Language::show('update_profile', 'Auth'); ?>
                Submit New Topic
              </button>
            <?php echo Form::close(); ?>
            <div id="autoSave"></div>
            <div id="forum_post_id"></div>

		</div>
	</div>
</div>
