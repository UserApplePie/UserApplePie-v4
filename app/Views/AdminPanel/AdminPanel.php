<div class="col-lg-8 col-md-8 col-sm-8">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1><?=$title;?></h1>
		</div>
		<div class="panel-body">
			<p><?=$welcomeMessage;?></p>

				<div class='row'>

		      <div class='col-lg-6 col-md-6'>
		        <div class='panel panel-primary'>
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

					<div class='col-lg-6 col-md-6'>
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
							<a href='<?=DIR?>AdminPanel-Users'>
								<div class='panel-footer'>
									<span class='pull-left'>View Details</span>
									<span class='pull-right'><i class='glyphicon glyphicon-user'></i></span>
									<div class='clearfix'></div>
								</div>
							</a>
						</div>
					</div>

				</div>

		</div>
	</div>
</div>
