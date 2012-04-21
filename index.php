<?php include('intranet/serverconfig.php'); ?>
<?php include('intranet/lib/functions.php'); ?>
<?php if($config['uTorrent']) {include('intranet/lib/utorrent_php_api.php');} ?>
<?php if($config['transmission']) {include('intranet/lib/transmissionrpc.class.php');} ?>
<?php 

	## Setting up URL structures
	if($config['sickbeardUsername']) {
		$sickbeardURL = "http://".$config['sickbeardUsername'].":".$config['sickbeardPassword']."@".$config['sickbeardURL'].":".$config['sickbeardPort'];
	} else {
		$sickbeardURL = "http://".$config['sickbeardURL'].":".$config['sickbeardPort'];
	}

	if($config['sabnzbdUsername']) {
		$sabURL = "http://".$config['sabnzbdUsername'].":".$config['sabnzbdPassword']."@".$config['sabnzbdURL'].":".$config['sabnzbdPort'];
	} else {
		$sabURL = "http://".$config['sabnzbdURL'].":".$config['sabnzbdPort'];
	}

	if($config['transmissionUsername']) {
		$transmissionURL = "http://".$config['transmissionURL'].":".$config['transmissionPort']."@".$config['transmissionUsername'].":".$config['transmissionPassword'];
	} else {
		$transmissionURL = "http://".$config['transmissionURL'].":".$config['transmissionPort'];
	}

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
				$sbJSON = file_get_contents($sickbeardURL."/api/".$config['sickbeardAPI']."/?cmd=future&sort=date&type=".$sbType);
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
						echo "<img src='".$sickbeardURL."/showPoster/?show=".$episode->{'tvdbid'}."&which=poster' class='showposter' />";
						echo "</span>";
						endif;

						# Show name and number
						echo "<strong class='showname'>".$episode->{'show_name'}."</strong><br />";
						echo "<span class='showep'>".$episode->{'season'}."x".$episode->{'episode'}." - ". $episode->{'ep_name'};
						echo "</li>";
					} 
				}
				echo "</ul>";

				$sbJSONdone = file_get_contents($sickbeardURL."/api/".$config['sickbeardAPI']."/?cmd=history&limit=50");
				$sbShowsdone = json_decode($sbJSONdone);
				$todaysDate = date('Y-m-d');

				echo "<ul class='snatchedShows'>";

				# List shows
				# Run through each show
				foreach($sbShowsdone->{'data'} as $episode) {

					if (substr($episode->date,0,10) == $todaysDate && $episode->status == "Snatched") :

					echo "<li>";

					# Sickbeard Popups
					if($config ['sickPopups']) :
					echo "<span class='showPopup'>";
					echo "<img src='".$sickbeardURL."/showPoster/?show=".$episode->{'tvdbid'}."&which=poster' class='showposter' />";
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
		<a href="<?= $sickbeardURL; ?>" title="SickBeard" class="actionButton big sickbeard"><span>SickBeard</span></a>
		<?php endif; ?>
		<?php if( $config['couchpotato'] ) : ?>
		<a href="http://<?= $config['couchpotatoURL']; ?>:<?= $config['couchpotatoPort']; ?>" title="CouchPoato" class="actionButton big couchpotato"><span>CouchPotato</span></a>
		<?php endif; ?>
		<?php if( $config['headphones'] ) : ?>
		<a href="http://<?= $config['headphonesURL']; ?>:<?= $config['headphonesPort']; ?>" title="Headphones" class="actionButton big headphones"><span>Headphones</span></a>
		<?php endif; ?>

		<?php ## SABnzbd ?>
		<?php if( $config['sabnzbd'] ) : ?>
		<a href="<?= $sabURL; ?>" title="SABnzbd" class="actionButton big sabnzb"><span>SABnzbd</span></a>

		<div class="downloadFrame clearfix"><div class="downloadFrameSlide clearfix">
			<div class="downloadPage downloadPageCurrent">
				<h2>Currently Downloading</h2>
				<a href="#" class="go actionButton small">&gt;</a>
				<?php

					$data = simplexml_load_file($sabURL."/sabnzbd/api?mode=qstatus&output=xml&apikey=".$config['sabnzbdAPI']);
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
			<div class="downloadPage downloadPageHistory">
				<h2>Recently Finished</h2>
				<a href="#" class="go actionButton small">&lt;</a>
				<?php

					$data = simplexml_load_file($sabURL."/sabnzbd/api?mode=history&start=0&limit=5&output=xml&apikey=".$config['sabnzbdAPI']);
					echo "<ul>";
					foreach($data->slots[0] as $slot) {
						echo "<li>".$slot->category." - ".$slot->nzb_name."</li>";
					}
					echo "</ul>";
				?>
			</div>
			<?php endif; ?>
		</div></div>

		<?php ## uTorrent Web GUI ?>
		<?php if( $config['uTorrent'] ) : ?>
		<section class="clearfix">
			<a href="http://<?= $config['uTorrentURL']; ?>:<?= $config['uTorrentPort']; ?>/gui/" title="uTorrent" class="actionButton big utorrent"><span>uTorrent</span></a>

			<div class="downloadFrame">
				<div class="downloadPage downloadPageCurrent">
					<h2>Currently Downloading</h2>
					<?php

				        $utorrent = new uTorrentAPI($config);

				        // Create some variables
				        $torrentAPI = $utorrent->get_torrent_list();
				        $torrents = $torrentAPI['torrents'];
				        $torrentsComplete = array();
				        $torrentsDownloading = array();

				        // Check if any results are returned
				        if(sizeof($torrents)==0) {

				        	echo "<em>No current downloads</em>";

				        } else {

					        // Run through each torrent and insert in to appropriate variables
					        foreach($torrents as $torrent) {
					            if($torrent[4] == "1000") {
					                array_push($torrentsComplete, $torrent);
					            } else {
					                array_push($torrentsDownloading, $torrent);
					            }
					        }

					        // Cut off array at 5 each
					        $torrentsDownloading = array_slice($torrentsDownloading,0,5);

					        // List all pending downloads
					        foreach($torrentsDownloading as $torrentDone) {
					            $name = $torrentDone[2];
					            $sizeFull = $torrentDone[3];
					            $sizeDone = $torrentDone[5];
					            $percentage = $torrentDone[4]/10;
					            $speed = $torrentDone[9];

					            echo "<div class='torrent'>";
					            echo $name;
					            echo "<progress value='".$sizeDone."' max='".$sizeFull."'></progress>";
					            echo "<span class='stats'>";
					            echo ByteSize($sizeDone)." / ".ByteSize($sizeFull)." (".$percentage."%)";
					            echo " @ " .ByteSize($speed);
					            echo "</span>";
					            echo "</div>";
					        }

					    }

					?>
				</div>
			</div>
		</section>
		<?php endif; ?>

		<?php if($config['transmission']) : ?>	
		<section class="clearfix">
			<a href="<?= $transmissionURL; ?>" title="Transmission" class="actionButton big transmission"><span>Transmission</span></a>

			<div class="downloadFrame">
				<div class="downloadPage downloadPageCurrent">
					<h2>Currently Downloading</h2>
					<?php
						//$transmissionAPI = new TransmissionRPC($transmissionURL."/transmission/rpc", null, null, true);

$rpc = new TransmissionRPC();
$rpc->url = $transmissionURL."/transmission/rpc";

					?>
				</div>
			</div>
		</section>
		<?php endif; ?>

		<?php ## Wifi ?>
		<?php if( $config['showWifi'] ) : ?>
			<div class="wifi">
				<h2>Wifi Password for <?= $config['wifiName'] ?></h2>
				<big><?= $config['wifiPassword']; ?></big>
			</div>
		<?php endif; ?>

		<div class="secondaryButtons clearfix">

			<?php if( $config['showTrailers'] ) : ?>
			<a href="http://www.hd-trailers.net/" target="_blank" class="actionButton small icon iconTrailer"><span>Watch Trailers</span></a>
			<?php endif; ?>

			<?php if ( !empty($config['bookmarks']) ) {
				foreach ($config['bookmarks'] as $bookmark) {
					
					//var_dump($bookmark);
					// echo $bookmark['label'];
					echo "<a href='".$bookmark['url']."' target='_blank' class='actionButton small icon'><span style='background-image: url(".$bookmark['url']."/favicon.ico);'>".$bookmark['label']."</span></a>";

				}
			}
			?>

		</div>

		<?php ## Ending check for all-disabled ?>
		<?php endif; ?>

		<script src="intranet/js/jquery.js"></script>
		<script src="intranet/js/scripts.js"></script>
	</body>
</html>