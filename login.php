<?php
// Start the session
session_start();

include 'function.php';
?>

<!DOCTYPE html>
<html>

	<head>
		<link rel="stylesheet" type="text/css" href="style/style.css"/>
	</head>

	<body>
		<div class="sameline">
            <div class="boxx"><a href="index.php">Home</a></div>
            <div class="boxx"><a href="login.php">Account</a></div>
            <div class="boxx"><a href="mineblock.php">Mine Block</a></div>
            <div class="boxx"><a href="trade.php">Trade</a></div>
            <div class="boxx"><a href="search.php">Search</a></div>
        </div>

		<?php 
			if (logged() == ""){
				echo '
					<form method="POST">
						<div class="box">
							<h3>Login</h3>
				            <div class="input-box">
				                <input type="text" name="user" autocomplete="off" required>
				                <label for="">username</label>
				            </div>

				            <div class="input-box">
				                <input type="text" name="pass" autocomplete="off" required>
				                <label for="">password</label>
				            </div>
			            	<input type="submit" name="login">
			            </div>
			        </form>

			        <form method="POST">
						<div class="box">
							<h3>Register</h3>
				            <div class="input-box">
				                <input type="text" name="userr" autocomplete="off" required>
				                <label for="">username</label>
				            </div>

				            <div class="input-box">
				                <input type="text" name="passr" autocomplete="off" required>
				                <label for="">password</label>
				            </div>

				            <div class="input-box">
				                <input type="text" name="pinr" autocomplete="off" required>
				                <label for="">pin</label>
				            </div>
			            	<input type="submit" name="register">
			            </div>
			        </form>
				';
			} else{ $sid = logged();
					echo '<div class="box">You are logged ' . read_name($sid) . "<br>";
					echo "You have " . read_coin($sid) . " bkc <br>";
					echo "You wallet id: " . read_wallet($sid) . "<br>";
					echo '<a href="mineblock.php">MINE BLOCK</a><br>';
					echo '<a href="trade.php">TRADE BKCOIN (BKC)</a><br>';
					echo '<a href="?logout=1">Log out</a></div>';
					echo '<form method="POST">
						<div class="box">
							<h3>Change Pass</h3>
				            <div class="input-box">
				                <input type="text" name="old_pass" autocomplete="off" required>
				                <label for="">Old password</label>
				            </div>

				            <div class="input-box">
				                <input type="text" name="new_pass" autocomplete="off" required>
				                <label for="">New password</label>
				            </div>

			            	<input type="submit" name="changepass">
			            </div>
			        </form>
					';
					echo '<form method="POST">
						<div class="box">
							<h3>Change Pin</h3>
				            <div class="input-box">
				                <input type="text" name="old_pin" autocomplete="off" required>
				                <label for="">Old pin</label>
				            </div>

				            <div class="input-box">
				                <input type="text" name="new_pin" autocomplete="off" required>
				                <label for="">New pin</label>
				            </div>

			            	<input type="submit" name="changepin">
			            </div>
			        </form>
					';
				}
		?>
        

	</body>
</html>

<?php

if (isset($_GET['logout'])){
    logout();
    header('Location: http://127.0.0.1/blockchain/login.php');
}

if(isset($_POST['login'])){ 

	$user = $_POST['user'];
	$pass = $_POST['pass'];

	$file = fopen("store/users.txt", "r");
	while(! feof($file))
	{
		$str = explode("#",fgets($file));
		if ($str[0] != ""){
			if ($str[1] == $user){
				$text1 = $str[0] . "#" . $pass;
				$hash = hash('sha256', $text1);
				if ($hash == $str[2]){
					$_SESSION["logged"] = $str[4];
					header("Location:index.php");
				} else{echo '<div class="box">Wrong username or password!</div>';}
			}
	    }
	}
	fclose($file);
}
if(isset($_POST['register'])){ 

	$userr = $_POST['userr'];
	$passr = $_POST['passr'];
	$pinr = $_POST['pinr'];

	$LastLine = read_last_line("store/users.txt");
  	$str = explode("#",$LastLine);

	if (check_u($userr) == 1){
		if (strlen($passr) > 7){
			if (strlen($pinr) == 6){
				create_user($str[0], $userr, $passr, $pinr);
				header("Location:index.php");
			} else{echo '<div class="box">Pin must 6 digits!</div>';}
		} else{echo '<div class="box">Password must higher or equal 8 digits!</div>';}
	} else{echo '<div class="box">This username aldready exist!</div>';}

}
if (isset($_POST['changepin'])){
    $old_pin = $_POST['old_pin'];
	$new_pin = $_POST['new_pin'];
	$sid = logged();
	if (strlen($new_pin) == 6) {
		change_pin($sid, $old_pin, $new_pin);
	} else{echo '<div class="box">New password must == 6 digits!</div>';}
}
if (isset($_POST['changepass'])){
    $old_pass = $_POST['old_pass'];
	$new_pass = $_POST['new_pass'];
	$sid = logged();
	if (strlen($new_pass) > 7) {
		change_pass($sid, $old_pass, $new_pass);
	} else{echo '<div class="box">New password must >= 8 digits!</div>';}	 
}
?>