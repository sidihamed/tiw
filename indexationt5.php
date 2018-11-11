<?php

	$chaine_html= "<title> ceci est un titre dans mon html </title>";
	$modele='/<title>(.*)<\/title>/si';
	//preg_match  elle cherche modele dans chaine_html est elle va le mettre dans titre
	preg_match($modele,$chaine_html,$titre);
	echo $titre[1] ;
	
?>