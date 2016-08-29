<?php use App\System\Libraries\Assets; ?>

                <div class='col-lg-12 col-md-12 col-sm-12'>
                    <!-- Footer (sticky) -->
                    <footer class='navbar navbar-default'>
                        <div class='container'>
                            <div class='navbar-text'>

                                <!-- Footer links / text -->
                                <a href='http://www.userapplepie.com' title='View UserApplePie Website' ALT='UserApplePie' target='_blank'>UserApplePie v3</a>

                                <!-- Display Copywrite stuff with auto year -->
                                <Br> &copy; <?php echo date("Y") ?> <?php echo SITE_TITLE;?>.

                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div><!-- /.container -->

        <?=Assets::js([
            'https://code.jquery.com/jquery-1.12.1.min.js',
            'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js',
            'https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js'
        ])?>
        <?=(isset($js)) ? $js : ""?>
        <?=(isset($footer)) ? $footer : ""?>
    </body>
</html>
