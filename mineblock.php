<?php
// Start the session
session_start();

$diff = 5;
$reward = 20;

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

  			$LastLine = read_last_line("store/block.txt");
  			$str = explode("#",$LastLine);
  			$ledges = explode("|", $str[2]);
	        $ledge = "";
	        for ($k=0; $k < (count($ledges)-1); $k++) { 
	            $ledge = $ledge . "<span>". $ledges[$k] . "</span><br><br>";
	        }
	        $text1 = "id=" . $str[0] . "; prevhash=" . $str[1] . "; ledge=" . $str[2] . "; nonse=";
  			echo '<div class="box">
  					<h2>Current Block</h2>
                    <h3><strong>#ID: '.$str[0].'</strong></h3><br>
                    <strong>Prev_Hash</strong>
                    <p>'.$str[1].'</p>
                    <strong>Ledge</strong><br><br>
                    '.$ledge.'
                    <strong>Nonse</strong>
                    <p>Null</p>
                    <strong>Hash</strong>
                    <p>Null</p>
                    <strong>Text to solve</strong>
                    <p>'.$text1.'</p>
                    <p>Difficult: '.$diff.'</p>
                    <p>Reward: '.$reward.' bkc</p>
              </div>';

  			if ($str[3] == ""){
  				echo '<form method="POST">
  					<div class="box">
  					<h2>Solve Block</h2>
			            <div class="input-box">
			                <input type="text" name="nonse" autocomplete="off" required>
			                <label for="">Nonse</label>
			            </div>
			            <input type="submit" name="mine"><br>
			        </form>';
  			} 
  			else{
  				echo '<div class="box">No block! Please wait..</div>';
  			}
        ?>
    </div>
        <div class="box">
  			<h3>App python</h3>
  			<a href="app miner/main.exe">Download exe</a><br>
  			<a href="app miner/main.py">Download py code</a>
        </div>

	</body>
</html>

<?php
	if(isset($_POST['mine']))
	{
		if (logged() != ""){
			$sid = logged();
			$nonse = $_POST['nonse'];
			$str = explode("#",$LastLine);

			$text1 = "id=" . $str[0] . "; prevhash=" . $str[1] . "; ledge=" . $str[2] . "; nonse=" . $nonse;
			$hash = hash('sha256', $text1);
			$check = substr($hash, 0, $diff);
			$ct = "";
			for ($x = 0; $x < $diff; $x +=1){
				$ct = $ct . "0";
			}
			if ($check == $ct)
			{
				$text = $str[0] . "#" . $str[1] . "#" . $str[2] . "#" . a_encrypt($sid, $nonse) . "#" . $hash;
				$lines = file('store/block.txt');
				array_pop($lines);
				$file = join('', $lines);

				$fp = fopen('store/block.txt', 'w');
				fputs($fp, $file);
				fwrite($fp, $text);
				fclose ($fp);

				$r_wallet = $_SESSION["logged"];
				$blockid = $str[0];
				$blockhash = $hash;
				coin_reward($r_wallet, $reward, $blockid, $blockhash);
				create_block($str[0], $hash);
				echo '<div class="box">Reward ' . read_name($sid) . " " . $reward . " bkcoin!<br>
				With nonse = ".$nonse." hash is: ".$hash. "</div>";
			}
			else {
				echo '<div class="box">Wrong nonse!! sorry <br>';
				//echo $text1."<br>";
				echo "With nonse = ".$nonse." hash is: ".$hash. "</div>";
			}
		}
		else{echo '<div class="box">Login required!</div>';}
	}
?>