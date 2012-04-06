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

All the set up variables are at the top of the php file. 
At the very top of the configuration is switches to turn on or off each service. Set them to false to turn them off, set them to true to turn them on. 
Underneath those switches are the URLs and Ports that you will need to configure before anything will work.

If you are wanting to show current downloads and TV Today, please ensure you have set the API keys for SABnzbd and SickBeard in the configuration section at the top of the php file.