<?php

include '../bib_funs.inc.php';

	$chaine = " mot deux; trois;";
	$separateur = ";";
	$tok =strtok($chaine ,$separateur);
	
	print_r($tok);
	
?>
