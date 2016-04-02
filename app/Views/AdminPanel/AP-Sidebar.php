<div class="col-lg-4 col-md-4 col-sm-4">
    <div class='panel panel-danger'>
        <div class='panel-heading'>
            <h3>Admin Panel</h3>
        </div>
        <ul class="list-group">
            <li class="list-group-item"><a href="<?php echo DIR; ?>AdminPanel">Dashboard</a></li>
            <li class="list-group-item"><a href="<?php echo DIR; ?>AdminPanel-Users">Users</a></li>
            <li class="list-group-item"><a href="<?php echo DIR; ?>AdminPanel-Groups">Groups</a></li>
        </ul>
    </div>

    <?php if($title == "Groups"){ ?>
      <?php echo Form::open(array('method' => 'post')); ?>
        <div class='panel panel-info'>
          <div class='panel-heading'>
            <i class='glyphicon glyphicon-tower'></i> Group Name
          </div>
          <div class='panel-body'>
            <?php echo Form::input(array('type' => 'text', 'name' => 'ag_groupName', 'class' => 'form-control', 'placeholder' => 'New Group Name', 'maxlength' => '150')); ?>
          </div>
          <div class='panel-footer'>
            <input type='hidden' name='token_groups' value='<?php echo $data['csrfToken'] ?>'>
            <input type='hidden' name='create_group' value='true' />
            <button name='submit' type='submit' class="btn btn-success">Create New Group</button>
          </div>
        </div>
      <?php echo Form::close(); ?>
    <?php } ?>

    <?php if($title == "User"){ ?>
    	<div class='panel panel-primary'>
    		<div class='panel-heading'>
    			<h3 class='jumbotron-heading'>Groups</h3>
    		</div>
    		<div class='panel-body'>
    			<?php
            echo "<table class='table table-hover responsive'>";
              // Displays User's Groups they are a member of
              if(isset($data['user_member_groups'])){
                echo "<th style='background-color: #EEE'>Member of Following Groups: </th>";
                foreach($data['user_member_groups'] as $member){
                  echo "<tr><td>";
                  echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                  echo "<input type='hidden' name='token_user' value='".$data['csrfToken']."'>";
                  echo "<input type='hidden' name='remove_group' value='true' />";
                  echo "<input type='hidden' name='au_userID' value='".$data['u_id']."'>";
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
                echo "<th style='background-color: #EEE'>Not Member of Following Groups: </th>";
                foreach($data['user_notmember_groups'] as $notmember){
                  echo "<tr><td>";
                  echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                  echo "<input type='hidden' name='token_user' value='".$data['csrfToken']."'>";
                  echo "<input type='hidden' name='add_group' value='true' />";
                  echo "<input type='hidden' name='au_userID' value='".$data['u_id']."'>";
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

    	<div class='panel panel-info'>
    		<div class='panel-heading'>
    			<h3 class='jumbotron-heading'>User Stats</h3>
    		</div>
    		<div class='panel-body'>
    			<b>Last Login</b>: <?php if($data['u_lastlogin']){ echo date("F d, Y",strtotime($data['u_lastlogin'])); }else{ echo "Never"; } ?><br>
    			<b>SignUp</b>: <?php echo date("F d, Y",strtotime($data['u_signup'])) ?>
    			<hr>
    			<?php
    				if($data['u_isactive'] == "1"){
    					echo "User Account Is Active";
              echo Form::open(array('method' => 'post'));
              echo "<input type='hidden' name='token_user' value='".$data['csrfToken']."'>";
              echo "<input type='hidden' name='deactivate_user' value='true' />";
              echo "<input type='hidden' name='au_id' value='".$data['u_id']."'>";
              echo "<button class='btn btn-xs btn-danger' name='submit' type='submit'>Deactivate User</button>";
              echo Form::close();
    				}else{
    					echo "User Account Is Not Active";
              echo Form::open(array('method' => 'post'));
              echo "<input type='hidden' name='token_user' value='".$data['csrfToken']."'>";
              echo "<input type='hidden' name='activate_user' value='true' />";
              echo "<input type='hidden' name='au_id' value='".$data['u_id']."'>";
              echo "<button class='btn btn-xs btn-success' name='submit' type='submit'>Activate User</button>";
              echo Form::close();
    				}
    			?>
    		</div>
    	</div>
    <?php } ?>
</div>
