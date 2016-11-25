![UserApplePie](http://uap3demo.userapplepie.com/uap3logolg.gif)

---

# UAP Version 4.0.0

[![Software License](http://img.shields.io/badge/License-BSD--3-brightgreen.svg)](LICENSE)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/UserApplePie/UAP-MVC-CMS-3/master/license.txt)

[![Join the chat at https://gitter.im/User-Apple-Pie/Lobby](https://img.shields.io/gitter/room/nwjs/nw.js.svg)](https://gitter.im/User-Apple-Pie/Lobby?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

---

## What is UserApplePie v4?

UserApplePie is a PHP 5.5+ CMS MVC Framework. It's designed to be lightweight and modular, allowing developers to build better and easy to maintain code with PHP.

---

## Documentation

Full docs & tutorials are available at [userapplepie.com](http://www.userapplepie.com/)

---

## Demo Website

Check out the demo website at [uap4demo.userapplepie.com](http://uap4demo.userapplepie.com/)

---

## Requirements

The UAP v4 requirements are limited.

- Apache Web Server or equivalent with mod rewrite support.
- IIS with URL Rewrite module installed - [http://www.iis.net/downloads/microsoft/url-rewrite](http://www.iis.net/downloads/microsoft/url-rewrite)
- PHP 5.5 or greater is required
- fileinfo enabled (edit php.ini and uncomment php_fileinfo.dll or use php selector within cpanel if available.)
- mySQL Database Server or equivalent

---

# Recommended way to install

UserApplePie is on packagist [https://packagist.org/packages/userapplepie/uap-user-management](https://packagist.org/packages/userapplepie/uap-user-management)

Install from terminal now by using:

```
composer create-project userapplepie/uap-user-management foldername v4.0.0-beta
```

The foldername is the desired folder to be created.

Once installed on your server, open the site, and it will display an install script.

---

# Install Manually

Option 1 - files above document root:

* place the contents of public into your public folder (.htaccess and index.php)
* navigate to your project in terminal and type composer install to initiate the composer install.
* edit public/.htaccess set the rewritebase if running on a sub folder otherwise a single / will do.
* edit app/Config.example.php change the SITEURL and DIR constants. the DIR path this is relative to the project url for example / for on the root or /foldername/ when in a folder. Also change other options as desired. Rename file as Config.php
* Import the database.sql to your database (Updated table PREFIX if changed in Config.php).
* Enjoy!

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
* Import the database.sql to your database (Updated table PREFIX if changed in Config.php).
* Enjoy!

---

##Setting up a VirtualHost (Optional but recommended)

Navigate to:
````
<path to your xampp installation>\apache\conf\extra\httpd-vhosts.conf
````

and uncomment:

````
NameVirtualHost *:80
````

Then add your VirtualHost to the same file at the bottom:

````
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot "C:\xampp\htdocs\testproject\public"
    ServerName testproject.dev

    <Directory "C:\xampp\htdocs\testproject\public">
        Options Indexes FollowSymLinks Includes ExecCGI
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
````

Finally, find your hosts file and add:

````
127.0.0.1       testproject.dev
````

You should then have a virtual host set up, and in your web browser, you can navigate to testproject.dev to see what you are working on.

---
