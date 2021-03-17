<?php
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
        <form method="POST">
            <div class="box">

                <h2>Search History</h2>
                <div class="input-box">
                    <input type="text" name="id" autocomplete="off" required>
                    <label for="">Wallet ID</label>
                </div>

                <input type="submit" name="search">
            </div>
        </form>

        <div class="box">
        <h2>History</h2>
        <?php
            if(isset($_POST['search'])){
			  $w_id = $_POST['id'];
              echo '<h3>You search: '.$w_id.'</h3>';
              for ($i = 1; $i < count(file("store/block.txt")); $i += 1){
                      $id = $i;
                      $data = explode("#", read_line("store/block.txt", $id));
                      $ledges = explode("|", $data[2]);
                      for ($k=0; $k < (count($ledges)-1); $k++) { 
                        if (strpos($ledges[$k], $w_id) !== false){
                            echo $ledges[$k]."<br><br>";
                        }
                      }
              }
			}
        ?>
        </div>

	</body>
</html>