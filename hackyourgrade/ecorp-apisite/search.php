<?php
	session_start();
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		
			if ( empty($_SESSION['logged_in'])) {
				$_SESSION['alert'] = "Please log in to enter private area";
				header("Location: index.php");
			}
			include 'header.html';
			include 'navbar.html';

			$db_filename = "ecorp.db";
			$db = new SQLite3($db_filename);

	} else {
		return http_response_code(400);
		exit();
	}

	function numRows($r){
		$count = 0;
		while($r -> fetchArray()){
			$count++;
		}
		return $count;
	}

?>

<div class="container">
	<a class="btn btn-default" href="msg.php">Return to messages</a>
	<h".$uid.">Search results for <?php echo $_POST['s']?></h".$uid.">
	<hr>

	<div class="table-responsive">
		<table class="table">
			<?php
				$uid = $_SESSION['uid'];
				$s = $_POST['s'];
				$q = "SELECT s.uLogin, r.uLogin, m.msg FROM eMsg as m, eUser as s, eUser as r WHERE m.deleted == 0 AND m.uid_recv == ".$uid." AND s.uid == m.uid_sender AND r.uid == m.uid_recv AND m.msg like '%".$s."%' UNION SELECT s.uLogin, r.uLogin, m.msg FROM eMsg as m, eUser as s, eUser as r WHERE m.deleted == 0 AND m.uid_sender == ".$uid." AND s.uid == m.uid_sender AND r.uid == m.uid_recv AND m.msg like '%".$s."%';";
				$result = $db -> query($q);
				if(numRows($result) > 0){
					echo "<th>Sender</th><th>Receiver</th><th>Message</th>";
					while($row = $result -> fetchArray()){
						//Escape and format stuff
						echo "<tr>";
						echo "<td>".$row[0]."</td>";
						echo "<td>".$row[1]."</td>";
						echo "<td>".$row[2]."</td>";
						echo "</tr>";
					}						
				} else {
					echo "No results!";
				}
			?>
		</table>
	</div>
</div>

<?php
	include 'footer.html';
?>