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
	'https://code.jquery.com/jquery-1.12.1.min.js',
    'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js',
		'https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js',
		SITE_URL.'Templates/AdminPanel/Assets/js/lumino.glyphs.js',
		SITE_URL.'Templates/AdminPanel/Assets/js/chart.min.js',
]);
?>

</body>
</html>
