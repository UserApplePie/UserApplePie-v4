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
            <form role="form" method="post" enctype="multipart/form-data">
								<div class="input-group mb-3">
								  <div class="input-group-prepend">
								    <span class="input-group-text" id="inputGroupFileAddon01"><?=Language::show('members_profile_new_photo', 'Members'); ?></span>
								  </div>
								  <div class="custom-file">
										<input type="file" class="custom-file-input" accept="image/jpeg, image/gif, image/x-png" id="profilePic" name="profilePic[]" aria-describedby="inputGroupFileAddon01" multiple="multiple">
								    <label class="custom-file-label" for="inputGroupFile01">Select Image Files</label>
								  </div>
								</div>

                <input type="hidden" name="token_editprofile" value="<?=$csrfToken;?>" />
                <input type="submit" name="submit" class="btn btn-primary" value="<?=Language::show('edit_profile_images_button', 'Members'); ?>">
            </form>
        </div>
    </div>
  </div>

	<?php if($data['main_image'] != ""){ ?>
		<input id="oldImg" name="oldImg" type="hidden" value="<?php echo $data['main_image']; ?>"">
		<div class="card mb-3">
			<div class="card-header">
				<?=Language::show('members_profile_cur_photo', 'Members'); ?>
			</div>
			<div class="card-body text-center">
				<img alt="User Pic" src="<?php echo SITE_URL.IMG_DIR_PROFILE.$data['main_image']; ?>" class="rounded img-fluid">
			</div>
		</div>
	<?php } ?>

	<div class="card mb-3">
		<div class="card-header h4">
			<?php echo $data['profile']->username; ?>'s Images
		</div>
		<div class="card-body">
				<div class='row'>
					<?php
						if(isset($data['user_images'])){
							foreach ($data['user_images'] as $row) {
								echo "<div class='col-lg-2 col-md-3 col-sm-4 col-xs-6' style='padding-bottom: 6px'>";
									echo "<a href='".SITE_URL.IMG_DIR_PROFILE."$row->userImage' target='_blank'><img src='".SITE_URL.IMG_DIR_PROFILE."$row->userImage' class='img-thumbnail'></a>";
								echo "</div>";
							}
						}
					?>
				</div>
		</div>
	</div>
</div>
