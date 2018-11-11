
<?php

	//$chaine = "Bonjour tout le monde.  L'université de Paris8, est ouverte toute l'année";
	$chaine = implode( file("source.txt"), " ");
	$chaine = utf8_encode($chaine);
	$separateur =". ,'!?;";
	$tab_mots = explode_bis($separateur,$chaine);
	print_tab($tab_mots);

	function explode_bis($separateur,$chaine)
	{
	$tab = array();
	$tok =strtok($chaine ,$separateur);
	if (strlen($tok) > 2) $tab[] =$tok;
	while ($tok !== false)
	{
	    $tok = strtok($separateur);
	    if ( strlen($tok) >2) $tab[] = $tok;  
	} 
	return $tab;
	}
	
	function print_tab($tab)
	{
	    foreach($tab as $indice => $mot ) echo $indice, ":" ,$mot ,"<br />";
	}

?>