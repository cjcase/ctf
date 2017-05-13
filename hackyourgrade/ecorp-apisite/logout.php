<?php
	session_start();
	include 'header.html';
	session_destroy();
?>

<div class="container">
	<div class="col-md-6 col-md-offset-3">
		<h3>You've been logged out</h3>
		<hr>
		<p>Thanks for using ECorp services.</p>
		<a href="/">Login</a>
	</div>
</div>

<?php
	include 'footer.html';
?>