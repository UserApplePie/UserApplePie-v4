<?php
/**
* Admin Panel Footer
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.0
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
		SITE_URL.'Templates/AdminPanel/Assets/js/chart.min.js'
]);
?>

</body>
</html>
