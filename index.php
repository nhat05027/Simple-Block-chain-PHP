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
        
        <?php
            for ($i = 1; $i < count(file("store/block.txt")); $i += 1){
			  $id = $i;
              $data = explode("#", read_line("store/block.txt", $id));
              for ($j = 0; $j < 5; $j+=1){
                if ($data[$j] == ""){
                    $data[$j] = "Null";
                }
              }
              $ledges = explode("|", $data[2]);
              $ledge = "";
              for ($k=0; $k < (count($ledges)-1); $k++) { 
                  $ledge = $ledge . "<span>". $ledges[$k] . "</span><br><br>";
              }
              for ($j = 0; $j < 5; $j+=1){
                if ($data[$j] == ""){
                    $data[$j] = "Null";
                }
              }
              if (a_decrypt(logged(), $data[3])== $data[3]){
                $mess = "Encypted";
              } else {$mess = "Decrypted";}
              echo '<div class="box">
                    <h3><strong>#ID: '.$data[0].'</strong></h3><br>
                    <strong>Prev_Hash</strong>
                    <p>'.$data[1].'</p>
                    <strong>Ledge</strong><br><br>
                    '.$ledge.'
                    <strong>Nonse ('.$mess.')</strong>
                    <p>'.a_decrypt(logged(), $data[3]).'</p>
                    <strong>Hash</strong>
                    <p>'.$data[4].'</p><br>
              </div>';

			}
        ?>

	</body>
</html>