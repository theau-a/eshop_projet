<?php

    # Définir mon nom de page
    $page = "Liste des utilisateurs";

    require_once("inc/header_back.php");

    foreach($_SESSION['user'] as $key => $value)
    {
        $info[$key] = htmlspecialchars($value); # nous vérifions que les informations à afficher ne comporte pas d'injections et ne perturberont pas notre service
    }

    if(isset($_GET['m']) && !empty($_GET['m']))
    {
        switch($_GET['m'])
        {
            case "fail":
            $msg .= "<div class='alert alert-danger'>Une erreur est survenue, veuillez réessayer.</div>";
            break;
            case "update":
            $msg .= "<div class='alert alert-success'>le compte utilisateur a bien été mis a jour.</div>";
            break;
            case "delete":
            $msg .= "<div class='alert alert-success'>le compte utilisateur a bien été supprimé.</div>";
            break;
            default:
            $msg .= "<div class='alert alert-warning'>A pas compris !</div>";
            break;
        }
    }

    if(isset($_GET['a']) && $_GET['a'] == "delete" && isset($_GET['id']) && is_numeric($_GET['id']))
    {
        $req = "SELECT * FROM membre WHERE id_membre = :id";
        
        $result = $pdo->prepare($req);

        $result->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

        $result->execute();

        if($result->rowCount() == 1)
        {
            $membre = $result->fetch();
            
            //debug($produit);
            
            $delete_req = "DELETE FROM membre WHERE id_membre = $membre[id_membre]";
            
            $delete_result = $pdo->exec($delete_req);

            // debug($delete_result);
            
            if($delete_result)
            {
                
                header("location:liste_user.php?m=delete");
            }
            else
            {
                header("location:liste_user.php?m=fail");  
            }
            
        }
        else 
        {
            echo "rater loser";
            // header("location:index.php?m=fail");    
        }
        
        
        
        
        // "DELETE FROM membre WHERE id_membre = $membre[id_membre]";
    }
    // debug($info);
    if(empty($msg))
    {
        // check si le pseudo est dispo
        if($_POST) # Je suis en train de modifier un produit
        {
            $result = $pdo->prepare("UPDATE membre SET pseudo=:pseudo, prenom=:prenom, nom=:nom, email=:email, adresse=:adresse, code_postal=:code_postal, ville=:ville, civilite=:civilite WHERE id_membre = :id_membre");

            
            $result->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
            $result->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
            $result->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
            $result->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
            $result->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
            $result->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
            $result->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
            
            $result->bindValue(":id_membre", $info['id_membre'], PDO::PARAM_INT);
            $result->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);

            if($result->execute())
            {
                // $msg .= "<div class='alert alert-success'>Vous êtes bien enregistré.</div>";
                $msg .= "<div class='alert alert-success'>le compte utilisateur a bien été mis a jour.</div>";
            }
        }
       

    }

    // debug($_POST);


    $result = $pdo->query('SELECT * FROM `membre`');
    $membres = $result->fetchAll();
    
    // debug($membres);
    
    $contenu .= "<div class='table-responsive'>";
    $contenu .= "<table class='table table-striped table-sm'>";
    $contenu .= "<thead class='thead-dark'><tr>";
    
    for($i= 0; $i < $result->columnCount(); $i++)
    {
        $colonne = $result->getColumnMeta($i);

        
        if($colonne['name'] == "mdp")//Si le nom de la key es = a mdp alors tu continue sinon elle s'affiche

        if($colonne["name"] == "mdp")

        {
            continue;
        }
        else
        {
            $contenu .= "<th scope='col'>" . ucfirst(str_replace('_', ' ', $colonne['name'])) . "</th>";
        }
        
    
    }
    
    $contenu .= "<th colspan='2'>Actions</th>";
    $contenu .= "</tr></thead><tbody>";
    
    
    foreach($membres as $membre)
    {  
        $contenu .= "<tr>";
        foreach ($membre as $key => $value) 
        {
            if($key != "mdp")
            {
                // je sais pas
                $contenu .= "<td>" . $value . "</td>";
            }
            
        }
            
        //debug($membre);
        
        $contenu .= "<td><a href='modif_utilisateur.php?id=" . $membre['id_membre'] . "'><i class='fas fa-pen'></i></a></td>";
        
        $contenu .= "<td><a data-toggle='modal' data-target='#deleteModal" . $membre['id_membre'] . "'><i class='fas fa-trash-alt'></i></a></td>";
       
        deleteModal($membre['id_membre'],$membre['pseudo'], "L'utilisateur");
        
        
        # J'appelle ma modal de supression (fonction créée dans fonction.php)
        $contenu .= "</tr>";
    }
    
    $contenu .= "</tbody></table>";
    $contenu .= "</div>";

    
    
    
    ?>
    
           <?= $msg ?>
        <?= $contenu ?>
    

    


  <?php require_once("inc/footer_back.php"); ?>