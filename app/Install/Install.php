<?php

/**
* UAP 3 Install Script
* @author DaVaR davar@userapplepie.com
**/

/** Include the Config.example.php file to get current settings **/
if (file_exists(ROOTDIR.'app/Config.example.php')) {
	require ROOTDIR.'app/Config.example.php';
	new \App\Config();
}

/** Include the Install Header **/
require APPDIR.'Install/Header.php';

/** Check to make sure system is not already installed **/
if (!file_exists(APPDIR.'Config.php')) {
	/** Make Sure All The Steps Needed for Install are on server **/
	if (file_exists(APPDIR.'Install/Step1.php')) {
		/** Check to see if an install step is set **/
		if (!isset($_GET['install_step'])){
			/** No Install Step set, display step1 **/
			require APPDIR.'Install/Step1.php';
		}else{
			/** Check to see what step user is on **/
		  if($_GET['install_step'] == "2"){
				/** Display step2 **/
				require APPDIR.'Install/Step2.php';
			}else if($_GET['install_step'] == "3"){
				/** Display step3 **/
				require APPDIR.'Install/Step3.php';
			}else if($_GET['install_step'] == "4"){
				/** Display step4 **/
				require APPDIR.'Install/Step4.php';
			}else if($_GET['install_step'] == "5"){
				/** Display step5 **/
				require APPDIR.'Install/Step5.php';
			}
		}
	}
}else{
	echo "UAP 3 Is Installed.  Remove Install Directory!";
}

/** Include the Install Footer **/
require APPDIR.'Install/Footer.php';
