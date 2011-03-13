=== LATEST NEWS AND INFORMATION ===

Current Version: 0.1
Author: PressBuddies
URL: http://geopress.my
License: GNU3

=== DESCRIPTION ===

GeoPress is the world's first entirely OpenSource Geo-Tagging Platform....

Install GeoPress on your own server, create maps, places and start checking-in. You can create both private and public places, leave full HTML content for both checkins and place descriptions.

However, please note that at present, GeoPress is in an EXTREMELY alpha state. It has been released now to the public not only as a way to demonstrate what is possible with BackPress - http://backpress.org - but to more importantly get people to start thinking about the importance of such a platform.

With information as sensitive as geo-location, it is essential for there to be an entirely OpenSource platform that people can host themselves, allowing other networks to use the information they decide, rather than letting other people own such personal information.

The future of GeoPress is one where people can create the own geo-social role-playing worlds, challenges and adventures, whilst also acting as a way for people to simply more easily create, manage and share maps and other geo-relevant information.

As the GeoPress continues to grow and reach closer to version 1.0, we will see a number of exciting new features and possibilities open-up. With themes, plugins and other ways to customise the experience, GeoPress has been designed specifically for geo-tagging and will at all times strive to be as mobile and cross-device compatible as it can, but again, at this point, for version 0.1, we have cut a few corners and missed out on hundreds of features we wanted to include, but time is of the essence, and want people to take a look and start providing feedback, for without the support of the OpenSource community, this project may never see the light...


=== REQUIREMENTS ===
* PHP 5.2+
* MySQL 4+


=== INSTALLATION ===

* Checkout the source from GitHub: git@github.com:msmalley/GeoPress.git
* Visit https://github.com/msmalley/GeoPress to download source as ZIP
* Copy gp-config-sample.php to gp-config.php and edit the information inside
* Visit the URL where you put the files and GeoPress will be installed in a second
* Don't forget to create the .htaccess file, mentioned by the installer script


=== SUPPORT ===
* Limited Support is Available via http://geopress.my


=== UPGRADING ===

* Run GIT in your local checkout directory
* Or download directly from - http://geopress.my/downloads/
* Visit <geopress-root>/install.php?action=upgrade


=== CHANGELOG ===

== 0.1 - 13/MARCH/2011 ==
* DB SCHEMA for Maps, Places and Checkins
* PERMALINKS for Maps, Places and Checkins
* FORMS AND VIEWS for Maps, Places and Checkins
* WYSIWYG Text-Areas for Descriptions
* LESS.CSS(JS) FOR UI CSS FRAMEWORK
* Private Maps and Private Places
* Display Settings per Map
* Show Either Places or Checkins
* Markers on Maps Link to Places or Checkins
* Checkin Markers Link to Places
* Place Pages Contain Checkin Links
* ADDED Checkin Links to Places.php
* ADDED Display Types for Maps (Checkins or Places)
* ADDED 2 MAPS AT INSTALL (Checkins and Places)
* ADDED FLUID CSS SUPPORT UP-TO MINIMUM OF 300PX WIDE
* 3/2/1 COLUMN LAYOUT AUTOMATICALLY ADJUSTED
* Pick Your Own Username / Password at Sign-Up

=== ROADMAP ===

== PRIOR to 0.2 ==
* Improved / Consolidated Header Functions
* Proper Utilization of Head.js (used only for CSS classes in 0.1)
* All Complex VAR Functions should be ARG Based
* ADD NEW PLACE button / function for Checkin pages...
* AJAX LOAD MORE (Default 10) on ALL LISTS
* Replace Geo-Location Methods with New W3C Standards
* FULLY HTML 5 / CROSS-BROWSER / CROSS-RESOLUTION / CROSS-DEVICE
* Increased Font-Size for Smaller (Mobile) Screens

== PRIOR to 0.3 ==
* geoRSS Support
* Dashboard / Homepage for Default Theme
* Provide Options Page
* New Markers and InfoWindows
* Options for displaying content in markers or outside...
* Remove Logo (and replace with site name) from Front-End of Theme

== PRIOR to 0.4 ==
* Add Tracks and Trails (connecting checkins)
* Allow for User / Friend Management
* Replace Lingo references to "I" with Username

== PRIOR to 0.5 ==
* Threaded Commenting

== PRIOR to 0.6 ==
* Customisable Modes of Transport
* Customisable Lingo

== PRIOR to 0.7 ==
* Working Theme System

== PRIOR to 0.8 ==
* Working Plugin System

== PRIOR to 0.9 ==
* Import / Export Data

== PRIOR to 1.0 ==
* Third-Party Data / Visual Integration
* Allow for Other Map Selections
* Provide Online Hosted Version