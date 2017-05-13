<?php
	session_start();
	if ( empty($_SESSION['logged_in'])) {
		$_SESSION['alert'] = "Please log in to enter private area";
		header("Location: index.php");
	}
	include 'header.html';
	include 'navbar.html';

	// query
	// select m.msg, u.uLogin, m.read from eMsg as m, eUser as u where m.uid_sender == 8 AND m.uid_recv == u.uid AND m.deleted == 0 order by m.id desc;

	$db_filename = "ecorp.db";
	$db = new SQLite3($db_filename);

	function numRows($r){
		$count = 0;
		while($r -> fetchArray()){
			$count++;
		}
		return $count;
	}

?>

	<div class="container">
		<h1>Messages &amp; Notes</h1>
		<div class="text-right">
			<form class="form form-inline" method="POST" action="search.php">
				<input type="text" name="s" id="s" class="form-control" placeholder="Search" required>
				<input type="submit" value="Search" class="form-control btn btn-default">
			</form>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active">
				<a href="#inbox" aria-controls="home" role="tab" data-toggle="tab">
					<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
					Inbox
				</a>
			</li>
			<li role="presentation">
				<a href="#sent" aria-controls="profile" role="tab" data-toggle="tab">
					<span class="glyphicon glyphicon-sent" aria-hidden="true"></span>
					Sent Messages
				</a>
			</li>
			<li role="presentation">
				<a href="#notes" aria-controls="profile" role="tab" data-toggle="tab">
					<span class="glyphicon glyphicon-clipboard" aria-hidden="true"></span>
					Notes
				</a>
			</li>
		</ul>
	</div>

	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active container" id="inbox">
			<h3>Unread Messages</h3>
			<div class="table-responsive">
				<table class="table">
				<?php
					$result = $db -> query("SELECT u.uLogin, m.msg from eMsg as m, eUser as u where m.uid_recv == ".$_SESSION['uid']." AND u.uid == m.uid_sender AND m.deleted == 0 AND m.read == 0 order by m.id desc;");
					if(numRows($result) > 0){
						echo "<th>Sender</th><th>Message</th>";
						while($row = $result -> fetchArray()){
							//Escape and format stuff
							$msg = htmlspecialchars($row[1]);
							$msg = str_replace("\n", "<br>", $msg);
							echo "<tr>";
							echo "<td>".$row[0]."</td>";
							echo "<td>".$msg."</td>";
							echo "</tr>";
						}						
					} else {
						echo "No unread messages";
					}
				?>
				</table>
			</div>
			<h3>Read Messages</h3>
			<div class="table-responsive">
				<table class="table">
				<?php
					$result = $db -> query("SELECT u.uLogin, m.msg from eMsg as m, eUser as u where m.uid_recv == ".$_SESSION['uid']." AND u.uid == m.uid_sender AND m.deleted == 0 AND m.read == 1 order by m.id desc;");
					if(numRows($result) > 0){
						echo "<th>Sender</th><th>Message</th>";
						while($row = $result -> fetchArray()){
							//Escape and format stuff
							$msg = htmlspecialchars($row[1]);
							$msg = str_replace("\n", "<br>", $msg);
							echo "<tr>";
							echo "<td>".$row[0]."</td>";
							echo "<td>".$msg."</td>";
							echo "</tr>";
						}						
					} else {
						echo "No read messages";
					}
				?>
				</table>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane container" id="sent">
			<div class="table-responsive">
				<table class="table">
				<?php
					$result = $db -> query("SELECT u.uLogin, m.msg, m.read from eMsg as m, eUser as u where m.uid_sender == ".$_SESSION['uid']." AND u.uid == m.uid_recv AND m.deleted == 0 order by m.id desc;");
					if(numRows($result) > 0){
						echo "<th>Receiver</th><th>Message</th><th>Status</th>";
						while($row = $result -> fetchArray()){
							//Escape and format stuff
							$msg = htmlspecialchars($row[1]);
							$msg = str_replace("\n", "<br>", $msg);
							echo "<tr>";
							echo "<td>".$row[0]."</td>";
							echo "<td>".$msg."</td>";
							if ($row[2] == 0){
								echo "<td>Unreaded</td>";
							} else  {
								echo "<td>Readed</td>";
							}
							echo "</tr>";
						}						
					} else {
						echo "No sent messages";
					}
				?>
				</table>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane container" id="notes">
			<div class="table-responsive">
				<table class="table">
				<?php
					$result = $db -> query("SELECT note from eNote where uid = ".$_SESSION['uid']." and deleted = 0");
					if(numRows($result) > 0){
						echo "<th>Note</th>";
						while($row = $result -> fetchArray()){
							//Escape and format stuff
							$msg = htmlspecialchars($row[0]);
							$msg = str_replace("\n", "<br>", $msg);
							echo "<tr>";
							echo "<td>".$msg."</td>";
							echo "</tr>";
						}						
					} else {
						echo "No saved notes";
					}
				?>
				</table>
			</div>
		</div>
	</div>

<?php
	include 'footer.html';
?>
