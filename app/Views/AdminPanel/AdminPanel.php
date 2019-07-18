<?php
/**
* Admin Panel Home View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

if(isset($cur_uap_version) || isset($cur_uap_messages_version) || isset($cur_uap_forum_version) || isset($cur_uap_friends_version) || isset($cur_uap_comments_version)){
	echo "<div class='col-lg-12 col-md-12 col-sm-12'>";
	echo "<div class='alert alert-danger'>";
		if(isset($cur_uap_version)){
			echo "<b>New Update Released for UAP! <br>";
			echo "New Version:</b> $cur_uap_version <br>";
			echo "<b>Current Version:</b> ".UAPVersion."<br>";
		}
		if(isset($cur_uap_messages_version)){
			echo "<br><b>New Update Released for UAP Messages Plugin! <br>";
			echo "New Version:</b> $cur_uap_messages_version <br>";
			echo "<b>Current Version:</b> ".UAPMessagesVersion."<br>";
		}
		if(isset($cur_uap_forum_version)){
			echo "<br><b>New Update Released for UAP Forum Plugin! <br>";
			echo "New Version:</b> $cur_uap_forum_version <br>";
			echo "<b>Current Version:</b> ".UAPForumVersion."<br>";
		}
		if(isset($cur_uap_friends_version)){
			echo "<br><b>New Update Released for UAP Friends Plugin! <br>";
			echo "New Version:</b> $cur_uap_friends_version <br>";
			echo "<b>Current Version:</b> ".UAPFriendsVersion."<br>";
		}
		if(isset($cur_uap_comments_version)){
			echo "<br><b>New Update Released for UAP Comments Plugin! <br>";
			echo "New Version:</b> $cur_uap_comments_version <br>";
			echo "<b>Current Version:</b> ".UAPCommentsVersion."<br>";
		}
		echo "<hr>Visit <a href='http://www.userapplepie.com' target='_blank'>www.UserApplePie.com</a> For Updates";
	echo "</div>";
	echo "</div>";
}
if($uap_files_version > $uap_database_version){
	echo "<div class='col-lg-12 col-md-12 col-sm-12'>";
	echo "<div class='alert alert-danger'>";
		echo "<b>UAP Database is out of Date. <br>";
		echo "New Version:</b> $uap_files_version <br>";
		echo "<b>Current Version:</b> $uap_database_version <br>";
		echo "<a href='".SITE_URL."AdminPanel-Upgrade'>Click Here to Upgrade</a>";
	echo "</div>";
	echo "</div>";
}

?>
<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class='row'>
		<div class="col-xs-12 col-md-6 col-lg-3 mb-3">
			<div class="card text-white bg-primary o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-user"></i>
              </div>
              <div class="mr-5"><?=$activatedAccounts?></div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="<?=SITE_URL?>AdminPanel-Users">
              <span class="float-left">Site Members</span>
              <span class="float-rightt">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
			</div>
		</div>

		<div class="col-xs-12 col-md-6 col-lg-3 mb-3">
			<div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-users"></i>
              </div>
              <div class="mr-5"><?=$usergroups?></div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="<?=SITE_URL?>AdminPanel-Groups">
              <span class="float-left">User Groups</span>
              <span class="float-rightt">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
			</div>
		</div>

		<div class="col-xs-12 col-md-6 col-lg-3 mb-3">
			<div class="card text-white bg-success o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-user"></i>
              </div>
              <div class="mr-5"><?=$onlineAccounts?></div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="<?=SITE_URL?>AdminPanel-Users">
              <span class="float-left">Online Members</span>
              <span class="float-rightt">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
			</div>
		</div>

		<div class="col-xs-12 col-md-6 col-lg-3 mb-3">
			<div class="card text-white bg-danger o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-fw fa-road"></i>
              </div>
              <div class="mr-5"><?=$totalPageViews?></div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="#">
              <span class="float-left">Page Views</span>
              <span class="float-rightt">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="card mb-3">
				<div class="card-header h4">Site Traffic Overview<span class='float-right'><small><font color='#30a4ff'>Current Year</font> <font color='#dcdcdc'>Previous Year</font></small></span></div>
				<div class="card-body">
					<div class="canvas-wrapper">
						<canvas class="main-chart" id="line-chart" height="200" width="600"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div><!--/.row-->

	<div class="row">
		<div class='col-lg-6 col-md-6'>
			<div class='card mb-3'>
				<div class='card-header h4'>
					Users Signed Up Stats
				</div>
				<ul class='list-group list-group-flush'>
						<li class='list-group-item'><span class='pull-left'>Past Day:</span><span class='float-right'><?=$mem_signup_past_1?></span><div class='clearfix'></div></li>
						<li class='list-group-item'><span class='pull-left'>Past Week:</span><span class='float-right'><?=$mem_signup_past_7?></span><div class='clearfix'></div></li>
						<li class='list-group-item'><span class='pull-left'>Past 30 Days:</span><span class='float-right'><?=$mem_signup_past_30?></span><div class='clearfix'></div></li>
						<li class='list-group-item'><span class='pull-left'>Past 90 Days:</span><span class='float-right'><?=$mem_signup_past_90?></span><div class='clearfix'></div></li>
						<li class='list-group-item'><span class='pull-left'>Past Year:</span><span class='float-right'><?=$mem_signup_past_365?></span><div class='clearfix'></div></li>
				</ul>
			</div>
		</div>

		<div class='col-lg-6 col-md-6'>
			<div class='card mb-3'>
				<div class='card-header h4'>
					Users Logged In Stats
				</div>
				<ul class='list-group list-group-flush'>
						<li class='list-group-item'><span class='pull-left'>Past Day:</span><span class='float-right'><?=$mem_login_past_1?></span><div class='clearfix'></div></li>
						<li class='list-group-item'><span class='pull-left'>Past Week:</span><span class='float-right'><?=$mem_login_past_7?></span><div class='clearfix'></div></li>
						<li class='list-group-item'><span class='pull-left'>Past 30 Days:</span><span class='float-right'><?=$mem_login_past_30?></span><div class='clearfix'></div></li>
						<li class='list-group-item'><span class='pull-left'>Past 90 Days:</span><span class='float-right'><?=$mem_login_past_90?></span><div class='clearfix'></div></li>
						<li class='list-group-item'><span class='pull-left'>Past Year:</span><span class='float-right'><?=$mem_login_past_365?></span><div class='clearfix'></div></li>
				</ul>
			</div>
		</div>

		<div class='col-lg-6 col-md-6'>
			<div class='card mb-3'>
				<div class='card-header h4'>
					Top Referers Past Month
				</div>
				<ul class='list-group list-group-flush'>
					<?php
						if(isset($topRefer)){
							foreach ($topRefer as $refer) {
								echo "<li class='list-group-item'><span class='pull-left'>$refer->refer</span><span class='float-right'>$refer->refer_count</span><div class='clearfix'></div></li>";
							}
						}
					?>
				</ul>
			</div>
		</div>

		<div class='col-lg-6 col-md-6'>
			<div class='card mb-3'>
				<div class='card-header h4'>
					Top Referers Past Year
				</div>
				<ul class='list-group list-group-flush'>
					<?php
						if(isset($topReferYear)){
							foreach ($topReferYear as $refer) {
								echo "<li class='list-group-item'><span class='pull-left'>$refer->refer</span><span class='float-right'>$refer->refer_count</span><div class='clearfix'></div></li>";
							}
						}
					?>
				</ul>
			</div>
		</div>

		<div class='col-lg-12 col-md-12'>
			<div class='card mb-3'>
				<div class='card-header h4'>
					Installed Plugins
				</div>
				<ul class='list-group list-group-flush'>
						<li class='list-group-item'><span class='pull-left'>Forum Plugin:</span><span class='float-right'><?=$apd_plugin_forum?></span><div class='clearfix'></div></li>
						<li class='list-group-item'><span class='pull-left'>Private Messages Plugin:</span><span class='float-right'><?=$apd_plugin_message?></span><div class='clearfix'></div></li>
						<li class='list-group-item'><span class='pull-left'>Friends Plugin:</span><span class='float-right'><?=$apd_plugin_friends?></span><div class='clearfix'></div></li>
						<li class='list-group-item'><span class='pull-left'>Comments Plugin:</span><span class='float-right'><?=$apd_plugin_comments?></span><div class='clearfix'></div></li>
				</ul>
			</div>
		</div>

		<div class='col-lg-12 col-md-12'>
			<div class='card mb-3'>
				<div class='card-header h4'>
					Site Map File
				</div>
				<ul class='list-group list-group-flush'>
						<li class='list-group-item'><a href='<?=SITE_URL?>sitemap.xml' target='_blank' class='pull-left'><?=SITE_URL?>sitemap.xml</a><span class='float-right'>Copy URL to <a href='https://search.google.com/search-console/' target='_blank'>Google Search Console</a></span></li>
				</ul>
			</div>
		</div>

		<div class='col-lg-12 col-md-12'>
			<div class='card mb-3'>
				<div class='card-header h4'>
					UserApplePie Support and Community
				</div>
				<ul class='list-group list-group-flush'>
						<li class='list-group-item'>
							<i class='fas fa-lg fa-home'></i> <a href='https://www.userapplepie.com/Forum' target='_blank' class='pull-left'>UserApplePie Forums</a>
							<span class='float-right'><a href='https://discord.gg/tcr4sJE' target='_blank' class='pull-left'>Discord</a> <i class='fab fa-lg fa-discord'></i></span>
						</li>
						<li class='list-group-item'>
							<i class='fab fa-lg fa-github'></i> <a href='https://github.com/UserApplePie/UserApplePie-v4' target='_blank' class='pull-left'>GitHub Repo</a>
							<span class='float-right'><a href='https://www.facebook.com/Userapplepie/' target='_blank' class='pull-left'>Facebook</a> <i class='fab fa-lg fa-facebook'></i></span>
						</li>
						<li class='list-group-item'>
							<i class='fab fa-lg fa-gitter'></i> <a href='https://gitter.im/User-Apple-Pie/Lobby?utm_source=share-link&utm_medium=link&utm_campaign=share-link' target='_blank' class='pull-left'>Gitter</a>
							<span class='float-right'><a href='https://twitter.com/UserApplePie' target='_blank' class='pull-left'>Twitter</a> <i class='fab fa-lg fa-twitter'></i></span>
						</li>
				</ul>
			</div>
		</div>

	</div>
</div>

<?php
$first  = strtotime('first day of next month');
$months = array();

for ($i = 7; $i >= 1; $i--) {
  array_push($months, date('F Y', strtotime("-$i month", $first)));
}
$month_display = '';
$month_cur_year = '';
$month_prev_year = '';
foreach($months as $row){
	$month_display .= '"'.date('F', strtotime($row)).'",';
	$month_cur_year .= '"'.\Libs\SiteStats::getCurrentMonth($row, 'thisYear').'",';
	$month_prev_year .= '"'.\Libs\SiteStats::getCurrentMonth($row, 'lastYear').'",';
}
$month_display1 = rtrim($month_display,'",');
$month_display2 = substr($month_display1, 1);
$month_cur_year = rtrim($month_cur_year,'",');
$month_cur_year = substr($month_cur_year, 1);
$month_prev_year = rtrim($month_prev_year,'",');
$month_prev_year = substr($month_prev_year, 1);
?>

<script type="text/javascript">
var lineChartData = {
		labels : ["<?php echo $month_display2;?>"],
		datasets : [
			{
				label: "Page Views Current Year",
				fillColor : "rgba(48, 164, 255, 0.2)",
				strokeColor : "rgba(48, 164, 255, 1)",
				pointColor : "rgba(48, 164, 255, 1)",
				pointStrokeColor : "#fff",
				pointHighlightFill : "#fff",
				pointHighlightStroke : "rgba(48, 164, 255, 1)",
				data : ["<?php echo $month_cur_year;?>"]
			},
			{
				label: "Page Views Previous Year",
				fillColor : "rgba(220,220,220,0.2)",
				strokeColor : "rgba(220,220,220,1)",
				pointColor : "rgba(220,220,220,1)",
				pointStrokeColor : "#fff",
				pointHighlightFill : "#fff",
				pointHighlightStroke : "rgba(220,220,220,1)",
				data : ["<?php echo $month_prev_year;?>"]
			}
		]

	}

	window.onload = function(){
		var chart1 = document.getElementById("line-chart").getContext("2d");
		window.myLine = new Chart(chart1).Line(lineChartData, {
			responsive: true
		});
	};
</script>
