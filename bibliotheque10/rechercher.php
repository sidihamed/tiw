<?php 

	$query = $_POST["query"];

	$connexion = mysqli_connect("localhost","root","","tiw");
		
	$sql = "select * from mot where mot = '$query'";

	$resultat_select_mot = mysqli_query($connexion,$sql);

	echo "Resultat pour $query : <br>";

   
   if (mysqli_num_rows($resultat_select_mot)==0) {

	   echo "Le mot :   $query   n'existe pas dans la BDD","<br>";
	}
	else{ 

	   $idMot = mysqli_fetch_row($resultat_select_mot)[0];
	   $sql = "select idSource, poids from mot_document where idMot = '$idMot'";
	   $resultat_idSource_mot = mysqli_query($connexion,$sql);

	   
	}
	

?>