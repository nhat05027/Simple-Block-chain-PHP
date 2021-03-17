<?php

function read_line ($file_path, $id){
	$file = fopen($file_path, "r");
	while(! feof($file))
	  {
	  $raw = fgets($file);
	  $str = explode("#",$raw);
	  if ($str[0] != "" && $str[0] == $id){
	    //echo "id=" . $str[0] . ", prevhash=" . $str[1] . ", ledge=" . $str[2] . ", nonse=" . $str[3] . ", hash=" . $str[4];
	    return $raw;
	    }
	  }
	fclose($file);
}

function read_last_line ($file_path){
	$line = '';
	$f = fopen($file_path, 'r');
	$cursor = -1;
	fseek($f, $cursor, SEEK_END);
	$char = fgetc($f);
	while ($char === "\n" || $char === "\r") {
	    fseek($f, $cursor--, SEEK_END);
	    $char = fgetc($f);
	}
	while ($char !== false && $char !== "\n" && $char !== "\r") {
	    $line = $char . $line;
	    fseek($f, $cursor--, SEEK_END);
	    $char = fgetc($f);
	}
	fclose($f);
	$line = substr($line, 0, -1);
	return $line;
}

function logout(){
    session_destroy();
}

function logged()
{
$sid = "";
$file = fopen("store/users.txt", "r");
	while(! feof($file))
	{
		$str = explode("#",fgets($file));
		if ($str[0] != "" and isset($_SESSION["logged"])){
			if ($_SESSION["logged"] == $str[4]){
				$sid = $str[0];
			}
		}
  	}
fclose($file);  
return $sid;			
}

function read_name ($id){
    $file = fopen("store/users.txt", "r");
    while(! feof($file))
    {
    	$str = explode("#",fgets($file));
    	if ($str[0] != "" && $str[0] == $id){
    		return $str[1];
        }
    }
    fclose($file);
}

function read_wallet ($id){
    $file = fopen("store/users.txt", "r");
    while(! feof($file))
    {
    	$str = explode("#",fgets($file));
    	if ($str[0] != "" && $str[0] == $id){
    		return $str[4];
        }
    }
    fclose($file);
}

function read_coin ($id){
    $file = fopen("store/users.txt", "r");
    while(! feof($file))
    {
    	$str = explode("#",fgets($file));
    	if ($str[0] != "" && $str[0] == $id){
    		return base64_decode($str[5]);
        }
    }
    fclose($file);
}

function read_signature ($id){
    $file = fopen("store/users.txt", "r");
    while(! feof($file))
    {
    	$str = explode("#",fgets($file));
    	if ($str[0] != "" && $str[0] == $id){
    		return $str[3];
        }
    }
    fclose($file);
}

function check_wallet ($r_wallet){
	$check = 0;
    $file = fopen("store/users.txt", "r");
    while(! feof($file))
    {
    	$str = explode("#",fgets($file));
    	if ($str[0] != "" && $str[4] == $r_wallet){
    		$check = 1;
        } 
    }
    fclose($file);
    return $check;
}

function replace_a_line($data, $deltext, $addtext) {
   if (stristr($data, $deltext)) {
   	$x = $addtext."\n";
     return $x;
   }
   return $data;
}

function replacetext ($file, $deltext, $addtext){
	$data = file($file);
	for ($x = 0; $x < count($data); $x+=1){
		$a[$x] = $deltext;
		$b[$x] = $addtext;
	}
	$data = array_map('replace_a_line', $data, $a, $b);
	file_put_contents($file, implode('', $data));
}

function change_pass ($id, $old_p, $new_p){
	$file = fopen("store/users.txt", "r");
	$t = $id . "#" . $old_p;
	$t = hash('sha256', $t);
	$r = $id . "#" . $new_p;
	$r = hash('sha256', $r);
	while(! feof($file)){
		  $str = explode("#",fgets($file));
		  if ($str[0] != "" && $str[0] == $id){
		  	if ($str[2] == $t){
		  		$r_coin = base64_decode($str[5]);
		  		$r_old = $str[0] . "#" . $str[1] . "#" . $str[2] . "#" . $str[3] . "#" . $str[4] . "#" . $str[5];
		    	$r_new = $str[0] . "#" . $str[1] . "#" . $r . "#" . $str[3] . "#" . $str[4] . "#" . base64_encode($r_coin);
		    	replacetext('store/users.txt', $r_old, $r_new);
		    	echo '<div class="box">Sucess!</div>';
		  	} else {echo '<div class="box">Wrong old password!</div>';}
		  }
	}
}
function change_pin ($id, $old_p, $new_p){
	$file = fopen("store/users.txt", "r");
	$t = $id . "#" . $old_p;
	$t = hash('sha256', $t);
	$r = $id . "#" . $new_p;
	$r = hash('sha256', $r);
	while(! feof($file)){
		  $str = explode("#",fgets($file));
		  if ($str[0] != "" && $str[0] == $id){
		  	if ($str[3] == $t){
		  		$r_coin = base64_decode($str[5]);
		  		$r_old = $str[0] . "#" . $str[1] . "#" . $str[2] . "#" . $str[3] . "#" . $str[4] . "#" . $str[5];
		    	$r_new = $str[0] . "#" . $str[1] . "#" . $str[2] . "#" . $r . "#" . $str[4] . "#" . base64_encode($r_coin);
		    	replacetext('store/users.txt', $r_old, $r_new);
		    	echo '<div class="box">Sucess!</div>';
		  	} else {echo '<div class="box">Wrong old pin!</div>';}
		  }
	}
}

