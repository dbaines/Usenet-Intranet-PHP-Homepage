Usenet Intranet PHP Homepage
====

A simple little one-page Intranet homepage I created for my home server with the following features:

* Buttons to go directly to:
	* Sickbeard
	* CouchPotato
	* Headphones
	* SABnzbd
	* uTorrent WebUI
	* Movie Trailers
* Currently downloading on SABnzbd
* Currently downloading on uTorrent (requires web ui to be enabled)
* List of TV Shows that come out today from Sickbeard
* Show Wifi Password
* Completely customisable to show or hide only the features you want

![screenshot](https://github.com/dbaines/Usenet-Intranet-PHP-Homepage/raw/master/intranet/screenshots/screen1.png)

## Installation

Ideally this would go on the root of your web server, which is why the intranet resources are in a folder named intranet. The resources are all relative so you can move it in to a subfolder if you like.

To configure the page you will need to open the intranet folder and rename serverconfig-example.php to serverconfig.php
Open the config file and make the appropriate changes to the IPs, Ports, API Keys, Usernames and Passwords.

## Requirements

Your webserver will require:

* cURL 
* PHP 

## Updating

If something goes wrong after updating, please consult the latest serverconfig-example.php file. Odds are something's changed and you'll need to either:

* Update your serverconfig.php with the changes  
* Rename serverconfig-example.php to serverconfig.php and copy in the settings from your old serverconfig.php  