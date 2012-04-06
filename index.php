<?php include('intranet/serverconfig.php'); ?>
<!doctype html>
<html>
	<head>
		<title><?= $config['wifiName']; ?> Intranet</title>
		<link rel="stylesheet" href="intranet/style.css" />
	</head>
	<body>
		<h1><?= $config['wifiName']; ?> Server</h1>

		<?php ## Check if everything is disabled
			if (!$config['sickbeard'] && !$config['couchpotato'] && !$config['headphones'] && !$config['sabnzbd'] && !$config['showWifi'] && !$config['showTrailers']) :

				echo "<img src='intranet/images/mymanjackie.png' />";

			else :
		?>
		
		<?php if( $config['sickbeard'] ) : 
			  if ( $config['sickMissed'] ) : $sbType = "missed"; else: $sbType = "today"; endif;
		?>
		<div class="sickbeardShows">
			<h3>TV Today</h3>
			<?php
				$sbJSON = file_get_contents($config['sickbeardURL'].":".$config['sickbeardPort']."/api/".$config['sickbeardAPI']."/?cmd=future&sort=date&type=".$sbType);
				$sbShows = json_decode($sbJSON);

				echo "<ul class='comingShows'>";

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

				$sbJSONdone = file_get_contents($config['sickbeardURL'].":".$config['sickbeardPort']."/api/".$config['sickbeardAPI']."/?cmd=history&limit=15");
				$sbShowsdone = json_decode($sbJSONdone);
				$todaysDate = date('Y-m-d');

				echo "<ul class='snatchedShows'>";

				# List shows
				# Run through each show
				foreach($sbShowsdone->{'data'} as $episode) {

					if (substr($episode->date,0,10) == $todaysDate) :

					echo "<li>";

					# Sickbeard Popups
					if($config ['sickPopups']) :
					echo "<span class='showPopup'>";
					echo "<img src='".$config['sickbeardURL'].":".$config['sickbeardPort']."/showPoster/?show=".$episode->{'tvdbid'}."&which=poster' class='showposter' />";
					echo "</span>";
					endif;

					# Show name and number
					echo "<strong class='showname'>".$episode->{'show_name'}." <small>".$episode->{'season'}."x".$episode->{'episode'}."</small></strong>";
					echo "</li>";
					endif;
				} 
				echo "</ul>";

			?>
		</div>
		<?php endif; ?>

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

					echo "<span class='currentdl'>";
					if ($data->paused == "True") {echo "PAUSED: ";}
					echo $filename."</span>";
					echo "<progress value='".$mbDone."' max='".$mbFull."'></progress>";
					echo "<span class='stats'>".$mbDone."mb / ".$mbFullNoRound[0]."mb (".$mbPercentPretty[0]."%) @ ". $data->speed ."</span>";

				} else {
					
					echo "<em class='currentdl'>No current downloads</em>";

				}
			?>

		</div>
		<?php endif; ?>
		<div class="clearLeft"></div>

		<?php ## Wifi ?>
		<?php if( $config['showWifi'] ) : ?>
			<div class="wifi">
				<h2>Wifi Password for <?= $config['wifiName'] ?></h2>
				<big><?= $config['wifiPassword']; ?></big>
			</div>
		<?php endif; ?>

		<?php if( $config['showTrailers'] ) : ?>
		<div class="clearLeft secondaryButtons">
			<a href="http://www.hd-trailers.net/" target="_blank" class="actionButton small icon iconTrailer"><span>Online Trailer Downloads</span></a>
		</div>
		<?php endif; ?>

		<?php ## Ending check for all-disabled ?>
		<?php endif; ?>


	</body>
</html>