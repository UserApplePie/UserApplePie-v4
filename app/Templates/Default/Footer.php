<?php
/**
* Default Footer
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Assets, Libs\Language;
?>
</div>
</div><!-- /.container -->

                    <!-- Footer (sticky) -->
                    <footer class='footer'>
                        <div class='container'>
                            <span class='text-muted'>

                                <!-- Footer links / text -->
                    						<?=Language::show('uap_poweredby', 'Welcome');?> <a href='https://www.userapplepie.com' title='View UserApplePie Website' ALT='UserApplePie' target='_blank'>UserApplePie v4</a>
                                 |
                    						<!-- Display Copywrite stuff with auto year -->
                    						 &copy; <?php echo date("Y") ?> <?php echo SITE_TITLE;?> <?=Language::show('uap_all_rights', 'Welcome');?>.
                                 |
                    							<?php
                    								/** Get List of all enabled Languages **/
                    								$languages = \Libs\Language::getlangs();
                    								$cur_lang_code = \Libs\Language::setLang();
                    								foreach ($languages as $code => $info) {
                    									/** List Lang Links **/
                    									if($cur_lang_code == $code){
                    										echo "<strong>".$info['name']."</strong> ";
                    									}else{
                    										echo "<a href='".DIR."ChangeLang/".$code."' title='".$info['info']."'>".$info['name']."</a> ";
                    									}
                    								}
                    							?>

                            </div>
                        </div>
                    </footer>

        <?=Assets::js([
            'https://code.jquery.com/jquery-3.2.1.slim.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js',
            'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js',
            'https://use.fontawesome.com/releases/v5.9.0/js/all.js'
        ])?>
        <?=(isset($js)) ? $js : ""?>
        <?php if(isset($ownjs)){ foreach ($ownjs as $eachownjs) { echo "$eachownjs"; } } ?>
        <?=(isset($footer)) ? $footer : ""?>

        <script>
          $(document).ready(function(){
            $('[data-toggle="popover"]').popover();
          });
        </script>
    </body>
</html>
