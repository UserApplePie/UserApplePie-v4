
<div class="col-lg-8 col-md-8 col-sm-8">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1><?=$title;?></h1>
		</div>
    <table class="table table-striped table-hover table-bordered responsive">
        <thead>
            <tr>
                <th>Username</th>
                <th>First Name</th>
                <th>User Group</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($data['members'] as $member){
                echo "<tr>
                        <td><a href='".DIR."profile/{$member->username}'> {$member->username}</a></td>
                        <td>{$member->firstName}</td>
                        <td><font color='{$member->groupFontColor}' style='font-weight:{$member->groupFontWeight}'>{$member->groupName}</font></td></tr>";
            }
        ?>
        </tbody>
    </table>
  </div>
</div>
