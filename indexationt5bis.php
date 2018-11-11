<?php

	$source_html="source.html";
	echo get_title($source_html);

	function get_title($source_html){
	    $chaine_html=implode(file ($source_html),"");
		$modele='/<title>(.*)<\/title>/si';
		preg_match($modele, $chaine_html, $titre);
		return $titre[1] ;
	}
	
?>