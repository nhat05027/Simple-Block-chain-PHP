<?php
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

        <div class="box">
            <?php
                if (logged() != ""){
                    $sid = logged();
                    echo "Hello " . read_name($sid) . " you have " . read_coin($sid) . " bkcoin!";
                    echo '
                        <form method="POST">
                            <div class="input-box">
                                <input type="text" name="r_wallet" autocomplete="off" required>
                                <label for="">Receive Wallet</label>
                            </div>
                            <div class="input-box">
                                <input type="number" step="0.01" name="amount" autocomplete="off" required>
                                <label for="">Coin</label>
                            </div>
                            <div class="input-box">
                                <input type="number" name="pin" maxlength="6" pattern="\d{6}" autocomplete="off" required>
                                <label for="">Pin Code</label>
                            </div>
                            <input type="submit" name="trade">
                        </form>
                    ';
                }
                else{echo 'Login required!';}
            ?>
        </div>

	</body>
</html>

<?php

    if(isset($_POST['trade'])){ 
        $r_wallet = $_POST['r_wallet'];
        $t_wallet = read_wallet($sid);
        $coin = $_POST['amount'];
        $t_sig = $sid . "#" . $_POST['pin'];
        $t_signature = hash('sha256', $t_sig);
        if ($coin > 0 and $coin <= read_coin($sid)){
            if ($t_signature == read_signature($sid)){
                if (check_wallet($r_wallet) == 1){
                    if ($t_wallet != $r_wallet){
                        coin_trade ($t_wallet, $t_signature, $r_wallet, $coin);
                        echo '<div class="box">Success send ' . $coin . ' bkc to wallet id: ' . $r_wallet . '</div>';
                    } else {echo '<div class="box">You cant send coin from u wallet to u wallet!</div>';}
                } else {echo '<div class="box">No receive wallet found!</div>';}
            } else {echo '<div class="box">Wrong pin code!</div>';}
        } else {echo '<div class="box">Too much coin than you have!</div>';}
    }

?>
