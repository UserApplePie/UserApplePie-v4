<?php
/**
* Admin Panel Mass E-Mail View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.0
*/

use Libs\Form;
?>
<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class="card mb-3">
		<div class="card-header h4">
			<?=$title;?>
		</div>
		<div class="card-body">
			<p><?=$welcomeMessage;?></p>
			<hr><?php echo count($get_users_massemail_allow) ?> Users Will Be Sent This Email<hr>

			<?php echo Form::open(array('method' => 'post')); ?>

      <!-- Subject -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
        <span class='input-group-addon'><i class='fa fa-fw s fa-book'></i> </span>
        <?php echo Form::input(array('type' => 'text', 'name' => 'subject', 'class' => 'form-control', 'value' => urldecode($data['subject']), 'placeholder' => 'Subject', 'maxlength' => '100')); ?>
      </div>

      <!-- Message Content -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
        <span class='input-group-addon'><i class='fa fa-fw s fa-pencil'></i> </span>
        <?php echo Form::textBox(array('type' => 'text', 'name' => 'content', 'class' => 'form-control', 'value' => $data['content'], 'placeholder' => 'Message Content', 'rows' => '6')); ?>
      </div>

        <!-- CSRF Token -->
        <input type="hidden" name="token_massemail" value="<?=$csrfToken?>" />
        <button class="btn btn-md btn-success" name="submit" type="submit">
          Send Mass Email
        </button>
      <?php echo Form::close(); ?>

		</div>
	</div>
</div>
