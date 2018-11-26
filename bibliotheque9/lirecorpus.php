<P>
<B>DEBUTTTTTT DU PROCESSUS :</B>
<BR>
<?php echo " ", date ("h:i:s"); ?>
</P>

<?php
include '../bib_funs.inc.php';

//lecture récursive de contenu de dossiers
//include '../indexationV1/indexation.php';
//Augmentation du temps
//d'exécution de ce script
set_time_limit (500);
$path= "ccm";

explorerDir($path);

function explorerDir($path)
{
        $folder = opendir($path);
        while($entree = readdir($folder))
        {
                //On ignore les entrées

                if($entree != "." && $entree != "..")
                {
                        // On vérifie si il s'agit d'un répertoire
                        if(is_dir($path."/".$entree))
                        {
                                $sav_path = $path;
                                // Construction du path jusqu'au nouveau répertoire
                                $path .= "/".$entree;
                                //echo "DOSSIER = ", $path, "<BR>";
                                // On parcours le nouveau répertoire
                                explorerDir($path);
                                $path = $sav_path;
                        }
                        else
                        {
                                //C'est un fichier html ou pas
                                $path_source = $path."/".$entree;

                                if(stripos($path_source, '.html'))
                                {
                                        echo 'On appelle le module indexation (la fonction indexer) <br>';
                                        // Affichage du chemin du fichier à traiter
                                        echo $path_source, '<br>';
                                        indexer($path_source);
                                }
                                //Si c'est un .html
                                //On appelle la fonction d'indexation
                                //Dans le module_indexation.php
                                //Par un include
                        }
                }
        }
        closedir($folder);
}
?>
<P>
<B>FINNNNNN DU PROCESSUS :</B>
<BR>
<?php echo " ", date ("h:i:s"); ?>
</P>
