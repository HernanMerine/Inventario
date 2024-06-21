<?php
	session_destroy();
	
	if(headers_sent()){
		echo "<script> window.location.href='index.php?vista=welcome'; </script>";
	}else{
		header("Location: index.php?vista=welcome");
	}