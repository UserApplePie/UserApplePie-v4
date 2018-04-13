<?php
/**
* UserApplePie v4 Forum View Plugin Admin Categories
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.0
*/

/** Forum Categories Admin Panel View **/

use Libs\Form,
  Core\Success,
  Core\Language;

?>

<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class='card mb-3'>
		<div class='card-header h4'>
			<h3 class='jumbotron-heading'><?php echo $data['title'];  ?></h3>
		</div>
		<div class='card-body'>
			<p><?php echo $data['welcome_message'] ?></p>
    </div>
	</div>

      <?php
            // Check to see if admin is editing a category
            if($data['edit_cat_main'] == true){
              // Display form with data to edit
              if(isset($data['data_cat_main'])){
                echo Form::open(array('method' => 'post'));
                  echo "<div class='card border-primary mb-3'>";
                    echo "<div class='card-header h4'>";
                      echo "<i class='fas fa-list'></i> Update Main Category Title";
                    echo "</div>";
                    echo "<div class='card-body'>";
                      echo Form::input(array('type' => 'hidden', 'name' => 'prev_forum_title', 'value' => $data['data_cat_main']));
                      echo Form::input(array('type' => 'hidden', 'name' => 'action', 'value' => 'update_cat_main_title'));
                      echo Form::input(array('type' => 'hidden', 'name' => 'token_ForumAdmin', 'value' => $data['csrf_token']));
                      echo Form::input(array('type' => 'text', 'name' => 'forum_title', 'class' => 'form-input text form-control', 'aria-describedby' => 'basic-addon1', 'value' => $data['data_cat_main'], 'placeholder' => 'Main Category Title', 'maxlength' => '100'));
                    echo "</div>";
                    echo "<div class='card-footer text-muted'>";
                      echo "<button class='btn btn-success' name='submit' type='submit'>Update Main Category Title</button>";
                    echo "</div>";
                  echo "</div>";
                echo Form::close();
              }
            }else if($data['cat_sub_list'] == true){
              // Display Main Category Title
              if(isset($data['cat_main_title'])){
                echo "<div class='well'><h4>Sub Categories for: ".$data['cat_main_title']."</h4></div>";
              }
              // Display sub categories for requeted main category
              if(isset($data['cat_sub_titles'])){
                foreach ($data['cat_sub_titles'] as $row) {
                  if(!empty($row->forum_cat)){
                    echo "<div class='card border-primary mb-3'>";
                      echo "<div class='card-header h4'>";
                        echo "<strong>$row->forum_cat</strong><br>";
                        echo "$row->forum_des";
                      echo "</div>";
                      echo "<div class='card-body'>";
                        echo "<div class='col-lg-6 col-md-6'>";
                          // Display total number of topics for this category
                          echo " <div class='label label-warning' style='margin-top: 5px'>";
                          echo "Topics <span class='badge'>$row->total_topics_display</span>";
                          echo "</div> ";
                          // Display total number of topic replies for this category
                          echo " <div class='label label-warning' style='margin-top: 5px'>";
                          echo "Replies <span class='badge'>$row->total_topic_replys_display</span>";
                          echo "</div> ";
                        echo "</div>";
                        echo "<div class='col-lg-6 col-md-6' style='text-align: right'>";
                          // Check to see if object is at top
                          if($row->forum_order_cat > 1){
                            echo "<a href='".DIR."AdminPanel-Forum-Categories/CatSubUp/$row->forum_id/$row->forum_order_cat' class='btn btn-primary btn-xs' role='button'><span class='fas fa-triangle-top' aria-hidden='true'></span></a> ";
                          }
                          // Check to see if object is at bottom
                          if($data['fourm_cat_sub_last'] != $row->forum_order_cat){
                            echo "<a href='".DIR."AdminPanel-Forum-Categories/CatSubDown/$row->forum_id/$row->forum_order_cat' class='btn btn-primary btn-xs' role='button'><span class='fas fa-triangle-bottom' aria-hidden='true'></span></a> ";
                          }
                          echo "<a href='".DIR."AdminPanel-Forum-Categories/CatSubEdit/$row->forum_id' class='btn btn-success btn-xs' role='button'><span class='fas fa-cog' aria-hidden='true'></span> Edit</a> ";
                          echo "<a href='".DIR."AdminPanel-Forum-Categories/DeleteSubCat/$row->forum_id' class='btn btn-danger btn-xs' role='button'><span class='fas fa-remove-circle' aria-hidden='true'></span></a> ";
                        echo "</div>";
                      echo "</div>";
                    echo "</div>";
                  }else{
                    echo "<div class='well'>No Sub categories for ".$data['cat_main_title']."</div>";
                  }
                }
              }
              // Display form to create new sub cat
              echo Form::open(array('method' => 'post', 'action' => DIR.'AdminPanel-Forum-Categories/CatSubList/'.$row->forum_id));
                echo "<div class='card border-info mb-3'>";
                  echo "<div class='card-header h4'>";
                    echo "<i class='fas fa-list'></i> Create New Sub Category";
                  echo "</div>";
                  echo "<div class='card-body'>";
                    echo Form::input(array('type' => 'hidden', 'name' => 'action', 'value' => 'new_cat_sub'));
                    echo Form::input(array('type' => 'hidden', 'name' => 'forum_title', 'value' => $data['cat_main_title']));
                    echo Form::input(array('type' => 'hidden', 'name' => 'token_ForumAdmin', 'value' => $data['csrf_token']));
                    echo "<strong>Sub Cat Title:</strong>";
                    echo Form::input(array('type' => 'text', 'name' => 'forum_cat', 'class' => 'form-input text form-control', 'aria-describedby' => 'basic-addon1', 'placeholder' => 'New Sub Category Title', 'maxlength' => '255'));
                    echo "<strong>Sub Cat Description:</strong>";
                    echo Form::textBox(array('type' => 'text', 'name' => 'forum_des', 'class' => 'form-input text form-control', 'aria-describedby' => 'basic-addon1', 'placeholder' => 'New Sub Category Description'));
                  echo "</div>";
                  echo "<div class='card-footer text-muted'>";
                    echo "<button class='btn btn-success' name='submit' type='submit'>Create New Sub Category</button>";
                  echo "</div>";
                echo "</div>";
              echo Form::close();
            }else if($data['cat_sub_edit'] == true){
              // Display Cat Sub Edit Form
              if(isset($data['cat_sub_data'])){
                foreach ($data['cat_sub_data'] as $row) {
                  echo Form::open(array('method' => 'post', 'action' => DIR.'AdminPanel-Forum-Categories/CatSubEdit/'.$row->forum_id));
                    echo "<div class='card border-primary mb-3'>";
                      echo "<div class='card-header h4'>";
                        echo "<i class='fas fa-list'></i> Edit Sub Category";
                      echo "</div>";
                      echo "<div class='card-body'>";
                        echo Form::input(array('type' => 'hidden', 'name' => 'action', 'value' => 'edit_cat_sub'));
                        echo Form::input(array('type' => 'hidden', 'name' => 'token_ForumAdmin', 'value' => $data['csrf_token']));
                        echo "<strong>Sub Cat Title:</strong>";
                        echo Form::input(array('type' => 'text', 'name' => 'forum_cat', 'value' => $row->forum_cat, 'class' => 'form-input text form-control', 'aria-describedby' => 'basic-addon1', 'placeholder' => 'Sub Category Title', 'maxlength' => '255'));
                        echo "<strong>Sub Cat Description:</strong>";
                        echo Form::textBox(array('type' => 'text', 'name' => 'forum_des', 'value' => $row->forum_des, 'class' => 'form-input text form-control', 'aria-describedby' => 'basic-addon1', 'placeholder' => 'Sub Category Description'));
                      echo "</div>";
                      echo "<div class='card-footer text-muted'>";
                        echo "<button class='btn btn-success' name='submit' type='submit'>Update Sub Category</button>";
                      echo "</div>";
                    echo "</div>";
                  echo Form::close();
                }
              }
            }else if($data['delete_cat_main']  == true){
              // Display Delete Main Cat Form
              echo Form::open(array('method' => 'post'));
                echo "<div class='card card-danger'>";
                  echo "<div class='card-header h4'>";
                    echo "Delete Forum Main Category: <strong>".$data['delete_cat_main_title']."</strong>";
                  echo "</div>";
                  echo "<div class='card-body'>";
                    echo "What would you like to do with all Sub Categories, Topics, and Topic Replies that are connected to Main Category: <strong>".$data['delete_cat_main_title']."</strong> ?";
                    echo "<select class='form-control' id='delete_cat_main_action' name='delete_cat_main_action'>";
                      echo "<option value=''>Select What To Do With Content</option>";
                      echo "<option value='delete_all'>Delete Everything Related to Main Category (This Can NOT be undone!)</option>";
                      // Show list of all main categories that admin can move content to
                      if(isset($data['list_all_cat_main'])){
                        foreach ($data['list_all_cat_main'] as $row) {
                          echo "<option value='move_to_$row->forum_id'>Move Content to: $row->forum_title</option>";
                        }
                      }
                    echo "</select>";
                  echo "</div>";
                  echo "<div class='card-footer text-muted'>";
                    echo "<button class='btn btn-danger' name='submit' type='submit'>Delete Main Category</button>";
                  echo "</div>";
                echo "</div>";
                echo Form::input(array('type' => 'hidden', 'name' => 'action', 'value' => 'delete_cat_main'));
                echo Form::input(array('type' => 'hidden', 'name' => 'token_ForumAdmin', 'value' => $data['csrf_token']));
              echo Form::close();
            }else if($data['delete_cat_sub']  == true){
              // Display Delete Sub Cat Form
              echo Form::open(array('method' => 'post'));
                echo "<div class='card card-danger'>";
                  echo "<div class='card-header h4'>";
                    echo "Delete Forum Sub Category: <strong>".$data['delete_cat_sub_title']."</strong>";
                  echo "</div>";
                  echo "<div class='card-body'>";
                    echo "What would you like to do with all Topics, and Topic Replies that are connected to Sub Category: <strong>".$data['delete_cat_sub_title']."</strong> ?";
                    echo "<select class='form-control' id='delete_cat_sub_action' name='delete_cat_sub_action'>";
                      echo "<option value=''>Select What To Do With Content</option>";
                      echo "<option value='delete_all'>Delete Everything Related to Sub Category (This Can NOT be undone!)</option>";
                      // Show list of all main categories that admin can move content to
                      if(isset($data['list_all_cat_sub'])){
                        foreach ($data['list_all_cat_sub'] as $row) {
                          echo "<option value='move_to_$row->forum_id'>Move Content to: $row->forum_title > $row->forum_cat</option>";
                        }
                      }
                    echo "</select>";
                  echo "</div>";
                  echo "<div class='card-footer text-muted'>";
                    echo "<button class='btn btn-danger' name='submit' type='submit'>Delete Sub Category</button>";
                  echo "</div>";
                echo "</div>";
                echo Form::input(array('type' => 'hidden', 'name' => 'action', 'value' => 'delete_cat_sub'));
                echo Form::input(array('type' => 'hidden', 'name' => 'token_ForumAdmin', 'value' => $data['csrf_token']));
              echo Form::close();
            }else{
              // Display main categories for forum
              if(isset($data['cat_main'])){
                foreach($data['cat_main'] as $row){
                  echo "<div class='card border-primary mb-3'>";
                    echo "<div class='card-header h4'>";
                      echo "<h3 class='panel-title'>";
                        echo $row->forum_title;
                      echo "</h3>";
                    echo "</div>";
                    echo "<div class='card-body'>";
                      echo "<div class='col-lg-6 col-md-6' style='text-align: left; margin-bottom: 2px'>";
                        // Display total number of sub cats for this category
                        echo " <div class='label label-warning' style='margin-top: 5px'>";
                        echo "Sub Cats <span class='badge'>$row->total_sub_cats</span>";
                        echo "</div> ";
                        // Display total number of topics for this category
                        echo " <div class='label label-warning' style='margin-top: 5px'>";
                        echo "Topics <span class='badge'>$row->total_topics_display</span>";
                        echo "</div> ";
                        // Display total number of topic replies for this category
                        echo " <div class='label label-warning' style='margin-top: 5px'>";
                        echo "Replies <span class='badge'>$row->total_topic_replys_display</span>";
                        echo "</div> ";
                      echo "</div>";
                      echo "<div class='col-lg-6 col-md-6' style='text-align: right; margin-bottom: 2px'>";
                        // Check to see if object is at top
                        if($row->forum_order_title > 1){
                          echo "<a href='".DIR."AdminPanel-Forum-Categories/CatMainUp/$row->forum_order_title' class='btn btn-primary btn-xs' role='button'><span class='fas fa-triangle-top' aria-hidden='true'></span></a> ";
                        }
                        // Check to see if object is at bottom
                        if($data['fourm_cat_main_last'] != $row->forum_order_title){
                          echo "<a href='".DIR."AdminPanel-Forum-Categories/CatMainDown/$row->forum_order_title' class='btn btn-primary btn-xs' role='button'><span class='fas fa-triangle-bottom' aria-hidden='true'></span></a> ";
                        }
                        echo "<a href='".DIR."AdminPanel-Forum-Categories/CatMainEdit/$row->forum_id' class='btn btn-success btn-xs' role='button'><span class='fas fa-cog' aria-hidden='true'></span> Edit</a> ";
                        echo "<a href='".DIR."AdminPanel-Forum-Categories/CatSubList/$row->forum_id' class='btn btn-info btn-xs' role='button'><span class='fas fa-list' aria-hidden='true'></span> Sub Categories</a> ";
                        echo "<a href='".DIR."AdminPanel-Forum-Categories/DeleteMainCat/$row->forum_id' class='btn btn-danger btn-xs' role='button'><span class='fas fa-remove-circle' aria-hidden='true'></span></a> ";
                      echo "</div>";
                    echo "</div>";
                  echo "</div>";
                }// End of foreach
              }// End of isset
              // Display form to create new Main Category
              echo Form::open(array('method' => 'post', 'action' => DIR.'AdminPanel-Forum-Categories/CatMainNew/1'));
                echo "<div class='card mb-3'>";
                  echo "<div class='card-header h4'>";
                    echo "<i class='fas fa-list'></i> New Main Category Title";
                  echo "</div>";
                  echo "<div class='card-body'>";
                    echo Form::input(array('type' => 'hidden', 'name' => 'action', 'value' => 'new_cat_main_title'));
                    echo Form::input(array('type' => 'hidden', 'name' => 'token_ForumAdmin', 'value' => $data['csrf_token']));
                    echo Form::input(array('type' => 'text', 'name' => 'forum_title', 'class' => 'form-input text form-control', 'aria-describedby' => 'basic-addon1', 'placeholder' => 'New Main Category Title', 'maxlength' => '100'));
                  echo "</div>";
                  echo "<div class='card-footer text-muted'>";
                    echo "<button class='btn btn-success' name='submit' type='submit'>Create New Main Category Title</button>";
                  echo "</div>";
                echo "</div>";
              echo Form::close();
            }// End of action check

            

      ?>
</div>
