<?php
/**
* Admin Panel User View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.1
*/

use Libs\Form,
    Libs\ErrorMessages,
    Libs\SuccessMessages,
    Libs\Language;

?>
<div class='col-lg-12 col-md-12 col-sm-12'>
  <div class='row'>
    <div class='col-lg-8 col-md-8 col-sm-8'>
    	<div class='card mb-3'>
    		<div class='card-header h4'>
    			<h3 class='jumbotron-heading'><?php echo $data['title']." - ".$user_data[0]->username;  ?></h3>
    		</div>
    		<div class='card-body'>
    			<p><?php echo $data['welcomeMessage'] ?></p>

    			<?php echo Form::open(array('method' => 'post')); ?>

    			<!-- User Name -->
    			<div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
    				  <span class='input-group-text'><i class='fa fa-fw  fa-user'></i> UserName</span>
            </div>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'au_username', 'class' => 'form-control', 'value' => $user_data[0]->username, 'placeholder' => 'UserName', 'maxlength' => '100')); ?>
    			</div>

    				<!-- First Name -->
    				<div class='input-group mb-3' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
      				  <span class='input-group-text'><i class='fa fa-fw  fa-user'></i> First Name</span>
              </div>
    					<?php echo Form::input(array('type' => 'text', 'name' => 'au_firstName', 'class' => 'form-control', 'value' => $user_data[0]->firstName, 'placeholder' => 'First Name', 'maxlength' => '100')); ?>
    				</div>

            <!-- First Name -->
    				<div class='input-group mb-3' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
      				  <span class='input-group-text'><i class='fa fa-fw  fa-user'></i> Last Name</span>
              </div>
    					<?php echo Form::input(array('type' => 'text', 'name' => 'au_lastName', 'class' => 'form-control', 'value' => $user_data[0]->lastName, 'placeholder' => 'Last Name', 'maxlength' => '100')); ?>
    				</div>

    				<!-- Email -->
    				<div class='input-group mb-3' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
      				  <span class='input-group-text'><i class='fa fa-fw  fa-envelope'></i> Email</span>
              </div>
    					<?php echo Form::input(array('type' => 'text', 'name' => 'au_email', 'class' => 'form-control', 'value' => $user_data[0]->email, 'placeholder' => 'Email Address', 'maxlength' => '100')); ?>
    				</div>

    				<!-- Gender -->
    				<div class='input-group mb-3' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
      				  <span class='input-group-text'><i class='fa fa-fw  fa-user'></i> Gender</span>
              </div>
    					<select class='form-control' id='gender' name='au_gender'>
    				    <option value='Male' <?php if($user_data[0]->gender == "Male"){echo "SELECTED";}?> >Male</option>
    				    <option value='Female' <?php if($user_data[0]->gender == "Female"){echo "SELECTED";}?> >Female</option>
    				  </select>
    				</div>

    				<!-- Website -->
    				<div class='input-group mb-3' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
      				  <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Website</span>
              </div>
    					<?php echo Form::input(array('type' => 'text', 'name' => 'au_website', 'class' => 'form-control', 'value' => $user_data[0]->website, 'placeholder' => 'Website URL', 'maxlength' => '100')); ?>
    				</div>

    				<!-- Profile Image -->
    				<div class='input-group mb-3' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
      				  <span class='input-group-text'><i class='fa fa-fw  fa-image'></i> Profile Image URL</span>
              </div>
    					<?php echo Form::input(array('type' => 'text', 'name' => 'au_userImage', 'class' => 'form-control', 'value' => $user_data[0]->userImage, 'placeholder' => 'Profile Image URL', 'maxlength' => '255')); ?>
    				</div>

    				<!-- About Me -->
    				<div class='input-group mb-3' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
      				  <span class='input-group-text'><i class='fa fa-fw  fa-book'></i> About Me</span>
              </div>
    					<?php echo Form::textBox(array('type' => 'text', 'name' => 'au_aboutme', 'class' => 'form-control', 'value' => str_replace("<br />", "", $user_data[0]->aboutme), 'placeholder' => 'About Me', 'rows' => '6')); ?>
    				</div>

            <!-- About Me -->
    				<div class='input-group mb-3' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
      				  <span class='input-group-text'><i class='fa fa-fw  fa-book'></i> About Me</span>
              </div>
    					<?php echo Form::textBox(array('type' => 'text', 'name' => 'au_signature', 'class' => 'form-control', 'value' => str_replace("<br />", "", $user_data[0]->signature), 'placeholder' => 'Forum Signature', 'rows' => '6')); ?>
    				</div>

    				<!-- CSRF Token -->
    				<input type="hidden" name="token_user" value="<?php echo $data['csrfToken']; ?>" />
    				<input type="hidden" name="au_id" value="<?php echo $user_data[0]->userID; ?>" />
            <input type="hidden" name="update_profile" value="true" />
    				<button class="btn btn-md btn-success" name="submit" type="submit">
    					<?php // echo Language::show('update_profile', 'Auth'); ?>
    					Update Profile
    				</button>
    			<?php echo Form::close(); ?>

    		</div>
    	</div>
    </div>

    <div class='col-lg-4 col-md-4 col-sm-4'>
      <div class='card mb-3'>
        <div class='card-header h4'>
          <h3 class='jumbotron-heading'>User Stats</h3>
        </div>
        <div class='card-body'>
          <b>Last Login</b>: <?php if($user_data[0]->LastLogin){ echo date("F d, Y",strtotime($user_data[0]->LastLogin)); }else{ echo "Never"; } ?><br>
          <b>SignUp</b>: <?php echo date("F d, Y",strtotime($user_data[0]->SignUp)) ?>
          <hr>
          <b>PM Privacy</b>: <?=$user_data[0]->privacy_pm?><br>
          <b>MassEmail Privacy</b>: <?=$user_data[0]->privacy_massemail?><br>
          <hr>
          <?php
            if($user_data[0]->isactive == "1"){
              echo "User Account Is Active";
              echo Form::open(array('method' => 'post'));
              echo "<input type='hidden' name='token_user' value='".$data['csrfToken']."'>";
              echo "<input type='hidden' name='deactivate_user' value='true' />";
              echo "<input type='hidden' name='au_id' value='".$user_data[0]->userID."'>";
              echo "<button class='btn btn-xs btn-danger' name='submit' type='submit'>Deactivate User</button>";
              echo Form::close();
            }else{
              echo "User Account Is Not Active";
              echo Form::open(array('method' => 'post'));
              echo "<input type='hidden' name='token_user' value='".$data['csrfToken']."'>";
              echo "<input type='hidden' name='activate_user' value='true' />";
              echo "<input type='hidden' name='au_id' value='".$user_data[0]->userID."'>";
              echo "<button class='btn btn-xs btn-success' name='submit' type='submit'>Activate User</button>";
              echo Form::close();
            }
          ?>
        </div>
      </div>

      <div class='card mb-3'>
        <div class='card-header h4'>
          <h3 class='jumbotron-heading'>User Groups</h3>
        </div>
          <?php
            echo "<table class='table table-hover responsive'>";
              // Displays User's Groups they are a member of
              if(isset($data['user_member_groups'])){
                echo "<th>Member of Following Groups</th>";
                foreach($data['user_member_groups'] as $member){
                  echo "<tr><td>";
                  echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                  echo "<input type='hidden' name='token_user' value='".$data['csrfToken']."'>";
                  echo "<input type='hidden' name='remove_group' value='true' />";
                  echo "<input type='hidden' name='au_userID' value='".$user_data[0]->userID."'>";
                  echo "<input type='hidden' name='au_groupID' value='".$member[0]->groupID."'>";
                  echo "<button class='btn btn-xs btn-danger' name='submit' type='submit'>Remove</button>";
                  echo Form::close();
                  echo " - <font color='".$member[0]->groupFontColor."' style='font-weight: ".$member[0]->groupFontWeight."'>".$member[0]->groupName."</font>";
                  echo "</td></tr>";
                }
              }else{
                echo "<th style='background-color: #EEE'>Member of Following Groups: </th>";
                echo "<tr><td> User Not Member of Any Groups </td></tr>";
              }
            echo "</table>";

            echo "<table class='table table-hover responsive'>";
              // Displays User's Groups they are not a member of
              if(isset($data['user_notmember_groups'])){
                echo "<th>Not Member of Following Groups</th>";
                foreach($data['user_notmember_groups'] as $notmember){
                  echo "<tr><td>";
                  echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                  echo "<input type='hidden' name='token_user' value='".$data['csrfToken']."'>";
                  echo "<input type='hidden' name='add_group' value='true' />";
                  echo "<input type='hidden' name='au_userID' value='".$user_data[0]->userID."'>";
                  echo "<input type='hidden' name='au_groupID' value='".$notmember[0]->groupID."'>";
                  echo "<button class='btn btn-xs btn-success' name='submit' type='submit'>Add</button>";
                  echo Form::close();
                  echo " - <font color='".$notmember[0]->groupFontColor."' style='font-weight: ".$notmember[0]->groupFontWeight."'>".$notmember[0]->groupName."</font> ";
                  echo "</td></tr>";
                }
              }else{
                echo "<th style='background-color: #EEE'>Not Member of Following Groups: </th>";
                echo "<tr><td> User is Member of All Groups </td></tr>";
              }
            echo "</table>";
          ?>
      </div>
    </div>
  </div>
</div>
