<?php
$sql['host'] = 'localhost';
$sql['user'] = 'root';
$sql['pass'] = '';
$sql['name'] = 'eatl_db';

$con = mysqli_connect($sql['host'], $sql['user'], $sql['pass'], $sql['name']);

/** REFERRAL SYSTEM **/
$refID = "";
session_name('UG_AQW_ACP');
session_start();
if(isset($_SESSION['refID'])) {
	$refID = $_SESSION['refID'];
}

if(isset($_POST["strUsername"])) {
	/** PREVENTS DATA MANIPULATION **/
	$sign['user'] = $con->real_escape_string(stripslashes($_POST["strUsername"]));
	$sign['pass'] = $con->real_escape_string(stripslashes($_POST["strPassword"]));
	$sign['pass'] = md5($sign['pass']);
	$sign['uage'] = $con->real_escape_string(stripslashes($_POST["intAge"]));
	$sign['daob'] = $con->real_escape_string(stripslashes($_POST["strDOB"]));
	$sign['emal'] = $con->real_escape_string(stripslashes($_POST["strEmail"]));
	$sign['gend'] = $con->real_escape_string(stripslashes($_POST["strGender"]));
	$sign['caid'] = $con->real_escape_string(stripslashes($_POST["ClassID"]));
	$sign['eycc'] = $con->real_escape_string(stripslashes($_POST["intColorEye"]));
	$sign['sycc'] = $con->real_escape_string(stripslashes($_POST["intColorSkin"]));
	$sign['hycc'] = $con->real_escape_string(stripslashes($_POST["intColorHair"]));
	$sign['hair'] = $con->real_escape_string(stripslashes($_POST['HairID']));

	/** GRABS IP **/
	if ($_SERVER['HTTP_X_FORWARD_FOR']) {
		$ip = $_SERVER['HTTP_X_FORWARD_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	/** CHECKS IP ADDRESS **/
	$ipcheck = $con->query("SELECT id FROM etl_users WHERE registered_ip='$ip'");
	if ($ipcheck->num_rows > 0) {
		die("status=Taken&strReason=This IP address is already being used by another account. Contact the administrator if you don't think so or you want to create a new account.");
	}
	
	/** CHECKS EMAIL **/
	$emcheck = $con->query("SELECT id FROM etl_users WHERE strEmail='{$sign['emal']}'");
	if ($emcheck->num_rows > 0) {
		die("status=Taken&strReason=The email is already in used by another user.");
	}

	/** CHECKS USERNAME **/
	$sql = $con->query("SELECT id FROM etl_users WHERE strUsername = '{$sign['user']}'");
	if ($sql->num_rows > 0) {
		die("status=Taken&strReason=The username is already in use by another character.");
	} else {
		/** SETS HAIRNAME & HAIRFILE **/
		switch ($sign['hair']) {
			/** MALE HAIR **/
			case 52:
				$hairname = 'Default';
				$hairfile = 'hair/M/Default.swf';
				break;
			case 55:
				$hairname = 'Goku1';
				$hairfile = 'hair/M/Goku1.swf';
				break;
			case 58:
				$hairname = 'Goku2';
				$hairfile = 'hair/M/Goku2.swf';
				break;
			case 64:
				$hairname = 'Normal2';
				$hairfile = 'hair/M/Normal2.swf';
				break;
			case 92:
				$hairname = 'Ponytail8';
				$hairfile = 'hair/M/Ponytail8.swf';
				break;
			
			/** FEMALE HAIR **/
			case 14:
				$hairname = 'Pig1Bangs1';
				$hairfile = 'hair/F/Pig1Bangs1.swf';
				break;
			case 18:
				$hairname = 'Pig2Bangs2';
				$hairfile = 'hair/F/Pig2Bangs2.swf';
				break;
			case 26:
				$hairname = 'Pony2Bangs2';
				$hairfile = 'hair/F/Pony2Bangs2.swf';
				break;
			case 83:
				$hairname = 'Bangs2Long';
				$hairfile = 'hair/F/Bangs2Long.swf';
				break;
			case 84:
				$hairname = 'Bangs3Long';
				$hairfile = 'hair/F/Bangs3Long.swf';
				break;
		}

		/** INSERTS CHARACTER INFO INTO DATABASE **/
		$con->query("INSERT INTO etl_users (strUsername, strPassword, strEmail, iAge, strGender, currentClass, strHairName, strHairFilename, intColorSkin, intColorHair, intColorEye, registered_ip, iUpg, iUpgDays, sHouseInfo, iCoins, iAccess) VALUES ('{$sign['user']}', '{$sign['pass']}', '{$sign['emal']}', '{$sign['uage']}', '{$sign['gend']}','{$sign['caid']}','{$hairname}','{$hairfile}', '{$sign['sycc']}', '{$sign['hycc']}', '{$sign['eycc']}', '{$ip}', '1', '100', '', '2000', '0');");

		/** SELECTS NEW USER ID **/
		$sql3 = $con->query("SELECT id FROM etl_users WHERE strUsername='{$sign['user']}'");
		$user = $sql3->fetch_assoc();
		$user_id = $user['id'];

		/** ADD'S STARTING CLASSES **/
		/** PEATL **/

		switch ($sign['caid']) {
			case 1: 
				$con->query("INSERT INTO etl_users_items (item_id, user_id, bEquip, sES, iLvl, iCP) VALUES ('16', '$user_id', '1', 'ar', '1', '0')");
				break;
			case 4: 
				$con->query("INSERT INTO etl_users_items (item_id, user_id, bEquip, sES, iLvl, iCP) VALUES ('29', '$user_id', '1', 'ar', '1', '0')");
				break;
			case 3: 
				$con->query("INSERT INTO etl_users_items (item_id, user_id, bEquip, sES, iLvl, iCP) VALUES ('17', '$user_id', '1', 'ar', '1', '0')");
				break;
			case 10: 
				$con->query("INSERT INTO etl_users_items (item_id, user_id, bEquip, sES, iLvl, iCP) VALUES ('19', '$user_id', '1', 'ar', '1', '0')");
				break;
		}		
		

		/** ADDS DEFAULT WEAPON **/
		$con->query("INSERT INTO etl_users_items (item_id, user_id, bEquip, sES, iLvl) VALUES ('1', '$user_id', '1', 'Weapon', '1')");

		/** ADDS FRIENDS LIST **/
		$con->query("INSERT INTO etl_users_friends (user_id) VALUES ('$user_id')");

		/** SUCCESS **/	
		echo "status=Success";
		
		/** ADDS FACTIONS **/
		$con->query("INSERT INTO `etl_users_factions` 
				(`user_id`,`factionid`,`iRep`,`sName`)
				VALUES 
				($user_id,13,0,'Adventurer');");
		$refer = $con->query("SELECT id FROM etl_users WHERE id=$refID");
		if(($refer->num_rows) > 0) {
			$con->query("UPDATE etl_users SET isReferred=1,referredBy='{$sign['user']}',refExp=10000,refGold=10000 WHERE id=$refID");
		}
	}
} else {
	die("status=Error&strReason=Invalid Input.");
}
?>
