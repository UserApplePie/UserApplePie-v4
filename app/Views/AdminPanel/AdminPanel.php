<div class="col-lg-8 col-md-8 col-sm-8">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1><?=$title;?></h1>
		</div>
		<div class="panel-body">
			<p><?=$welcomeMessage;?></p>

				<div class='row'>

		      <div class='col-lg-4 col-md-4'>
		        <div class='panel panel-info'>
		          <div class='panel-heading'>
		            <div class='row'>
									<div class='col-xs-3'>
										<i class='glyphicon glyphicon-user' style='font-size:38px'></i>
									</div>
									<div class='col-xs-9 text-right'>
										<div class='huge'><?=$activatedAccounts?></div>
										<div>Site Members!</div>
									</div>
		            </div>
		          </div>
		 					<a href='<?=DIR?>AdminPanel-Users'>
								<div class='panel-footer'>
			          	<span class='pull-left'>View Details</span>
			            <span class='pull-right'><i class='glyphicon glyphicon-user'></i></span>
			            <div class='clearfix'></div>
			          </div>
		          </a>
		        </div>
		      </div>

					<div class='col-lg-4 col-md-4'>
						<div class='panel panel-warning'>
							<div class='panel-heading'>
								<div class='row'>
									<div class='col-xs-3'>
										<i class='glyphicon glyphicon-user' style='font-size:38px'></i>
									</div>
									<div class='col-xs-9 text-right'>
										<div class='huge'><?=$usergroups?></div>
										<div>User Groups!</div>
									</div>
								</div>
							</div>
							<a href='<?=DIR?>AdminPanel-Groups'>
								<div class='panel-footer'>
									<span class='pull-left'>View Details</span>
									<span class='pull-right'><i class='glyphicon glyphicon-user'></i></span>
									<div class='clearfix'></div>
								</div>
							</a>
						</div>
					</div>

					<div class='col-lg-4 col-md-4'>
						<div class='panel panel-success'>
							<div class='panel-heading'>
								<div class='row'>
									<div class='col-xs-3'>
										<i class='glyphicon glyphicon-user' style='font-size:38px'></i>
									</div>
									<div class='col-xs-9 text-right'>
										<div class='huge'><?=$onlineAccounts?></div>
										<div>Online Members!</div>
									</div>
								</div>
							</div>
							<a href='<?=DIR?>Online-Members'>
								<div class='panel-footer'>
									<span class='pull-left'>View Details</span>
									<span class='pull-right'><i class='glyphicon glyphicon-user'></i></span>
									<div class='clearfix'></div>
								</div>
							</a>
						</div>
					</div>

					<div class='col-lg-6 col-md-6'>
						<div class='panel panel-info'>
							<div class='panel-heading'>
								Users Signed Up Stats
							</div>
							<ul class='list-group'>
									<li class='list-group-item'><span class='pull-left'>Past Day:</span><span class='pull-right'><?=$mem_signup_past_1?></span><div class='clearfix'></div></li>
									<li class='list-group-item'><span class='pull-left'>Past Week:</span><span class='pull-right'><?=$mem_signup_past_7?></span><div class='clearfix'></div></li>
									<li class='list-group-item'><span class='pull-left'>Past 30 Days:</span><span class='pull-right'><?=$mem_signup_past_30?></span><div class='clearfix'></div></li>
									<li class='list-group-item'><span class='pull-left'>Past 90 Days:</span><span class='pull-right'><?=$mem_signup_past_90?></span><div class='clearfix'></div></li>
									<li class='list-group-item'><span class='pull-left'>Past Year:</span><span class='pull-right'><?=$mem_signup_past_365?></span><div class='clearfix'></div></li>
							</ul>
						</div>
					</div>

					<div class='col-lg-6 col-md-6'>
						<div class='panel panel-warning'>
							<div class='panel-heading'>
								Users Logged In Stats
							</div>
							<ul class='list-group'>
									<li class='list-group-item'><span class='pull-left'>Past Day:</span><span class='pull-right'><?=$mem_login_past_1?></span><div class='clearfix'></div></li>
									<li class='list-group-item'><span class='pull-left'>Past Week:</span><span class='pull-right'><?=$mem_login_past_7?></span><div class='clearfix'></div></li>
									<li class='list-group-item'><span class='pull-left'>Past 30 Days:</span><span class='pull-right'><?=$mem_login_past_30?></span><div class='clearfix'></div></li>
									<li class='list-group-item'><span class='pull-left'>Past 90 Days:</span><span class='pull-right'><?=$mem_login_past_90?></span><div class='clearfix'></div></li>
									<li class='list-group-item'><span class='pull-left'>Past Year:</span><span class='pull-right'><?=$mem_login_past_365?></span><div class='clearfix'></div></li>
							</ul>
						</div>
					</div>

					<div class='col-lg-12 col-md-12'>
						<div class='panel panel-success'>
							<div class='panel-heading'>
								Installed Plugins
							</div>
							<ul class='list-group'>
									<li class='list-group-item'><span class='pull-left'>Forum Plugin:</span><span class='pull-right'><?=$apd_plugin_forum?></span><div class='clearfix'></div></li>
									<li class='list-group-item'><span class='pull-left'>Private Messages Plugin:</span><span class='pull-right'><?=$apd_plugin_message?></span><div class='clearfix'></div></li>
							</ul>
						</div>
					</div>

				</div>
		</div>
	</div>
</div>
