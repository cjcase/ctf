<?php

	if ($_SERVER['REQUEST_METHOD'] == 'POST'){

		if (isset($_POST['apiuser']) && isset($_POST['apikey']) && isset($_POST['api'])){
			
			//All set, lets do this
			$db_filename = "ecorp.db";
			$db = new SQLite3($db_filename);

			// no sqli here
			$apiuser = $db -> escapeString($_POST['apiuser']);
			$apikey = $db -> escapeString($_POST['apikey']);
			$api = $db -> escapeString($_POST['api']);

			//query 1
			$prepared = $db -> prepare("SELECT uid, uHash FROM eUser WHERE uLogin=:login;");
			$prepared  -> bindValue(":login", $apiuser, SQLITE3_TEXT);
			$result = $prepared -> execute();

			//check user & key
			if($result -> numColumns() > 0){
				
				//get hash from db
				$row = $result -> fetchArray();
				$hash_db = $row['uHash'];
				$uid = $row['uid'];

				//recreate hash with key
				$salt = base64_encode(md5($apiuser));
				$salt = substr($salt, -16);
				$hash = crypt(base64_decode($apikey), "$5$".$salt."$");

				//compare hashes
				if ($hash == $hash_db) {
					
					$api($db, $uid);

				} else {
					return http_response_code(403);
				}
				
			} else {
				return http_response_code(403);	
			}

		} else {
			return http_response_code(403);
		}

	} else {
		return http_response_code(400);
	}

	function get_unread_msg_count($db, $id){

		// REALLY HARD SQLi (not maleable)
		$result = $db -> query("SELECT COUNT(msg) FROM eMsg WHERE uid_recv == ".$id." AND read == 0 AND deleted == 0;");
		if($result -> numColumns() > 0){
			$row = $result -> fetchArray();
			echo $row[0]." unread message(s)";
		} else {
			echo "No unread messages";
		}

	}

	function get_unread_msg($db, $id){
		// STILL REALLY HARD (not maleable)
		$results = $db -> query("SELECT msg FROM eMsg WHERE uid_recv == ".$id." AND read == 0 AND deleted == 0;");
		$msgs = array();
		if ($results -> numColumns() > 0) {
			while( $row = $results -> fetchArray() ) {
				$msgs[] = $row["msg"];
			}
		}
		echo var_dump($msgs);
	}

?>