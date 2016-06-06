<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
  <ul class="nav menu">
    <li <?php if($title == "Dashboard"){echo "class='active'";} ?>><a href="<?php echo DIR; ?>AdminPanel"><i class='glyphicon glyphicon-cog'></i> Dashboard</a></li>
    <li <?php if($title == "Users" || $title == "User"){echo "class='active'";} ?>><a href="<?php echo DIR; ?>AdminPanel-Users"><i class='glyphicon glyphicon-user'></i> Users</a></li>
    <li <?php if($title == "Groups" || $title == "Group"){echo "class='active'";} ?>><a href="<?php echo DIR; ?>AdminPanel-Groups"><i class='glyphicon glyphicon-tower'></i> Groups</a></li>
    <li <?php if($title == "Mass Email"){echo "class='active'";} ?>><a href="<?php echo DIR; ?>AdminPanel-MassEmail"><i class='glyphicon glyphicon-envelope'></i> Mass Email</a></li>
  </ul>

    <?php
      /* Check to see if Forum Plugin is installed. If so then show forum admin links */
      if(file_exists('../app/Modules/Forum/forum.module.php')){
    ?>
      <ul class="nav menu">
        <li class="sidebar-title">Forum</li>
        <li <?php if($title == "Forum Global Settings"){echo "class='active'";} ?>>
          <a href='<?php echo DIR; ?>AdminPanel-Forum-Settings'><i class='glyphicon glyphicon-cog'></i> Global Settings</a>
        </li>
        <li <?php if($title == "Forum Categories"){echo "class='active'";} ?>>
          <a href='<?php echo DIR; ?>AdminPanel-Forum-Categories'><i class='glyphicon glyphicon-list'></i> Categories</a>
        </li>
        <li <?php if($title == "Forum Blocked Content"){echo "class='active'";} ?>>
          <a href='<?php echo DIR; ?>AdminPanel-Forum-Blocked-Content'><i class='glyphicon glyphicon-remove-sign'></i> Blocked Content</a>
        </li>
      </ul>
    <?php } ?>
</div>