function coin_trade ($t_wallet, $t_signature, $r_wallet, $coin){

	$file = fopen("store/users.txt", "r");
	while(! feof($file))
		{
		  $str = explode("#",fgets($file));
		  if ($str[0] != "" && $str[4] == $r_wallet){
		    $r_coin = base64_decode($str[5]);
		    $r_id = $str[0];

		    $r_old = $str[0] . "#" . $str[1] . "#" . $str[2] . "#" . $str[3] . "#" . $str[4] . "#" . $str[5];
		    $r_new = $str[0] . "#" . $str[1] . "#" . $str[2] . "#" . $str[3] . "#" . $str[4] . "#";
		  }
		}
	fclose($file);
	$file = fopen("store/users.txt", "r");
	while(! feof($file))
	{
	  $str = explode("#",fgets($file));
	  if ($str[0] != "" && $str[4] == $t_wallet && $str[3] == $t_signature){
	    $t_coin = base64_decode($str[5]);
	    $t_id = $str[0];
	    if ($t_coin > $coin){
	    	$t_coin = $t_coin - $coin;
	    	$r_coin = $r_coin + $coin;

	    	$t_old = $str[0] . "#" . $str[1] . "#" . $str[2] . "#" . $str[3] . "#" . $str[4] . "#" . $str[5];
	    	$t_new = $str[0] . "#" . $str[1] . "#" . $str[2] . "#" . $str[3] . "#" . $str[4] . "#" . base64_encode($t_coin);

	    	$r_new = $r_new . base64_encode($r_coin);

	    	replacetext('store/users.txt', $t_old, $t_new);
	    	replacetext('store/users.txt', $r_old, $r_new);

	    	$text = "\n" . date('Y-m-d H:i:s'). ": " . $t_wallet . " send " . $coin . " bkc to " . $r_wallet . " verified signature " . $t_signature;
	    	$fp = fopen('store/ledge.txt', 'a+');
			fwrite($fp, $text);
			fclose ($fp);
	    }
	  }
	}
	fclose($file);
}

function coin_reward ($r_wallet, $coin, $blockid, $blockhash){

$file = fopen("store/users.txt", "r");
while(! feof($file))
	{
	  $str = explode("#",fgets($file));
	  if ($str[0] != "" && $str[4] == $r_wallet){
	    $r_coin = base64_decode($str[5]);
	    $r_coin += $coin;
	    $old = $str[0] . "#" . $str[1] . "#" . $str[2] . "#" . $str[3] . "#" . $str[4] . "#" . $str[5];
	    $new = $str[0] . "#" . $str[1] . "#" . $str[2] . "#" . $str[3] . "#" . $str[4] . "#" . base64_encode($r_coin);
	    replacetext('store/users.txt', $old, $new);

	    $text = "\n" . date('Y-m-d H:i:s') . ": Reward " . $coin . " bkc to " . $r_wallet . " for mine block id: " . $blockid . " with hash: " . $blockhash;
    	$fp = fopen('store/ledge.txt', 'a+');
		fwrite($fp, $text);
		fclose ($fp);
	  }
	}
fclose($file);
}

function create_block($id, $prevhash){
	$ledgeA = file('store/ledge.txt');
	$ledge ="";
	for ($i = 0; $i < count($ledgeA); $i +=1){
		$ledge = $ledge . $ledgeA[$i] . "|";
	}
	$ledge = str_replace("\n", "", $ledge);
	$id += 1;
	$text = "\n" . $id . "#" . $prevhash . "#" . $ledge . "##";
	$fp = fopen('store/block.txt', 'a+');
    fwrite($fp, $text);
    fclose ($fp);

    $text1 = date('Y-m-d H:i:s') . ": Block created id: " . $id;
    $fp = fopen('store/ledge.txt', 'w');
	fwrite($fp, $text1);
	fclose ($fp);
}

function create_user($id, $user, $pass, $pin){
	
	$id += 1;
	$pas = $id . "#" . $pass;
	$pi = $id . "#" . $pin;
	$wallet = $id . "#" . $user;

	$pass = hash('sha256', $pas);
	$pin = hash('sha256', $pi);

	$text = "\n" . $id . "#" . $user . "#" . $pass . "#" . $pin . "#" . md5($wallet) . "#MA==";

	$fp = fopen('store/users.txt', 'a+');
    fwrite($fp, $text);
    fclose ($fp);

    $text1 = "\n" . date('Y-m-d H:i:s') . ": New user created: ". $user . ", with id: " . $id;
    $fp = fopen('store/ledge.txt', 'a+');
	fwrite($fp, $text1);
	fclose ($fp);
}

function a_encrypt($id, $text){
	$hash = md5($id) . "#" . base64_encode($text);
	$hash = base64_encode(base64_encode($hash));
	$hash = str_rot13($hash);
	return $hash;
}
function a_decrypt($id, $hash){
	$text = $hash;
	$hash = str_rot13($hash);
	$hash = base64_decode(base64_decode($hash));
	$str = explode("#", $hash);
	if (md5($id) == $str[0]){
		$text = base64_decode($str[1]);
	} 
	return $text;
}

function check_u($user){
	$file = fopen("store/users.txt", "r");
	while(! feof($file))
	{
		$str = explode("#",fgets($file));
		if ($str[1] == $user){
			return 0;
	    }
	}
	return 1;
	fclose($file);
}

?>