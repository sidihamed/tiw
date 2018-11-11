<?php

	$chaine =	 "Bonjour tout le monde.  L'université de Paris8, est ouverte toute l'année";
	$separateur =". ,'!?;";
	$tab_mots = explode_bis($separateur,$chaine);
	print_tab($tab_mots);


	function explode_bis($separateur,$chaine){
		$tab = array();
		$tok =strtok($chaine ,$separateur);
		if (strlen($tok) > 2) $tab[] = $tok;
		while ($tok !== false){
		    $tok = strtok($separateur);
		    if ( strlen($tok) >2) $tab[] = $tok;    
		} 
		return $tab;
	}
	// Affichage d'un tableau
	function print_tab($tab){
	    foreach($tab as $indice => $mot ) echo $indice, ":" ,$mot ,"<br />";
	}

?>