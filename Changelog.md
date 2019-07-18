----------------------------------------------------------------------------------------
UserApplePie Change Log
https://www.userapplepie.com
----------------------------------------------------------------------------------------
UAP v4.2.1 to v4.3.0
----------------------------------------------------------------------------------------
### Images
 - Added Lightbox to fix the styles for image pop-ups
### Messages Plugin
 - Fixed issue where mass email message would not delete after users reads
### Comments Plugin
 - Added Commments Plugin to display all comments per post
### SiteMap
 - Added auto site map generator
 - Set to only work if page is public and enabled for sitemap
### PageFunctions
 - Added Online Bubble that can be displayed next to user names
 - Added Admin Enable and Disable option to Admin Panel
### Pages Permissions
 - Added a Pages Permissions page to the Admin Panel
 - Set site to check all page permissions before letting user continue
### House Cleaning
 - Cleaned up the code where needed.
### Security Fix
 - Fixed where only Administrator can see the Admin Panel.  
### Site Links
 - Added permission settings to each link.
### Comments Library
 - Added new comments Library
 - Setup with status updates
 - Setup enable user to edit and delete their comments
 - Setup Media Elements to look clean and readable.
### Language
 - Added misc lang data
### Header
 - Added Site Wide Message to the header to show if set
 - Added a check to let user know if profile is still default
### Auth
 - Fixed styles in auth pages to match rest of site
### Sweets
 - Fixed bug with Sweets that would cause bad redirects
 - Made more strict to better match with content it was clicked in
### Missing Lang Logger
 - Logs any missing language fields so that they can be added to lang files.
### Styles
 - Added loader gif for Admin Panel and install pages
 - Updated styles on many pages to look better on computer screens.
### Install
 - Updated Example-Config.php title in install files (visual)
### System Fixes
 - Setup a auto add member to group if not a member of any
### Friends
 - Added email notification for new friend requests
### Updated Home controller
 - Set to show Recent Friends Activity if logged in
### Added Admin Panel Database Upgrade
 - Checks to see if site version matches database version
 - Runs update script based on current version
### Added Recent Friends Activity
 - If Forum and Friends Plugins are installed show recents when logged in
 - Added Friends sidebar with friends list and suggested friends
 - Added Fourm sidebar
 - Added Status Update feature.  Users can post and edit with BBCode
 - Added Profile images to recents.  Grouped multi uploads if within 10 min.
### Updated User Profile
 - Added input group styling
 - Added image max size option in Admin Settings
 - Added Forum signature to view profile
 - Added ability to upload multiple photos to user profiles
 - Added ability for user to set default and delete photos
 - Added Mutual Friends list
 - Added Privacy Settings
 - Added Location
 - Updated Account home page
 - Added Forum Recent Post list based on user's id
### Updated Forum Plugin
 - Added Feature to Forum that auto generates url based on topic title
 - Added Auto Save Draft feature to new topics and replies with auto recover.
 - Added Image Max Size option to Admin Settings
 - Added BBcode buttons for text areas
 - Fixed issues when users try to post php code to the forum
 - Added ability to upload multiple photos to Topics and Replies
 - Added Admin pubish and delete page for unpublished posts
 - Limited recent posts display to published posts
### Registration Bot Fix
 - Added a simple hidden empty text field to the form
 - Set site to block registration if field has data
 - Updated for more strict email check
### Admin panel
 - Updated admin panel to not show adds
 - Fixed a bug in the settings that created duplicate entries
 - Added Site Wide Message in the settings
 - Moved Email settings to it's own page
 - Set auto router to add link to site links
 - Added top referer stats to dashboard
### User Auth
 - Updated user auth pages to not show adds
### Mass Email
 - Updated to only send emails to active users
### Database Library
 - Added updateWhereNot function that got lost somehow
### Site adds
 - Added site adds feature to the site and admin panel  
 - When enabled added code to detect in the Load system controller
### reCAPTCHA
 - Updated reCAPTCHA to be more strict
 - Removed test keys since it now requires domain match
 - Setup where if user leaves keys blank the site will not load reCAPTCHA
### Database
 - Updated database to check if table exist during install
 - Added Image Max Size setting to Site Settings and Forum Settings
 - Added Indexes to a couple tables to help speed things up

----------------------------------------------------------------------------------------
UAP v4.2.0 to v4.2.1
----------------------------------------------------------------------------------------
### Updated Forum Plugin
 - Moved settings from Config.php to Admin Panel
 - Fixed bug with forum tracking
### Updated Admin Panel
 - Moved settings from Config.php to Advanced Settings in Admin Panel
 - Add Users Auth Log Display
### Registration
 - Added Site Invitation Code
### Global Variables
 - Moved all defined variables to site settings so they can be stored in database
### Advertisement Settings
 - Added Advertisement settings where admin can setup automatic adds on their website
### Demo Site Settings
 - Added setting to disable admin editing in the Admin Panel when site is in demo mode
### Bootswatch Theme Update
 - Updated to Bootswatch 4.2.1
### Bug Fixes
 - Minor language fixes

----------------------------------------------------------------------------------------
UAP v4.0.1 to v4.2.0
----------------------------------------------------------------------------------------
### Updated Bootstrap from v3 to v4
 - Updated style sheets and java request urls
 - Updated from old style formats to new (All View and Template files updated)
 - Added usage of FontAwesome for icons
 - Fixed bug with site version checker
 - Added French language pack (EddyBeaupre)
 - Added Theme Change Option in Admin Settings.
### Updated to work with PHP 7.2
 - Properly changed use of most count() functions.

----------------------------------------------------------------------------------------
UAP v4.0.0 to v4.0.1
----------------------------------------------------------------------------------------
### Updated Forum Plugin
 - Added forum group permission settings.
 - Updated forum group settings in admin panel.
 - Added user permission detail box to forum pages.
 - Updated database file to include forum group settings.
### Admin Panel Site Settings
 - Changed max length for site title, keywords, and description to 255 char.
### Updated Assets Library
 - Setup so that site can load files within another folder in the Assets dir.
### User Profiles
 - Fixed a bug where default images were getting deleted.
----------------------------------------------------------------------------------------
