<?php use Libs\Form; ?>
<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?=$title;?>
		</div>
		<div class="panel-body">
			<p><?=$welcomeMessage;?></p>
			<hr><?php echo count($get_users_massemail_allow) ?> Users Will Be Sent This Email<hr>

			<?php echo Form::open(array('method' => 'post')); ?>

      <!-- Subject -->
      <div class='input-group' style='margin-bottom: 25px'>
        <span class='input-group-addon'><i class='glyphicon glyphicon-book'></i> </span>
        <?php echo Form::input(array('type' => 'text', 'name' => 'subject', 'class' => 'form-control', 'value' => urldecode($data['subject']), 'placeholder' => 'Subject', 'maxlength' => '100')); ?>
      </div>

      <!-- Message Content -->
      <div class='input-group' style='margin-bottom: 25px'>
        <span class='input-group-addon'><i class='glyphicon glyphicon-pencil'></i> </span>
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
