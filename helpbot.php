<?php
ini_set('error_reporting', E_ALL);

//Define BotToken
$botToken = "";
if ($botToken === "") {
	exit("Token Unauthorized! This script has no valid bot token!");
} else {
	print_r("Token Accepted!");
}
//prepare BOT API URL
$url = array(
	"https://api.telegram.org/bot".$botToken,
	"https://api.telegram.org/files/bot".$botToken
);
/*BotInfo
	-Firstname				$myName[0]
	-Username				$myName[1]
	-Username(lowercase)	$myName[2]
*/
$getMe = file_get_contents($url[0].'/getMe');
$getMe = json_decode($getMe, TRUE);
if ($getMe['ok'] == true) {
	$myName = array($getMe['result']['first_name'], $getMe['result']['username'],strtolower($getMe['result']['username']));
}

//Store all the input that comes to this script into a variable
$update = file_get_contents('php://input');
//Convert json data to array
$update = json_decode($update, TRUE);

//Log File
$logName = "log.txt";
if (is_writable($logName)) {
	$fLog = fopen($logName, 'w');
	if ($fLog != false) {
		fwrite($fLog, print_r($update, TRUE));
	}
}

//Grab certain attributes from the received data (username, userid, chat id, message, type);
$uID = $update['update-id'];
$fname = $update['message']['from']['first_name'];
$lname = $update['message']['from']['last_name'];
$usrname = $update['message']['from']['username'];
$usrid = $update['message']['from']['id'];
$chatId = $update['message']['chat']['id'];
$msgtype = $update['message']['chat']['type'];
$time = $update['message']['date'];
$msg = $update['message']['text'];

if($update != null and array_key_exists('new_chat_participant', $update['message'])) {
	$nusrname = $update['message']['new_chat_participant']['username'];
	$re = "Hello @".$nusrname."\n Welcome to The Telegram Bot Community.\n This bot is still being developed and a list of commands will be avaliable soon.";// DEBUG
	/* PRODUCTION
	$re = "Hello @".$nusrname."\n Welcome to The Telegram Bot Community.\n The commands available at your disposal are\n
	/getStarted - Necessary informatin to start writing your own bot\n
	/webHook - How webhooks work and how to set it\n
	/selfSign - For help with self signed certificate\n
	/hosting - For help with hosting\n
	";*/
	sendMessage($re);
}

switch(strtolower($msg)) {
	case('/getstarted@'.$myName[2]) :
	case('/getstarted @'.$myName[2]) :
	case('/getstarted') :
		$re = "Info on getting ";
		sendMessage($re);
		break;
	case('/webhook@'.$myName[2]) :
	case('/webhook @'.$myName[2]) :
	case('/webhook') :
		$re = "Info on web hook ";
		sendMessage($re);
		break;
	case('/selfsign@'.$myName[2]) :
	case('/selfsign @'.$myName[2]) :
	case('/selfsign') :
		$re = "Info on self ";
		sendMessage($re);
		break;
	case('/hosting@'.$myName[2]) :
	case('/hosting @'.$myName[2]) :
	case('/hosting') :
		$re = "Info on hosting ";
		sendMessage($re);
		break;
	case('/who@'.$myName[2]) :
	case('/who @'.$myName[2]) :
	case('/who') :
		$re = "Hello, my name is ".$myName[0].", ".$myName[1];
		sendMessage($re);
		break;
}

function sendMessage ($response) {
	global $chatId,$url;
	$se = $url[0]."/sendMessage?chat_id=".$chatId."&text=".urlencode($response);
	file_get_contents($se);
}