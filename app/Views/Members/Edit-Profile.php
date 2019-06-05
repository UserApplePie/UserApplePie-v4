<?php
/**
* Account Edit Profile View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Language, Libs\Form;
?>

<div class="col-lg-8 col-md-8 col-sm-12">
	<div class="card mb-3">
		<div class="card-header h4">
			<?=$title;?>
		</div>
		<div class="card-body">
        <div class="col-xs-12">
            <h4><?=Language::show('edit_profile', 'Members'); ?> <strong><?php echo $data['profile']->username; ?></strong></h4>
            <hr>

            <form role="form" method="post" enctype="multipart/form-data">

                <div class="input-group mb-3">
									<div class="input-group-prepend">
										<div class="input-group-text"><?=Language::show('members_profile_firstname', 'Members'); ?> </div>
									</div>
                  <input id="firstName" type="text" class="form-control" name="firstName" placeholder="<?=Language::show('members_profile_firstname', 'Members'); ?>" value="<?php echo $data['profile']->firstName; ?>">
									<div class="input-group-append">
										<div class="input-group-text"><span class="badge badge-danger"><?=Language::show('required', 'Members'); ?></span></div>
									</div>
                </div>

								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<div class="input-group-text"><?=Language::show('members_profile_lastname', 'Members'); ?> </div>
									</div>
									<input id="lastName" type="text" class="form-control" name="lastName" placeholder="<?=Language::show('members_profile_lastname', 'Members'); ?>" value="<?php echo $data['profile']->lastName; ?>">
									<div class="input-group-append">
										<div class="input-group-text"><span class="badge badge-danger"><?=Language::show('required', 'Members'); ?></span></div>
									</div>
								</div>

								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<div class="input-group-text"><?=Language::show('members_profile_gender', 'Members'); ?> </div>
									</div>
									<select class='form-control' id='gender' name='gender'>
											<option value='male' <?php if($data['profile']->gender == "Male") echo "selected";?> >Male</option>
											<option value='female' <?php if($data['profile']->gender == "Female") echo "selected";?> >Female</option>
									</select>
									<div class="input-group-append">
										<div class="input-group-text"><span class="badge badge-danger"><?=Language::show('required', 'Members'); ?></span></div>
									</div>
								</div>

								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<div class="input-group-text"><?=Language::show('members_profile_website', 'Members'); ?> </div>
									</div>
									<input id="website" type="website" class="form-control" name="website" placeholder="<?=Language::show('members_profile_website', 'Members'); ?>" value="<?php echo $data['profile']->website; ?>">
								</div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
											<div class="input-group-text"><?=Language::show('edit_profile_aboutme', 'Members'); ?></div>
										</div>
                    <textarea id="aboutMe"  class="form-control" name="aboutMe" placeholder="<?=Language::show('edit_profile_aboutme', 'Members'); ?>" rows="5"><?php echo str_replace('<br />' , '', $data['profile']->aboutme); ?></textarea>
                </div>

								<?php
									/* Check to see if Private Message Module is installed, if it is show link */
									if(file_exists(ROOTDIR.'app/Plugins/Forum/Controllers/Forum.php')){
								?>
								<div class="input-group mb-3">
										<div class="input-group-prepend">
											<div class="input-group-text"><?=Language::show('edit_profile_forum_sign', 'Members'); ?> </div>
										</div>
	                  <textarea id="signature"  class="form-control" name="signature" placeholder="<?=Language::show('edit_profile_forum_sign', 'Members'); ?>" rows="5"><?php echo str_replace('<br />' , '', $data['profile']->signature); ?></textarea>
	                </div>
								<?php } ?>

                <input type="hidden" name="token_editprofile" value="<?=$csrfToken;?>" />
                <input type="submit" name="submit" class="btn btn-primary" value="<?=Language::show('edit_profile_button', 'Members'); ?>">
            </form>
        </div>
    </div>
  </div>
</div>
