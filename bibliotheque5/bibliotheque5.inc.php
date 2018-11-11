<?php

	// Tokenization de la chaine en mot
	function explode_bis($separateur,$chaine){
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

	// Affichage d'un tableau avec indices et valeurs
	function print_tab($tab){
	    foreach($tab as $indice => $mot ) echo $indice, ":" ,$mot ,"<br />";
	}

	//Extraction des keywords et description des metas html
	function get_metas_keywords_description($source_html){
		//les metas  keywords + description
		$tab_metas=get_meta_tags($source_html);
		return $tab_metas["keywords"]." ".$tab_metas["description"];
	}

	// Extraction de title html
	function get_title($source_html){
	    $chaine_html=implode(file ($source_html),"");
		$modele='/<title>(.*)<\/title>/si';
		//preg_match  elle cherche modele dans chaine_html est elle va le mettre dans titre
		preg_match($modele, $chaine_html, $titre);
		return $titre[1] ;
	}

	// Extraction du body en texte
	function get_body($source_html)
	{	
		$chaine_html=implode(file ($source_html),"");

		$modele_balises_scripts = '/<script[^>]*?>.*?<\/script>/is'; 

		$html_sans_script = preg_replace($modele_balises_scripts, '', $chaine_html) ;

		$modele='/<body[^>]*>(.*)<\/body>/si';

		$chaine_texte_body = preg_match($modele, $html_sans_script, $body);

		// Supprime les balise HTML et PHP
		$chaine_texte_body = strip_tags($body[1]);

		return $chaine_texte_body;
	}

?>