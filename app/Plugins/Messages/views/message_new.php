<?php
/**
* UserApplePie v4 Messages View Plugin Message New
*
* UserApplePie - Messages Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.0 for UAP v.4.2.1
*/

// Message New Displays form for user to create a new message to send
// Also works with message reply

use Libs\Language,
  Libs\ErrorMessages,
  Libs\SuccessMessages,
  Core\Error,
  Libs\Form;

?>

<div class='col-lg-8 col-md-8'>

	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title'] ?>
		</div>
		<div class='card-body'>
			<p><?php echo $data['welcome_message'] ?></p>

<?php
    // Check to see if message form is disabled
    if(empty($data['hide_form'])){ $data['hide_form'] = ""; }
    if($data['hide_form'] != "true"){
        if(empty($data['to_username'])){ $data['to_username'] = ""; }
        if(empty($data['subject'])){ $data['subject'] = ""; }
        if(empty($data['content'])){ $data['content'] = ""; }
?>

			<?php echo Form::open(array('method' => 'post')); ?>

      <!-- To UserName -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
		<div class='input-group-prepend'>
			<span class='input-group-text'><i class='fas fa-user'></i> </span>
		</div>
        <?php echo Form::input(array('type' => 'text', 'name' => 'to_username', 'class' => 'form-control', 'value' => $data['to_username'], 'placeholder' => 'To Username', 'maxlength' => '100')); ?>
      </div>

      <!-- Subject -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
		<div class='input-group-prepend'>
			<span class='input-group-text'><i class='fas fa-book'></i> </span>
		</div>
        <?php echo Form::input(array('type' => 'text', 'name' => 'subject', 'class' => 'form-control', 'value' => urldecode($data['subject']), 'placeholder' => 'Subject', 'maxlength' => '100')); ?>
      </div>

      <!-- Message Content -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
		<div class='input-group-prepend'>
			<span class='input-group-text'><i class='fas fa-pencil-alt'></i> </span>
		</div>
        <?php echo Form::textBox(array('type' => 'text', 'name' => 'content', 'class' => 'form-control', 'value' => $data['content'], 'placeholder' => 'Message Content', 'rows' => '6')); ?>
      </div>

        <!-- CSRF Token -->
        <input type="hidden" name="token_messages" value="<?php echo $data['csrf_token']; ?>" />
        <button class="btn btn-md btn-success" name="submit" type="submit">
          <?php // echo Language::show('update_profile', 'Auth'); ?>
          Send Message
        </button>
      <?php echo Form::close(); ?>

<?php
  // END Check to see if message form is disabled
  }
?>

		</div>
	</div>
</div>
