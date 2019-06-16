<?php
/**
* Account Edit Profile Image View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Language, Libs\Form;
?>

<div class="col-lg-9 col-md-8 col-sm-12">

	<?php if($data['edit_image'] != ""){ ?>
		<div class="card mb-3">
			<div class="card-header">
				<?=Language::show('members_profile_cur_photo', 'Members'); ?>
			</div>
			<div class="card-body text-center">
				<img alt="User Pic" src="<?php echo SITE_URL.IMG_DIR_PROFILE.$data['edit_image']; ?>" class="rounded img-fluid">
				<hr>
				<?php

						if($data['edit_image'] != $data['main_image']){
							/** Setup Delete Button Form **/
							$button_display = Form::open(array('method' => 'post', 'style' => 'display:inline'));
								$button_display .= " <input type='hidden' name='image_action' value='default' /> ";
								$button_display .= " <input type='hidden' name='imageID' value='$imageID' /> ";
								$button_display .= " <input type='hidden' name='token_editprofile' value='$csrfToken' /> ";
								$button_display .= " <button type='submit' class='btn btn-primary btn-sm' value='submit' name='submit'>".Language::show('edit_profile_images_button_default', 'Members')." </button> ";
							$button_display .= Form::close();;
							echo $button_display;

							/** Setup Delete Button Form **/
							$button_display_delete = Form::open(array('method' => 'post', 'style' => 'display:inline'));
								$button_display_delete .= " <input type='hidden' name='image_action' value='delete' /> ";
								$button_display_delete .= " <input type='hidden' name='imageID' value='$imageID' /> ";
								$button_display_delete .= " <input type='hidden' name='token_editprofile' value='$csrfToken' /> ";
								$button_display_delete .= " <button type='submit' class='btn btn-danger' value='submit' name='submit'>".Language::show('edit_profile_images_button_delete', 'Members')." </button> ";
							$button_display_delete .= Form::close();;
							echo "<a href='#DeleteModal' class='btn btn-sm btn-danger trigger-btn' data-toggle='modal'>".Language::show('edit_profile_images_button_delete', 'Members')."</a>";

							echo "
								<div class='modal fade' id='DeleteModal' tabindex='-1' role='dialog' aria-labelledby='DeleteLabel' aria-hidden='true'>
									<div class='modal-dialog' role='document'>
										<div class='modal-content'>
											<div class='modal-header'>
												<h5 class='modal-title' id='DeleteLabel'>".Language::show('ep_delete_profile_photo', 'Members')."</h5>
												<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
													<span aria-hidden='true'>&times;</span>
												</button>
											</div>
											<div class='modal-body'>
												".Language::show('ep_delete_profile_photo_question', 'Members')."
											</div>
											<div class='modal-footer'>
												<button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
												$button_display_delete
											</div>
										</div>
									</div>
								</div>
							";
						}
					}

				?>
			</div>
		</div>
	</div>
