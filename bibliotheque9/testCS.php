<?php

// Mise en bdd des resultats de l'indexation

function insertion_BDD($source_html, $titre, $descriptif, $tab_mots_poids_doc){
	
	$connexion = mysqli_connect("localhost","root","","tiw");
	$idMot = 0; $idSource = 0;
	$select_document = "SELECT * FROM document WHERE source = '".$source_html."' and titre = '".$titre."' ";
	$resultats_select_document = mysqli_query($connexion,$select_document);
	
	//Ajouter un document à la base de données
	if (mysqli_num_rows($resultats_select_document)==0) {

	   $insert_document = " insert into document(source,titre,descriptif) values ('$source_html','$titre','$descriptif') ";

	   $resultats_insert_document = mysqli_query($connexion,$insert_document);		  
		   if ($resultats_insert_document) {
			   echo "Le document:  ".$source_html."  a été ajouté<br>";
			   $idSource = mysqli_insert_id($connexion);
		   }
		   else{
			  echo "Erreur d'insertion dans la BDD <br>";
		   }
	}
	else{     
	   $idSource = mysqli_fetch_row($resultats_select_document)[0];
	   echo "Le document:   $source_html   existe déja dans la BDD","<br>";
	}

	//Ajouter un mot à la base de données
	foreach ($tab_mots_poids_doc as $mot => $poids) {
	   $select_mot = "SELECT * FROM mot WHERE mot = '".$mot."' ";
	   $resultats_select_mot = mysqli_query($connexion,$select_mot);
	  
	   if (mysqli_num_rows($resultats_select_mot)==0) {

		   $insert_mot = " insert into mot(mot) values ('$mot') "; 
		   $resultats_insert_mot = mysqli_query($connexion,$insert_mot);
		   if ($resultats_insert_mot) {
			   echo "Le mot:  ".$mot."  a été ajouté<br>";
			   $idMot = mysqli_insert_id($connexion);
		   }
		   else{
			   echo "Erreur d'insertion dans la BDD <br>";
		   }
	   }
	   else{     
		   $idMot = mysqli_fetch_row($resultats_select_mot)[0];
		   echo "Le mot:   $mot   existe dèja dans la BDD","<br>";
		}             
		//insertion dans la table d'association
		$select_mot_document = "SELECT * FROM mot_document WHERE idMot = '".$idMot."' and idSource = '".$idSource."' ";

		$resultats_select_mot_document = mysqli_query($connexion,$select_mot_document);

		if (mysqli_num_rows($resultats_select_mot_document)==0) {

		   $sql = "INSERT INTO mot_document (idMot, idSource, poids) VALUES ($idMot, $idSource, $poids)";

		   $resultat = mysqli_query($connexion, $sql);

		   if ($resultat) {

			  echo " Succes d'insertion dans la table d'association <br>";
		   }
		   else{
			  echo " Erreur d'insertion dans la table d'association <br>";
		   }
		}
	}
}
	
?>
