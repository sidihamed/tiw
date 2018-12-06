<?php 

	$query = $_POST["query"];

	$connexion = mysqli_connect("localhost","root","","tiw");
		
	$sql = "select * from source_mot_poids where mot = '$query'";

	$resultat = mysqli_query($connexion,$sql);

	echo "Resultat pour $query : <br>";
	
	while ($ligne = mysqli_fetch_row($resultat)) {
	 	
	 	echo $ligne[0], " : ", $ligne[2] ,"<br>";	
	 } 

?>