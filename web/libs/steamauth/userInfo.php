<?php
if (!empty($_SESSION['steamid']) && (empty($_SESSION['steam_uptodate']) || empty($_SESSION['steam_personaname']))) {
	require 'SteamConfig.php';
	//$url = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$steamauth['apikey']."&steamids=".$_SESSION['steamid']); 
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$steamauth['apikey']."&steamids=".$_SESSION['steamid']);
	
	$result = curl_exec($ch);
	curl_close($ch);
	
	$content = json_decode($result, true);
	$_SESSION['steam_steamid'] = $content['response']['players'][0]['steamid'];
	//$_SESSION['steam_communityvisibilitystate'] = $content['response']['players'][0]['communityvisibilitystate'];
	//$_SESSION['steam_profilestate'] = $content['response']['players'][0]['profilestate'];
	$_SESSION['steam_personaname'] = $content['response']['players'][0]['personaname'];
	//$_SESSION['steam_lastlogoff'] = $content['response']['players'][0]['lastlogoff'];
	$_SESSION['steam_profileurl'] = $content['response']['players'][0]['profileurl'];
	$_SESSION['steam_avatar'] = $content['response']['players'][0]['avatar'];
	$_SESSION['steam_avatarmedium'] = $content['response']['players'][0]['avatarmedium'];
	$_SESSION['steam_avatarfull'] = $content['response']['players'][0]['avatarfull'];
	//$_SESSION['steam_personastate'] = $content['response']['players'][0]['personastate'];
	/*	
		if (isset($content['response']['players'][0]['realname'])) { 
			$_SESSION['steam_realname'] = $content['response']['players'][0]['realname'];
		} else {
			$_SESSION['steam_realname'] = "Real name not given";
		}
	*/
	//$_SESSION['steam_primaryclanid'] = $content['response']['players'][0]['primaryclanid'];
	//$_SESSION['steam_timecreated'] = $content['response']['players'][0]['timecreated'];
	$_SESSION['steam_uptodate'] = time();
	
	// cookie
	setcookie("user", $_SESSION['steam_steamid'], time()+86400*7);

	// get player data from sql
	$sql = "select * from ".$player_table." where steam_id_64 = :steam";
	$input = array(
		":steam" =>  $_SESSION['steam_steamid'],
	);
	$sth = $pdo->prepare($sql);
	$sth->execute($input);
	$result = $sth->fetchAll();
	foreach($result as $k => $v){
		if($k != "rws" && $k != "steam_id_64")	$_SESSION[$k] = $v;
	}

	$_SESSION["view"] = "module";
}

// We don't need this because we have session.
// $steamprofile['steamid'] = $_SESSION['steam_steamid'];
// $steamprofile['communityvisibilitystate'] = $_SESSION['steam_communityvisibilitystate'];
// $steamprofile['profilestate'] = $_SESSION['steam_profilestate'];
// $steamprofile['personaname'] = $_SESSION['steam_personaname'];
// $steamprofile['lastlogoff'] = $_SESSION['steam_lastlogoff'];
// $steamprofile['profileurl'] = $_SESSION['steam_profileurl'];
// $steamprofile['avatar'] = $_SESSION['steam_avatar'];
// $steamprofile['avatarmedium'] = $_SESSION['steam_avatarmedium'];
// $steamprofile['avatarfull'] = $_SESSION['steam_avatarfull'];
// $steamprofile['personastate'] = $_SESSION['steam_personastate'];
// $steamprofile['realname'] = $_SESSION['steam_realname'];
// $steamprofile['primaryclanid'] = $_SESSION['steam_primaryclanid'];
// $steamprofile['timecreated'] = $_SESSION['steam_timecreated'];
// $steamprofile['uptodate'] = $_SESSION['steam_uptodate'];

// Version 3.2
// But I edited part of code, change file_get_contents to curl and remove some unnecessary userinfo.
// Valve updated steam privacy so we can't get some info unless player change their steam profile settings.
// Added cookie
?>
    
