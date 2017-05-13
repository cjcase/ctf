<?php
	include 'header.html';
?>
<div class="container">
<?php
	echo "<h3>Password Hash Test Page</h3><hr>";
	// $db_filename = "ecorp.db";
	// $db = new SQLite3($db_filename);

	// //query 1
	// $result = $db->query("SELECT name, sql FROM sqlite_master WHERE type='table' ORDER BY name;");
	// if($result){
	// 	while ($row = $result->fetchArray()) {
 //    		echo $row['name']." -> ".$row['sql']."<br>";
	// 	}
	// }
	// echo "<hr>";
	// //query2
	// $result = $db -> query("SELECT * FROM eUser");
	// if($result){
	// 	while ($row = $result->fetchArray()) {
	// 		echo $row['uid']." : ".$row['uLogin']." : ".$row['uHash']."<br>";
	// 	}
	// }
	// echo "<hr>";
?>
	<form action="" method="post" class="form-inline">
		<input class="form-control" type="text" name="user" placeholder="Login">
		<input class="form-control" type="text" name="pass" placeholder="Password">
		<input class="form-control" type="submit" value="Generate Hash">
	</form>

<?php
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){
		$salt = base64_encode(md5($_POST['user']));
		$salt = substr($salt, -16);
		$pass = $_POST['pass'];

		// $1$ is md5 -> WEAKSAUCE
		// $5$ is sha256 -> meh, good enough for NSA
		// $6$ is sha512 -> as SECURE as pw
		echo "<p>".crypt($pass, "$5$".$salt."$")."</p>";
		echo "<p>Verify: ".crypt($pass, "$5$".$salt."$")."</p>";
	}
?>

</div>
<?php
	include 'footer.html';
?>