<?php
	// notepad.pw/tiw
	$source_html = "source.html";

	// Affichage du retour de la fonction
	echo get_body($source_html);

	// Extraction du body en texte
    function get_body($source_html)
	{	
		$chaine_html=implode(file ($source_html),"");
		
		$modele='/<body[^>]*>(.*)<\/body>/si';

		// $body[1] contient le texte sans le model 
		preg_match($modele, $chaine_html, $body);

		// Supprime les balise HTML et PHP
		$chaine_texte_body = strip_tags($body[1]);

		return $chaine_texte_body;
	}
?>
	