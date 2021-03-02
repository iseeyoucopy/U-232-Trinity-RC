# U-232-Trinity-RC

    This version is an Release Candidate for development and testing

# Basic Information

    Code Base: U-232 
    Powered By: U-232 Team
    Using: CSS3, HTML5, Javascript, PHP, Mysql,Memcached,Redis, Couchbase, APC(u) & C++(XBT Tracker)  
    Test Site: https://u-232.duckdns.org 
    Support forum: https://u-232-forum.duckdns.org

# Requires the following

    PHP 7.4 or higher 
    PHP-CURL 
    PHP-IGBINARY 
    PHP-JSON 
    PHP-MEMCACHED
    PHP-REDIS
    PHP-MSGPACK 
    PHP-MYSQLI 
    PHP-MBSTRING 
    PHP-GD 
    PHP-GEOIP 
    PHP-OPCACHE 
    PHP-XML 
    PHP-ZIP 
    LIBAPACHE2-MOD-PHP Apache/2.4.38 or higher 
    MariaDB 10.5.7 or higher

# Instructions for installing php

      #For
      sudo apt update

"Side note" temporary make sure your strict mode is off " set global sql_mode=''; " I will update the code to work even in strict mode

# Instructions for XBT

      http://www.visigod.com/xbt-tracker
      http://code.google.com/p/xbt/

      High-performance BitTorrent Tracker

        Installing under Linux

          The following commands can be used to install the dependencies on Debian and Ubuntu. The g++ version should be at least 7.

               apt install cmake default-libmysqlclient-dev g++ git libboost-dev libsystemd-dev make zlib1g-dev

          The following commands can be used to install some of the dependencies on CentOS, Fedora and Red Hat. The g++ version should be at least 7.

              yum install boost-devel cmake gcc-c++ git make mysql-devel systemd-devel

        Enter the following commands in a terminal. Be patient while g++ is running, it'll take a few minutes.

        git clone https://github.com/OlafvdSpek/xbt 
        cd xbt/Tracker 
        cmake . 
        make 
        cp xbt_tracker.conf.default xbt_tracker.conf

        Remember to add your mysql connect details to xbt_tracker.conf

        If no errors occurred during install then to start XBT tracker run ./xbt_tracker

        To stop XBT run killall xbt_tracker

"Please note" If running the upgrade sql then you need to check a few points;

First, go to your phpmyadmin and check what the last id is on cleanup_manager and edit the upgrade.sql inserts accordinally.

Second, check the staffpanel last id and edit the upgrade.sql inserts accordinally.

Third, userclasses in class_config table are default 0-6 so if you have different classes then edit that table first adding the new classes and values
also edit cache/class_config.php.

Fourth, once your upgrade is completed with new code in place simply edit your announce urls in all seeding torrents and change announce.php?passkey=
to announce.php?torrent_pass= and all torrents should resume as normal, same applys if switching to XBT_TRACKER, you would use the XBT_TRACKER format
for announce url in client.

# Setup to Automate HTML and SaSS changes using GULP4 and BrowserSync

       first make sure you have NodeJs intalled on server
       
       apt install nodejs  on Debian and Ubuntu
       
       CentOS
       yum install -y gcc-c++ make
       curl -sL https://rpm.nodesource.com/setup_14.x | sudo -E bash -
       sudo yum install nodejs
       node -v 
       npm -v
       
        To Run the Project
        
        cd /to/your/webroot/directory
        npm install

        Finally, run
        
        gulp 
        
        to run the Sass compiler. It will re-run every time you save a Sass file.

# Configure

Open the upgrade.sql and update your database adding all additional entrys, once done backup cache/staff_settings.php also cache/staff_settings2.php,
backup include/ann_config.php and include/config.php, then delete all the old v4 files from ftp except pic folder, torrents, then upload the v5 code
onto your server except the install folder, when prompted at pic folder hit skip and it will only add the newer files depening on ftp client. Open
your ann_config.php and config.php files then open the install/extra/ann_config.sample.php and config.sample.php, transfer all your config settings to
the newer files then save and rename them removing.sample out the file name, then transfer them into include folder, ensure you chmod any new cache
files added also.

"Please note" Before you begin installation it's very important to configured your server correctly, including code dependencies.

Ensure your error reporting is enabled on the server and you are logging the errors and not displaying them. A error on install is a failure to adhere
to setup instructions. If you experience a failure then a properly configured server will report that issue, no excuses required. Install memcached
redis apcu and zend opcode cache before installing U-232 Trinity.

Create a directory one up from root so it resides beside it not inside it, named bucket. Then inside the bucket folder make another and name it
avatar, remember to chmod them to 777. If you use your own names for those folders then you need to edit bitbucket.php and img.php defines at top of
the files. Then add a .htaccess and index.html files into both newly created folders. Then chmod those above folders. Then extract pic.tar.gz,
Log_Viewer.tar.gz and GeoIp.tar.gz and ensure they are not inside an extra folder from being archived.

"Then upload the code to your server and chmod; "

- cache and all nested files and folders
- dir_list
- uploads
- uploadsub imdb/imdb/casts
- imdb/imdb/casts
- imdb/imdb/posters
- include
- include/backup
- include/settings/settings.txt
- install/extra/config.phpsample.php/config.xbtsample.php
- install/extra/ann_config.phpsample.php/ann_config.xbtsample.php
- sqlerr_logs
- phperr_logs
- torrents

Create a new database user and password via phpmyadmin.

Point to https://yoursite.com/install/index.php - fill in all the required data and choose XBT or default - then log in.

Create a second user on entry named System ensure its userid2 so you dont need to alter the autoshout function on include/user_functions.php.

Sysop is added automatically to the array in cache/staff_settings.php and cache/staff_setting2.php.

Staff is automatically added to the same 2 files, but you have to make sure the member is offline before you promote them.

# Credits

All Respect goes to Mindless for keeping and coding all this versions

Thanks to darkalchemy for idea of scrapbook multicache

Thanks to HDVinnie for refractoring and style the code as php standards

All Credit goes to the original code creators

The original coders of torrentbits and especially to CoLdFuSiOn for carrying on the legacy with Tbdev.

XBT High-performance BitTorrent Tracker By Olaf van der Spek - http://code.google.com/p/xbt/

The coders of gazelle for the class.cache, sctbdev for various replacement code.

All other snippets, mods and contributions for this version from CoLdFuSiOn, putyn, pdq, djGrrr, Retro, elephant, ezero, Alex2005, system,
sir_Snugglebunny, laffin, Wilba, Traffic, dokty, djlee, neptune, scars, Raw, soft, jaits, Melvinmeow, RogueSurfer, stoner, Stillapunk, swizzles,
autotron, stonebreath, whocares, Tundracanine.

U-232 wants to thank everyone who helped make it what it is today; shaping and directing our project, all through the thick and thin. It wouldn't have
been possible without you. This includes our users and especially Beta Testers - thanks for installing and using u-232 source code as well as
providing valuable feedback, bug reports, and opinions.

# The Team

    Lead coder Mindless (he was been and allways will be the leader of this source)

    Lead Coders iseeyoucopy, GodFather 

    Coders  stonebreath

    Lead Designer iseeyoucopy

# Designers Support

    Credit's to Kidvision & others for designs used in the v0+v1+v2 Installer projects. 

    Credit's to Roguesurfer for all v3&v4 design - Your a credit to this team.

    Credit's to swizzles and mistero for their work on framework intergration and design layout for v4. 

    Credit's to son for v5 design work.

# 2021 U-232 Code Name Trinity - RC©
