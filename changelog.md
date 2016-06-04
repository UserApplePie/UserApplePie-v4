UserApplePie Change Log

==========================================================================
					UAP v3.0.3
--------------------------------------------------------------------------
- Added Mass Email Function to Admin Panel
- Added Pagination to Members page
- Updated User Profiles to fix a couple bugs
- Updated how Auth system displays errors
- Added Privacy Settings to User Account Settings
- Fix style in forgot password section
- Updated CSRF token strength
- Check github for list of files updated in this release
	-https://github.com/UserApplePie/UserApplePie-v3/compare/3.0.2...master
==========================================================================

==========================================================================
					UAP v3.0.2
--------------------------------------------------------------------------
- Added Prettify for better code snippets display in forums plugin
- Updated Composer file so UAP can be installed via composer
- Fixed default profile images location for registration 
- Updated BBCode Helper to use Prettify for code snippets and Youtube fix
- Removed files Composer installs to lighten package size
- Updated Config file to be more friendly
- Updated how profile images are uploaded and viewed
- Added Install script
- Updated how unnamed groups are displayed in AdminPanel
- Updated how last login is displayed if never logged in within user profiles
- Updated database users table with Default Null settings
- Added mode rewrite check in .htaccess file
- Files Edited Since Previous Version:
	- app/Controllers/Members.php
	- app/Views/AdminPanel/Groups.php
	- app/Views/Members/Edit-Profile.php
	- app/Views/Members/View-Profile.php
	- app/Config.example.php
	- app/Templates/Default/footer.php
	- app/Templates/Default/header.php
	- app/Install/....
	- public/index.php
	- public/.htaccess
	- system/Helpers/Auth/Auth.php
	- system/Helpers/BBCode.php
	- system/Helpers/PageViews.php 
	- database.sql
	- composer.json
	- vendor/...
==========================================================================

==========================================================================
					UAP v3.0.1
--------------------------------------------------------------------------
- Updated Admin Panel Dashboard to Show More Information
- Added Last name to user profiles
- Fixed Image Uploads allowed file types for user profiles
- Files Edited Since Previous Version:
	- app/Controllers/AdminPanel.php
	- app/Controllers/Members.php
	- app/Models/AdminPanel.php
	- app/Models/Members.php
	- app/Models/Users.php
	- app/Templates/Default/header.php
	- app/Views/AdminPanel/AdminPanel.php
	- app/Views/Members/Edit-Profile.php
	- app/Views/Members/View-Profile.php
	- database.sql
==========================================================================