----------------------------------------------------------------------------------------
UserApplePie Change Log
https://www.userapplepie.com
----------------------------------------------------------------------------------------

----------------------------------------------------------------------------------------
UAP v4.2.1 to v4.2.2
----------------------------------------------------------------------------------------
Updated Forum Plugin
 - Added BBcode buttons for text areas
 - Fixed issues when users try to post php code to the forum
Registration Bot Fix
 - Added a simple hidden empty text field to the form
 - Set site to block registration if field has data
 - Updated for more strict email check
Admin panel
 - Updated admin panel to not show adds
User Auth
 - Updated user auth pages to not show adds
Mass Email
 - Updated to only send emails to active users
Database Library
 - Added updateWhereNot function that got lost somehow
Site adds
 - Added site adds feature to the site and admin panel  
 - When enabled added code to detect in the Load system controller
reCAPTCHA
 - Updated reCAPTCHA to be more strict
 - Removed test keys since it now requires domain match
 - Setup where if user leaves keys blank the site will not load reCAPTCHA
Database
 - Updated database to check if table exist during install

----------------------------------------------------------------------------------------
UAP v4.2.0 to v4.2.1
----------------------------------------------------------------------------------------
Updated Forum Plugin
 - Moved settings from Config.php to Admin Panel
 - Fixed bug with forum tracking
Updated Admin Panel
 - Moved settings from Config.php to Advanced Settings in Admin Panel
 - Add Users Auth Log Display
Registration
 - Added Site Invitation Code
Global Variables
 - Moved all defined variables to site settings so they can be stored in database
Advertisement Settings
 - Added Advertisement settings where admin can setup automatic adds on their website
Demo Site Settings
 - Added setting to disable admin editing in the Admin Panel when site is in demo mode
Bootswatch Theme Update
 - Updated to Bootswatch 4.2.1
Bug Fixes
 - Minor language fixes

----------------------------------------------------------------------------------------
UAP v4.0.1 to v4.2.0
----------------------------------------------------------------------------------------
Updated Bootstrap from v3 to v4
 - Updated style sheets and java request urls
 - Updated from old style formats to new (All View and Template files updated)
 - Added usage of FontAwesome for icons
 - Fixed bug with site version checker
 - Added French language pack (EddyBeaupre)
 - Added Theme Change Option in Admin Settings.
Updated to work with PHP 7.2
 - Properly changed use of most count() functions.
----------------------------------------------------------------------------------------

----------------------------------------------------------------------------------------
UAP v4.0.0 to v4.0.1
----------------------------------------------------------------------------------------
Updated Forum Plugin
 - Added forum group permission settings.
 - Updated forum group settings in admin panel.
 - Added user permission detail box to forum pages.
 - Updated database file to include forum group settings.
Admin Panel Site Settings
 - Changed max length for site title, keywords, and description to 255 char.
Updated Assets Library
 - Setup so that site can load files within another folder in the Assets dir.
User Profiles
 - Fixed a bug where default images were getting deleted.
----------------------------------------------------------------------------------------
