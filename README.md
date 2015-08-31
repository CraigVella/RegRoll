# RegRoll
Edge of The Empire RPG Registered Roll Site
The utility located @ http://reztek.net/rr

Used To create registered rolls for Star Wars RPG Games

#Description
A GM creates a roll link with optional difficulty die associated.

The GM hands off link to PC

PC adds his dice to the pool and Rolls

Results are stored indefinitely at that Roll link and can not be Re-Rolled

#Creating a Local Working Fork

1. Install Git client and Clone Repository
 * Download and install latest GitHub Client From here https://desktop.github.com/
 * Open GitHub Desktop and click Plus icon in upper right
 * Clone the Repo https://github.com/ChaseHQ/RegRoll Somewhere suitable for you
 * We will consider this REPO_DIR the rest of the install process

2. Download and Install MySQL
 * Download and Install 2013 C++ VS Redis from http://www.microsoft.com/en-us/download/details.aspx?id=40784
 * Browse to http://dev.mysql.com/downloads/mysql/ and download MySQL Installer
 * During install Click Custom
 * Select MySQL Server ->
 * Select MySQL Workbench ->
 * Click Next and Install
 * Set the root password and continue Next to complete installation

3. Configure MySQL
 * Open up MySQL Workbench and login to your server with root credentials
 * In Navigator, Under Management Click Data Import
 * On Right Pane, Click Import from Self-Contained Files Radio Button
 * Browse to REPO_DIR under Folder SQL select RR.sql
 * Under 'Default Scheme to be Imported To' Click New and give it the name 'rr'
 * Click Start Import
 * Back on the left hand Pane Under 'Management' Click 'Users and Privlidges'
 * On Right Hand Pane Click 'New Account'
 * For 'Login Name' type 'rr' For 'Limit to Matching Hosts' type 'localhost' For 'Password' type 'rr' Click apply
 * Click 'Schema Privlidges' Tab
 * Click 'Add Entry' Select the 'rr' Schema, Select 'All Privlidges' Press Apply
 * MySQL is now configured

4. Install Apache
 * Download and install VS 2012 Runtimes from http://www.microsoft.com/en-us/download/details.aspx?id=30679
 * Download Apache VC11 Build from http://www.apachelounge.com/download/VC11/
 * Extract Apache24 Folder to C:\Apache24
 * Download Apache Modules below the Apache Download (It is a package containing extra modules)
 * Extract 'mod_fcgid.so' from mod_fcgid-2.3.9\mod_fcgid Folder into 'C:\Apache24\modules' 
 * Open an Elevated Command Prompt (run cmd as Administrator)
 * Navigate to Apache24\bin Directory (CD \Apache24\bin)
 * Run 'httpd -k install' This will install Apache2 as System service
 * Make a shortcut to 'C:\Apache24\bin\ApacheMonitor.exe' in your startup folder for monitoring
 * Open 'C:\Apache24\conf\httpd.conf' in an Editor 
 * Uncomment line 'LoadModule rewrite_module modules/mod_rewrite.so'
 * Find 'DocumentRoot' and set path to REPO_DIR
 * Find '<Directory ' and set path to REPO_DIR
 * Under the <Directory > Tags Find 'AllowOverride None' and change to 'AllowOverride All'
 * Under the <Directory > Tags Find 'Options Indexes FollowSymLinks' and Change to 'Options Indexes FollowSymLinks ExecCGI'
 * Find 'DirectoryIndex index.html' and Change to 'DirectoryIndex index.php'
 * Open ApacheMonitor in your System Tray (Next to Clock on desktop) and restart Apache Service

5. Install PHP
 * Download PHP 5.6 from http://windows.php.net/download#php-5.6
 * Extract all contents to C:\php
 * Open up 'C:\Apache24\conf\httpd.conf' go to end and paste the folowing in:
```
LoadModule fcgid_module modules/mod_fcgid.so
FcgidInitialEnv PHPRC        "c:/php" 
AddHandler fcgid-script .php  
FcgidWrapper "c:/php/php-cgi.exe" .php  
```
 * 
 
