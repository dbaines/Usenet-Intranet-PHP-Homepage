<?php 

	/* =======================================================

	INITIAL SET UP

	======================================================= */

	$config = array(

		# Services
		# Set these to false to disable them
		"sickbeard" => true,
		"couchpotato" => true,
		"headphones" => true,
		"sabnzbd" => true,

		# URLs and Ports
		"sickbeardURL" => "http://192.168.1.1",
		"sickbeardPort" => "8081",
		"sickbeardAPI" => "",
		"sabnzbdURL" => "http://192.168.1.1",
		"sabnzbdPort" => "8080",
		"sabnzbdAPI" => "",
		"couchpotatoURL" => "http://192.168.1.1",
		"couchpotatoPort" => "5000",
		"headphonesURL" => "http://192.168.1.1",
		"headphonesPort" => "8181",

		# Show currently downloading?
		"currentlyDownloading" => true,

		#Show TV Today?
		"todaysShows" => true,

		# Sickbeard - Missed or Coming?
		# Australia, for example, is almost an entire day ahead of America and so American TV shows 
		# air the day after they say they're going to air, so instead of "coming shows", we use "missed shows"
		# to indicate what's coming out today. 
		# Set to true for "missed" (Oz), false for "coming" (US)
		"sickMissed" => false,

		# Show popups when hovering over coming shows?
		"sickPopups" => true,

		# Wifi
		# WifiName is also used for page title
		"showWifi" => true,
		"wifiName" => "Shiznit Central",
		"wifiPassword" => "CCE11A3403"

	);

	/* =======================================================

	END SET UP

	======================================================= */

?>
<!doctype html>
<html>
	<head>
		<title><?= $config['wifiName']; ?> Intranet</title>
		<link rel="stylesheet" href="intranet/style.css" />
	</head>
	<body>
		<h1><?= $config['wifiName']; ?> Server</h1>

		<?php ## Check if everything is disabled
			if (!$config['sickbeard'] && !$config['couchpotato'] && !$config['headphones'] && !$config['sabnzbd'] && !$config['showWifi']) :

				echo "<img src='intranet/images/mymanjackie.png' />";

			else :
		?>
		
		<?php if( $config['sickbeard'] ) : 
			  if( $config['todaysShows'] ) : 
			  if ( $config['sickMissed'] ) : $sbType = "missed"; else: $sbType = "today"; endif;
		?>
		<div class="sickbeardShows">
			<h3>TV Today</h3>
			<?php
				$sbJSON = file_get_contents($config['sickbeardURL'].":".$config['sickbeardPort']."/api/".$config['sickbeardAPI']."/?cmd=future&sort=date&type=".$sbType);
				$sbShows = json_decode($sbJSON);

				echo "<ul>";

				# List shows
				if(empty($sbShows)){
					# quick check if there are any shows today.
					echo "<li>No shows today.</li>";
				} else {
					# Run through each show
					foreach($sbShows->{'data'}->{$sbType} as $episode) {
						echo "<li>";

						# Sickbeard Popups
						if($config ['sickPopups']) :
						echo "<span class='showPopup'>";
						echo "<img src='".$config['sickbeardURL'].":".$config['sickbeardPort']."/showPoster/?show=".$episode->{'tvdbid'}."&which=poster' class='showposter' />";
						echo "</span>";
						endif;

						# Show name and number
						echo "<strong class='showname'>".$episode->{'show_name'}."</strong><br />";
						echo "<span class='showep'>".$episode->{'season'}."x".$episode->{'episode'}." - ". $episode->{'ep_name'};
						echo "</li>";
					} 
				}
				echo "</ul>";
			?>
		</div>
		<?php endif; endif; ?>

		<?php ## Action Buttons ?>
		<?php if( $config['sickbeard'] ) : ?>
		<a href="<?= $config['sickbeardURL']; ?>:<?= $config['sickbeardPort']; ?>" title="SickBeard" class="actionButton big sickbeard"><span>SickBeard</span></a>
		<?php endif; ?>
		<?php if( $config['couchpotato'] ) : ?>
		<a href="<?= $config['couchpotatoURL']; ?>:<?= $config['couchpotatoPort']; ?>" title="CouchPoato" class="actionButton big couchpotato"><span>CouchPotato</span></a>
		<?php endif; ?>
		<?php if( $config['headphones'] ) : ?>
		<a href="<?= $config['headphonesURL']; ?>:<?= $config['headphonesPort']; ?>" title="Headphones" class="actionButton big headphones"><span>Headphones</span></a>
		<?php endif; ?>

		<?php ## SABnzbd ?>
		<?php if( $config['sabnzbd'] ) : ?>
		<a href="<?= $config['sabnzbdURL']; ?>:<?= $config['sabnzbdPort']; ?>" title="SABnzbd" class="actionButton big sabnzb"><span>SABnzbd</span></a>

			<?php if( $config['currentlyDownloading'] ) : ?>
			<div class="sabDownload">
				<h2>Currently Downloading</h2>
				<?php

					$data = simplexml_load_file($config['sabnzbdURL'].":".$config['sabnzbdPort']."/sabnzbd/api?mode=qstatus&output=xml&apikey=".$config['sabnzbdAPI']);
					$filename = $data->jobs[0]->job->filename;
					$mbFull = $data->jobs[0]->job->mb;
					$mbLeft = $data->jobs[0]->job->mbleft;
					$mbDone = $mbFull - $mbLeft;

					if($filename) {

						$mbFullNoRound = explode(".",$mbFull);
						$mbPercent = $mbDone / $mbFullNoRound[0] * 100;
						$mbPercentPretty = explode(".",$mbPercent);

						echo "<span class='currentdl'>".$filename."</span>";
						echo "<progress value='".$mbDone."' max='".$mbFull."'></progress>";
						echo "<span class='stats'>".$mbDone."mb / ".$mbFullNoRound[0]."mb (".$mbPercentPretty[0]."%) @ ". $data->speed ."</span>";

					} else {
						
						echo "<em class='currentdl'>No current downloads</em>";

					}
				?>

			</div>
			<?php endif; ?>

		<?php endif; ?>
		<div class="clearLeft"></div>

		<?php ## Wifi ?>
		<?php if( $config['showWifi'] ) : ?>
			<div class="wifi">
				<h2>Wifi Password for <?= $config['wifiName'] ?></h2>
				<big><?= $config['wifiPassword']; ?></big>
			</div>
		<?php endif; ?>

		<?php ## Ending check for all-disabled ?>
		<?php endif; ?>

	</body>
</html>