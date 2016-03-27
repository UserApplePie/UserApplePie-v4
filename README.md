![Nova Framework](http://uap3demo.userapplepie.com/uap3logolg.gif)

# UAP Version 3.0

[![Software License](http://img.shields.io/badge/License-BSD--3-brightgreen.svg)](LICENSE)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/UserApplePie/UAP-MVC-CMS-3/license.txt)

[![Join the chat at https://gitter.im/UserApplePie/UAP-MVC-CMS-3](https://img.shields.io/gitter/room/nwjs/nw.js.svg)](https://gitter.im/UserApplePie/UAP-MVC-CMS-3?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

## What is UserApplePie v3?

UserApplePie is a PHP 5.5 MVC CMS system based on Nova Framework. It's designed to be lightweight and modular, allowing developers to build better and easy to maintain code with PHP.

The base framework comes with a range of [helper classes](https://github.com/nova-framework/framework/tree/master/app/Helpers).

## Documentation

Full docs & tutorials are available at [userapplepie.com](http://www.userapplepie.com/).

## Requirements

The UAP v3 requirements are limited.

- Apache Web Server or equivalent with mod rewrite support.
- IIS with URL Rewrite module installed - [http://www.iis.net/downloads/microsoft/url-rewrite](http://www.iis.net/downloads/microsoft/url-rewrite)
- PHP 5.5 or greater is required
- fileinfo enabled (edit php.ini and uncomment php_fileinfo.dll or use php selector within cpanel if available.)
- mySQL Database Server or equivalent

# Install

Option 1 - files above document root:

* place the contents of public into your public folder (.htaccess and index.php)
* navigate to your project in terminal and type composer install to initiate the composer install.
* edit public/.htaccess set the rewritebase if running on a sub folder otherwise a single / will do.
* edit app/Config.example.php change the SITEURL and DIR constants. the DIR path this is relative to the project url for example / for on the root or /foldername/ when in a folder. Also change other options as desired. Rename file as Config.php

Option 2 - everything inside your public folder

* place all files inside your public folder
* navigate to the public folder in terminal and type composer install to initiate the composer install.
* open index.php and change the paths from using DIR to FILE:

````
define('APPDIR', realpath(__DIR__.'/app/').'/');
define('SYSTEMDIR', realpath(__DIR__.'/system/').'/');
define('PUBLICDIR', realpath(__DIR__).'/');
define('ROOTDIR', realpath(__DIR__).'/');
````

* edit .htaccess set the rewritebase if running on a sub folder otherwise a single / will do.
* edit system/Core/Config.example.php change the SITEURL and DIR constants. the DIR path this is relative to the project url for example / for on the root or /foldername/ when in a folder. Also change other options as desired. Rename file as Config.php
