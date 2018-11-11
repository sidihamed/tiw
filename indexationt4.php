<?php

	$source_html="source.html";
	//les metas  keywords + description
	$tab_metas=get_meta_tags($source_html);
	echo $tab_metas["keywords"];
	echo " <br/>";
	echo $tab_metas["description"];
	
?>