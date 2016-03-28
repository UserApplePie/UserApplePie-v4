    <div class="col-md-4 col-lg-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4><?php echo $data['profile']->username; ?></h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8 col-lg-8" align="center">
                        <img alt="<?php echo $data['profile']->username; ?>'s Profile Picture" src="<?php echo DIR.$data['profile']->userImage; ?>" class="img-circle img-responsive">
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class=" col-md-12 col-lg-12 ">
                        <table class="table table-striped">
                            <tbody><tr><td>First Name</td><td><?php echo $data['profile']->firstName; ?></td></tr>
                            <tr><td>Group</td><td>Not RETRIEVING GROUPS YET</td></tr>
                            <tr><td>Gender</td><td><?php echo $data['profile']->gender; ?></td></tr>
                            <tr><td>Website</td><td><a href="<?php echo $data['profile']->website; ?>" target="_blank">View</a></td></tr>
                            <tr><td>Last Login</td><td><?php echo $data['profile']->LastLogin; ?></td></tr>
                            <tr><td>Sign Up</td><td><?php echo $data['profile']->SignUp; ?></td></tr>
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
