<html>
<head></head>
<body>
<?php 

if (isset($_GET['password'])){
	echo md5($_GET['password']);
}
?>
<form action="gindex.php" method="GET">
<table>

		<td>Password</td>
		<td><input type="text" name="password"></td>

<input type='submit'>
</form>
</body>
</html>
