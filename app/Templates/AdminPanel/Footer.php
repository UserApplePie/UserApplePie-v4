<?php
/**
* Admin Panel Footer
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Assets, Libs\Url;
?>
	</div>
</div>

<?php
if(isset($data['ownjs'])){
	foreach($data['ownjs'] as $ownjs){
		echo $ownjs;
	}
}
?>
<?=Assets::js([
		SITE_URL.'Templates/AdminPanel/Assets/js/jquery.min.js',
		SITE_URL.'Templates/AdminPanel/Assets/js/bootstrap.bundle.min.js',
		SITE_URL.'Templates/AdminPanel/Assets/js/jquery.easing.min.js',
		SITE_URL.'Templates/AdminPanel/Assets/js/sb-admin.min.js',
		SITE_URL.'Templates/AdminPanel/Assets/js/lumino.glyphs.js',
		SITE_URL.'Templates/AdminPanel/Assets/js/chart.min.js',
		'https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/js/bootstrap4-toggle.min.js',
		'https://use.fontawesome.com/releases/v5.9.0/js/all.js',
]);
?>
<script>
	$(document).ready(function(){
		$('[data-toggle="popover"]').popover();
	});
</script>
</body>
</html>
