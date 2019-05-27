<!DOCTYPE html>
<?php
	require_once("./configs/configs.php");
	require_once("./libs/sql.php");
	require_once("./libs/steamauth/steamauth.php");
	require_once("./libs/steamauth/userInfo.php");
	require_once("./libs/steam/SourceQuery.php");
	require_once("./libs/geoip/geoip.php");
	$gi = geoip_open("./libs/geoip/GeoIP.dat",GEOIP_STANDARD);	
	require_once("./libs/functions.php");
	require_once("./libs/class/match.php");
	require_once("./libs/class/player.php");
	require_once("./libs/class/team.php");
	$activePage = basename($_SERVER['PHP_SELF'], ".php");
?>
<html lang="en">

<head>
	<meta charset="utf-8" />

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

	<title>Welcome To Warmod+</title>
	
	<!-- Icons -->
	<link rel="apple-touch-icon" href="./assets/img/icon.ico">
	<link rel="icon" href="./assets/img/icon.ico">
	<link rel="shortcut icon" href="./assets/img/icon.ico"/>
	<link rel="bookmark" href="./assets/img/icon.ico"/>

	<!-- Fonts and icons -->
	<link rel="stylesheet" href="./assets/css/googlefonts.css" />
	<link rel="stylesheet" href="./assets/css/font-awesome.css">
	<link rel="stylesheet" href="./assets/css/Pe-icon-7-stroke.css">

	<!-- CSS Files -->
	<link href="./assets/css/material-dashboard.min.css" rel="stylesheet" />
	<link href="./assets/css/warmod_plus.css" rel="stylesheet" />

	<!-- Facebook Meta -->
	<meta property="og:title" content="Warmod+">
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="Warmod+">
	<meta property="og:image" content="./assets/img/logo.png">
	<meta property="og:description" content="Warmod+">
</head>

<body class="">
	<div class="wrapper ">
		<?php require_once("./libs/sidebar.php");?>
		<div class="main-panel">
			<?php require_once("./libs/navbar.php");?>
			<div class="content">
				<div class="content">
					<div class="container-fluid">
						<h3>Latest Matches</h3>
						<br>
						<div class="row">
							<?php
								$sql = $matchSQL." limit 4";
								$sth = $pdo->prepare($sql);
								$sth->execute();
								$result = $sth->fetchAll();
								if(count($result) > 0){
									foreach($result as $row){
										$match = new Match($row, $timezone);
										$match->Card(1);
									}
								}
								else{
									match::emptyCard();
								}
							?>
						</div>
						<h3>Top Players</h3>
						<br>
						<div class="row">
							<?php
								// get player data from sql
								$sql = $playerSQL." ORDER BY rws DESC limit 4";
								$sth = $pdo->prepare($sql);
								$sth->execute();
								$result = $sth->fetchAll();
								if(count($result) > 0)
								{
									// get all player name and avatar from steam api by steamid in 1 api query
									$sth = $pdo->prepare($sql);
									$sth->execute();
									$steamids = $sth->fetchAll(PDO::FETCH_COLUMN, 1);
									$data = SteamData::GetData($SteamAPI_Key, $steamids);
									foreach($result as $row){
										$player = new Player($row);
										$player->Card($data["name"][$row["steam_id_64"]], $data["avatar"][$row["steam_id_64"]], 1);
									}
								}
								else	Player::emptyCard();
							?>
						</div>
						<h3>Top Teams</h3>
						<br>
						<div class="row">
							<?php
								// get player data from sql
								$sql = $teamSQL."ORDER BY wlr DESC LIMIT 4";
								$sth = $pdo->prepare($sql);
								$sth->execute();
								$result = $sth->fetchAll();
								if(count($result) > 0)
								{
									// get all leader name and avatar from steam api by steamid in 1 api query
									$sth = $pdo->prepare($sql);
									$sth->execute();
									$steamids = $sth->fetchAll(PDO::FETCH_COLUMN, 1);
									$data = SteamData::GetData($SteamAPI_Key, $steamids);
									foreach($result as $row){
										$team = new Team($row);
										$team->Card($data["name"][$row["leader"]], 1);
									}
								}
								else	Team::emptyCard();
							?>
						</div>
					</div>
				</div>
			</div>
			<footer class="footer">
				<div class="container-fluid">
					<div class="copyright float-right">
						&copy;
						<script>
							document.write(new Date().getFullYear())
						</script>, Made by 
						<a href="https://kento520.tw/" target="_blank">Kento</a>.
					</div>
				</div>
			</footer>
		</div>
	</div>
	<!--   Core JS Files   -->
	<script src="./assets/js/core/jquery.min.js"></script>
	<script src="./assets/js/core/popper.min.js"></script>
	<script src="./assets/js/core/bootstrap-material-design.min.js"></script>
	<script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
	<!-- Plugin for the momentJs  -->
	<script src="./assets/js/plugins/moment.min.js"></script>
	<!--  Plugin for Sweet Alert -->
	<script src="./assets/js/plugins/sweetalert2.js"></script>
	<!-- Forms Validations Plugin -->
	<script src="./assets/js/plugins/jquery.validate.min.js"></script>
	<!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
	<script src="./assets/js/plugins/jquery.bootstrap-wizard.js"></script>
	<!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
	<script src="./assets/js/plugins/bootstrap-selectpicker.js"></script>
	<!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
	<script src="./assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
	<!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
	<script src="./assets/js/plugins/jquery.dataTables.min.js"></script>
	<!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
	<script src="./assets/js/plugins/bootstrap-tagsinput.js"></script>
	<!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
	<script src="./assets/js/plugins/jasny-bootstrap.min.js"></script>
	<!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
	<script src="./assets/js/plugins/fullcalendar.min.js"></script>
	<!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
	<script src="./assets/js/plugins/jquery-jvectormap.js"></script>
	<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
	<script src="./assets/js/plugins/nouislider.min.js"></script>
	<!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
	<script src="./assets/js/plugins/core.js"></script>
	<!-- Library for adding dinamically elements -->
	<script src="./assets/js/plugins/arrive.min.js"></script>
	<!-- Chartist JS -->
	<script src="./assets/js/plugins/chartist.min.js"></script>
	<!--  Notifications Plugin    -->
	<script src="./assets/js/plugins/bootstrap-notify.js"></script>
	<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
	<script src="./assets/js/core/material-dashboard.min.js" type="text/javascript"></script>
</body>
<?php
	$pdo = null;
?>
</html>