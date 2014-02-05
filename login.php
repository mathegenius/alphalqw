<?php
require "core.php";

if($_GET['do'] == 'logout') {
	$session->unset_data("uoName");
	$session->unset_data("sID");
	$session->unset_data("isStaff");
	$session->unset_data("isVIP");
	// Unset all of the session variables.
	$_SESSION = array();

	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time()-(60*60*24*365),
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}

	// Finally, destroy the session.
	session_destroy();
}
if(($session->get_data("sID"))) {
	header("Location: ucp.php");
}

/** Includes **/
include PSZ_FILE_PATH."inc/class.pagina.php";
include PSZ_FILE_PATH."inc/class.template.php";

/** Initialize Needed Classes **/
global $temp;
$temp = new Template();

define("USER_WISE", 3);
define("USER_STAFF", 2);
define("USER_VIP", 1);



if(isset($C_POST['login'])) {
	$post['user'] = $C_POST['username'];
	$post['pass'] = md5($C_POST['password']);
	$post['remb'] = $C_POST['rem'];
	if (!preg_match('/^[\sa-z0-9\x99\xe2\x84\xa2]+$/i', $post['user'])) {
		$err = '<p class="error">Username must contain letters and/or numbers.</p>'."\n";
	} else {
		$data = $mysql->fetch_query("SELECT {$usr['usid']},{$usr['name']},{$usr['pswd']},{$usr['accs']} FROM {$tbl['user']} WHERE {$usr['name']}='{$post['user']}'");
		if($data[$usr['pswd']] == $post['pass']) {
			$session->set_data("uoName", $data[$usr['name']], $post['remb']);
			$session->set_data("sID", md5($data[$usr['usid']]), $post['remb']);
			$session->set_data("uID", $data[$usr['usid']], $post['remb']);
			switch ($root->get_user_access($data[$usr['accs']])) {
				case USER_STAFF:
					$session->set_data("isStaff", true, $post['remb']);
					break;
				case USER_VIP:
					$session->set_data("isVIP", true, $post['remb']);
					break;
				default:
					break;
			}
			header("Location: loginsuccess.asp");
		} else {
			$temp->add_error("Warning", "Invalid Credentials");
		}
	}
}

$temp->header("Login");
$temp->add_error("Notice", "CMS is still under development, please report bugs to Ishan or Owl (WiSeXC staffs) as soon as possible.");
$temp->content .= '<form action="login.php" method="post" class="nice"><h2>Login</h2><p class="left"><label>Username:</label><input type="text" name="username" class="inputText" /><label>Password:</label><input type="password" name="password" class="inputText" /><label><input type="checkbox" name="rem" />Remember Me</label><br clear="all" /><br clear="all" /><button type="submit" class="red" name="login">Login</button></p><div class="clear"></div></form>';
$temp->footer();
$temp->flush();
?>
