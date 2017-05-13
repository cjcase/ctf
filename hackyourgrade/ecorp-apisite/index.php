<?php
	
	session_start();

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){
		//fuck you wellick, good enough for NSA is good enough for me
		/*$salt = base64_encode(md5($_POST['user']));
		$salt = substr($salt, -16);
		$pass = $_POST['pass'];*/

		//no, fuck you and your shitty code bartmoss, I just patched a blatant sqli
		$db_filename = "ecorp.db";
		$db = new SQLite3($db_filename);

		// no sqli here
		$user = $db -> escapeString($_POST['user']);
		$pass = $db -> escapeString($_POST['pass']);

		//query 1
		$prepared = $db -> prepare("SELECT uid, uHash, uFirstName, uLastName FROM eUser WHERE uLogin=:login;");
		$prepared  -> bindValue(":login", $user, SQLITE3_TEXT);
		$result = $prepared -> execute();

		//check user & key
		if($result -> numColumns() > 0){
			
			//get hash from db
			$row = $result -> fetchArray();
			$hash_db = $row['uHash'];
			$uid = $row['uid'];
			$fname = $row['uFirstName'];
			$lname = $row['uLastName'];

			//recreate hash with key
			$salt = base64_encode(md5($user));
			$salt = substr($salt, -16);
			$hash = crypt($pass, "$5$".$salt."$");

			if($hash == $hash_db){
				$_SESSION['logged_in'] = true;
				$_SESSION['login'] = $user;
				$_SESSION['uid'] = $uid;
				$_SESSION['lname'] = $lname;
				$_SESSION['fname'] = $fname;
			} else {
				$_SESSION['logged_in'] = false;
				$_SESSION['alert'] = "User or Password not found!";
			}
		} else {
			$_SESSION['logged_in'] = false;
			$_SESSION['alert'] = "User or Password not found!";
		}
	}

	if ($_SESSION['logged_in'] == true) {
		header('Location: msg.php');
	}

	include 'header.html';
?>
		<div class="container">

			<div class="col-md-4 col-md-offset-4">
				<img class="img img-responsive" src="img/icon.jpg">
			</div>


			<div class="col-md-4 col-md-offset-4">
			<?php
			if (isset($_SESSION['alert'])) {
				$msg = $_SESSION['alert'];
				unset($_SESSION['alert']);
				echo "<div class='alert alert-danger alert-dismissible' role='alert'>";
			  	echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Warning!</strong>';
			  	echo " ".$msg."\n"."</div>";
			  	}
			?>
				<form class="form-signin" method="POST">
					<label for="user" class="sr-only">Login</label>
					<input type="text" id="user" name="user" class="form-control" placeholder="Login" required autofocus>
					<label for="pass" class="sr-only">Password</label>
					<input type="password" id="pass" name="pass" class="form-control" placeholder="Password" required>
					<div class="checkbox">
						<label>
							<input type="checkbox" value="remember-me"> Remember me
						</label>
					</div>
					<button class="btn btn-lg btn-default btn-block" type="submit">Sign in</button>
				</form>
			</div>
		</div>

<?php
	include 'footer.html';
?>