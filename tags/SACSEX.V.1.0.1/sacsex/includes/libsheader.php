<?php
echo "<script type='text/javascript' src='js/main.js'></script>"; 
echo "<script type='text/javascript' src='js/jquery.js'></script>";
echo "<script type='text/javascript' src='js/jquerymd5.js'></script>";

echo "<script type='text/javascript'>
		if ($.browser.msie){
			document.write(\"<link rel='stylesheet' type='text/css' href='css/style-ie.css'/>\");
		}else{
			document.write(\"<link rel='stylesheet' type='text/css' href='css/style.css'/>\");
		}
	</script> ";

?>