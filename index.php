<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Localhost</title>
	<style>
		body{background-color: #eee}
		iframe{min-height: 250px}
		img{display: block;margin: 0 auto;margin-bottom: 10px;width: 250px;text-align: center;}
		form{display: block;margin: 0 auto;text-align: center;}
		input{cursor: pointer;}
		input[type=text]{margin-top: 10px;text-align: center;padding: 5px 25px;border: 0;}
		input[type=submit]{display: block;margin: 5px auto;text-align: center;padding: 5px 25px;background-color: #20639b;color: #fff;text-transform: uppercase;border: 0;transition: 1s all}
		input[type=submit]:hover{background-color: #10395d;}
	</style>
</head>
<body>

	<img src="logo.jpg" alt="">
	
<?
	$koneksi = new PDO('mysql:host=localhost;dbname=gomunime', "root", "");
	$sql = "SELECT * FROM onepiece ORDER BY id_episode DESC LIMIT 1";
	$data = $koneksi->prepare($sql);
	$data->execute();
	$rows = $data->fetchAll(PDO::FETCH_ASSOC);

	function http_request($url){
	    $ch = curl_init(); 
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	    curl_setopt($ch, CURLOPT_URL, $url);
	    $output = curl_exec($ch); 
	    curl_close($ch);      
	    return $output;
	}

	if( isset($_POST['gomunime']) && isset($_POST['url']) ){
		$url = $_POST['url'];

		if( $_POST['gomunime'] == 'Next' ){		
		    $insert = "INSERT INTO onepiece (link_episode) VALUES ('$url')";
		    $koneksi->exec($insert);
		}

		$request = http_request($url); 
		$iframe = explode('<div id="embed_holder"><div class="player-embed" id="pembed">', $request);
		$iframe2 = explode('</iframe></div></div>', $iframe[1]);

		echo $iframe2[0]."</iframe>";

		$next = explode('<div class="nvs"><a href="', $request);
		$next2 = explode('" rel="next">Next »</a></div>', $next[2]);

?>
	<form method="POST" action="">
		<input type="text" name="url" value="<?= $next2[0] ?>" style="width: 30%">
		<input type="submit" name="gomunime" value="Next">
	</form>
<?

	}else{

		foreach ($rows as $value) {

			$request = http_request($value['link_episode']); 
			$iframe = explode('<div id="embed_holder"><div class="player-embed" id="pembed">', $request);
			$iframe2 = explode('</iframe></div></div>', $iframe[1]);

			echo $iframe2[0]."</iframe>";

			$next = explode('<div class="nvs"><a href="', $request);
			$next2 = explode('" rel="next">Next »</a></div>', $next[2]);

?>

			<form method="POST" action="">
				<input type="text" name="url" value="<?= $next2[0] ?>" style="width: 30%">
				<input type="submit" name="gomunime" value="Next">
			</form>

<?
		}
	}
?>
</body>
</html>
