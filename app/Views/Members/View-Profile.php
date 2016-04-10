    <div class="col-md-4 col-lg-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4><?php echo $data['profile']->username; ?></h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8 col-lg-8" align="center">
                      <?php if(!empty($data['profile']->userImage)){ ?>
                        <img alt="<?php echo $data['profile']->username; ?>'s Profile Picture" src="<?php echo DIR.$data['profile']->userImage; ?>" class="img-circle img-responsive">
                        <?php }else{ ?>
          								<span class='glyphicon glyphicon-user icon-size'></span>
          							<?php } ?>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class=" col-md-12 col-lg-12 ">
                        <table class="table table-striped">
                            <tbody>
                              <?php
                                /* Check to see if Private Message Module is installed, if it is show link */
                                if(file_exists('../app/Modules/Messages/messages.module.php')){
                                  echo "<tr><td>PM</td><td><a href='/NewMessage/".$data['profile']->username."' class='btn btn-xs btn-default'>Send Message</a></td></tr>";
                                }
                              ?>
                              <tr><td>First Name</td><td><?php echo $data['profile']->firstName; ?></td></tr>
                              <?php
                                if($data['user_groups']){
                                  echo "<tr><td>Group</td><td>";
                                  foreach($data['user_groups'] as $row){
                                    echo " $row <br>";
                                  }
                                  echo "</td></tr>";
                                }
                              ?>
                            <tr><td>Gender</td><td><?php echo $data['profile']->gender; ?></td></tr>
                            <tr><td>Website</td><td><a href="<?php echo $data['profile']->website; ?>" target="_blank">View</a></td></tr>
                            <tr><td>Last Login</td><td><?php echo date("F d, Y",strtotime($data['profile']->LastLogin)); ?></td></tr>
                            <tr><td>Sign Up</td><td><?php echo date("F d, Y",strtotime($data['profile']->SignUp)); ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 col-lg-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>All About <?php echo $data['profile']->username; ?></h4>
            </div>
            <div class="panel-body">
                <?php echo $data['profile']->aboutme; ?>
            </div>
        </div>
    </div>
