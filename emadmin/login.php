<?php 

if (isset($_POST[usuario] ) && isset($_POST[password])){
	if ( $_POST[usuario] == "emsa"  && $_POST[password] == "3ms42009" ){ //ZASSSSCUTRADAAA XDD
		$_SESSION['login']= true;
		include 'index.php';
		die;
	}else{
		$_SESSION['login']= false;
	}
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Login Administración Web EMSA 2009</title>
<link rel='stylesheet' type='text/css' href='css/main.css'></link>
<script type="text/javascript">

$(function(){

});
</script>
</head>
<body>
<form action="login.php" method='POST'>
	<div class='login'>
		<div class='text' > Usuario  <input type="text" name='usuario'/></div>
		<div class='text' > Password  <input type="password" name='password' /> </div>
		<br>
		<div><input type='submit' value='Login'/></div>
	</div>
</form>
</body>
</html>
