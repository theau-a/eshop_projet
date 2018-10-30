<?php

    # Définir mon nom de page
    $page = "Liste des commmandes";

    require_once("inc/header_back.php");



    $result = $pdo->query('SELECT * FROM `commande`');
    $commandes = $result->fetchAll();
    
    // debug($membres);
    
    $contenu .= "<div class='table-responsive'>";
    $contenu .= "<table class='table table-striped table-sm'>";
    $contenu .= "<thead class='thead-dark'><tr>";
    
    for($i= 0; $i < $result->columnCount(); $i++)
    {
        $colonne = $result->getColumnMeta($i);

        
        
        
            $contenu .= "<th scope='col'>" . ucfirst(str_replace('_', ' ', $colonne['name'])) . "</th>";

    
    }
    
    $contenu .= "<th colspan='2'>Actions</th>";
    $contenu .= "</tr></thead><tbody>";
    
    
    foreach($commandes as $commande)
    {  
        $contenu .= "<tr>";
        foreach ($commande as $key => $value) 
        {
            if($key != "mdp")
            {
                // je sais pas
                $contenu .= "<td>" . $value . "</td>";
            }
            
        }
            
        //debug($membre);
        
        $contenu .= "<td><a href='#?id=" . $commande['id_commande'] . "'><i class='fas fa-pen'></i></a></td>";
        
        $contenu .= "<td><a data-toggle='modal' data-target='#deleteModal" . $commande['id_commande'] . "'><i class='fas fa-trash-alt'></i></a></td>";
       
        
        
        # J'appelle ma modal de supression (fonction créée dans fonction.php)
        $contenu .= "</tr>";
    }
    
    $contenu .= "</tbody></table>";
    $contenu .= "</div>";
?>

    <?= $msg ?>
    <?= $contenu ?>
    
    <?php require_once("inc/footer_back.php"); ?>