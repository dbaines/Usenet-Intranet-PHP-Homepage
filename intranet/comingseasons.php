<?php

// Include Settings
include("serverconfig.php");

// Edit Settings
$username = $config['sickbeardUsername'];
$password = $config['sickbeardPassword'];
$ip = $config['sickbeardURL'].":".$config['sickbeardPort'];
$api = $config['sickbeardAPI'];
// End Settings

// Check if username is available, set URL
if($username) {
	$feed = "http://".$username.":".$password."@".$ip."/api/".$api."/?cmd=future&sort=date&type=later";
} else {
	$feed = "http://".$ip."/api/".$api."/?cmd=future&sort=date&type=later";
}
$sbJSON = json_decode(file_get_contents($feed));
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Season Start Dates</title>
		<link rel="stylesheet" href="style.css" />
		<link rel="shortcut icon" href="images/wifi.png" />
	</head>
	<body>
		<h1>Season Start Dates</h1>
		<div class="seasonStarts">
			<?php
			echo "<ul>";
			// Run through each feed item
			foreach($sbJSON->{data}->{later} as $show) {
				// Only grab shows of episode 1
				if($show->{episode} == "1") {
					// Reformat date
					$newDate = date("l, j F Y", strtotime($show->{airdate}));
					// Show Details
					echo "<li>";
					echo "<img src='http://".$ip."/showPoster/?show=".$show->{'tvdbid'}."&which=banner' class='showbanner' />";
					echo "<strong>".$show->{show_name} . "</strong><br />Season " . $show->{season} . "<br /><br />Starts " .$newDate;
					echo "</li>";
				}
			}
			echo "</ul>";
			?>
		</div>
		<script src="js/jquery.js"></script>
		<script src="js/scripts.js"></script>
	</body>
</html>