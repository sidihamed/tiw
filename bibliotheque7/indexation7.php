<?php
	
	//inclusion de fonctions prédefinies
	include '../bib_funs.inc.php';

	//séparateur tokenisation
	$separateurs = " \".',«’!?;:&-=+@#{}[]()0123456789 ";

	$source_html = "../source.html";

	$texte_body = get_body($source_html);

	//tokenisation des données head
	$tab_mots = explode_bis($separateurs , $texte_body); 

	// liste de mots avec leurs nombres d'occurrences.
	$tab_mots_occurrences = array_count_values($tab_mots);
	
	//affichage des resultats de traitement
	//print_tab($tab_mots_occurrences);

	// Mise en bdd les resultats de l'indexation 
	$connexion = mysqli_connect("localhost","root","","tiw");

	foreach ($tab_mots_occurrences as $mot => $poid) {

		$sql = " insert into source_mot_poid(source,mot,poid) 
        values('$source_html','$mot',$poid) ";

		$test = mysqli_query($connexion,$sql); 
		if ($test) {
			//echo $sql,"<br>";
		}
		else{
			echo "Erreur $sql <br>";
		}
		
	}

	mysqli_close($connexion); 
    
?>
	