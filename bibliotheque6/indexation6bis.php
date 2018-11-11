<?php
	// notepad.pw/tiw
	$source_html = "source.html";

	// Affichage du retour de la fonction
	echo get_body($source_html);

	// Extraction du body en texte
    function get_body($source_html)
	{	
		$chaine_html=implode(file ($source_html),"");

		$modele_balises_scripts = '/<script[^>]*?>.*?<\/script>/is'; 

		$html_sans_script = preg_replace($modele_balises_scripts, '', $chaine_html) ;

		$modele='/<body[^>]*>(.*)<\/body>/si';

		$chaine_texte_body = preg_match($modele, $html_sans_script, $body);

		// Supprime les balise HTML, PHP, javascript ....
		$chaine_texte_body = strip_tags($body[1]);

		return $chaine_texte_body;
	}
?>
	