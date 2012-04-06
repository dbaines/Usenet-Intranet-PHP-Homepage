Usenet Intranet PHP Homepage
====

A simple little one-page Intranet homepage I created for my home server with the following features:

* Buttons to go directly to:
	* Sickbeard
	* CouchPotato
	* Headphones
	* SABnzbd
* Currently downloading on SABnzbd
* List of TV Shows that come out today
* Show Wifi Password
* Completely customisable to show or hide only the features you want

![screenshot](https://github.com/dbaines/Usenet-Intranet-PHP-Homepage/raw/master/intranet/screenshots/screen1.png)

## Installation

Ideally this would go on the root of your web server, which is why the intranet resources are in a folder named intranet. The resources are all relative so you can move it in to a subfolder if you like.

To configure the page you will need to open the intranet folder and rename serverconfig-example.php to serverconfig.php
Open the config file and make the appropriate changes to the IPs, Ports and API Keys.