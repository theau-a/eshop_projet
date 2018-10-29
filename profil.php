<?php

    # Règles SEO
    $page = "Mon profil";
    $seo_description = "Regardez votre profil qui est sublime, magnifique, vous êtes une star !";

    require_once("inc/header.php");

    if(!userConnect())
    {
        header("location:connexion.php");
        exit(); // die() fonctionne aussi
    }

    // debug($_SESSION, 2);
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
            default:
            $msg .= "<div class='alert alert-warning'>A pas compris !</div>";
            break;
        }
    }
    // debug($info);
    // debug($_POST);
    
    $contenu .= "<td><a data-toggle='modal' data-target='#deleteModal" . $_SESSION['user']['id_membre'] . "'><i class='fas fa-trash-alt'></i></a></td>";
    deleteModal($_SESSION['user']['id_membre'], $_SESSION['user']['pseudo'], "votre compte");
    
    
    if(isset($_GET['a']) && isset($_GET['id']) && $_GET['a'] == "delete" && is_numeric($_GET['id'])) # la fonction is_numeric() me permet de vérifier que le paramètre rentré est bien un chiffre
    {
        $req = "SELECT * FROM membre WHERE id_membre = :id";
        $result = $pdo->prepare($req);
        $result->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $result->execute();
        // debug($result);

        if($result->rowCount() == 1)
        {
            $produit = $result->fetch();
            
            //debug($produit);
            
            $delete_req = "DELETE FROM membre WHERE id_membre = :id";
            
            $delete_result = $pdo->prepare($delete_req);
            $delete_result->bindValue(':id', $_SESSION['user']['id_membre'], PDO::PARAM_INT);
            // debug($delete_result);
            
            if($delete_result->execute())
            {
                $chemin_photo = RACINE . 'assets/uploads/admin/' . $produit['photo'];
                
                if(file_exists($chemin_photo) && $produit['photo'] != "default.jpg") # la fonction fil_exists() me permet de vérifier si le fichier existe bel et bien
                {
                    unlink($chemin_photo); # la fonction unlink() me permet de supprimer un fichier
                }
                
                header("location:connexion.php?m=success");
                unset($_SESSION['user']);
            }
            else
            {
                header("location:connexion.php?m=fail");  
            }
            
        }
        else 
        {
            header("location:connexion.php?m=fail");    
        }
    }
    
    ?>

    <div class="starter-template">
        <h1><?= $page ?></h1>
        <div class="card">
            <img class="card-img-top img-thumbnail rounded mx-auto d-block" src="<?=URL?>/assets/uploads/user/default.png" alt="Card image cap" style="width:25%;">
            <div class="card-body">
                <h5 class="card-title">Bonjour <?= $info['pseudo'] ?></h5>
                <p class="card-text">Nous sommes râvi de vous revoir sur notre plateforme.</p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Prénom: <?= $info['prenom'] ?></li>
                <li class="list-group-item">Nom: <?= $info['nom'] ?></li>
                <li class="list-group-item">Email: <?= $info['email'] ?></li>

                <li class="list-group-item">Civilité: <?php switch($info['civilite']){case "m": echo "homme"; break; case "f": echo "femme"; break; default: echo "Non défini"; break;} ?></li>
                
                <li class="list-group-item">Adresse: <?= $info['adresse'] ?></li>
                <li class="list-group-item">Code postal: <?= $info['code_postal'] ?></li>
                <li class="list-group-item">Ville: <?= $info['ville'] ?></li>
            </ul>
            <div class="card-body">
                <a href="modif_user.php" class="card-link">modifier</a>
                <?= $contenu ?> 
    
                <!-- # J'appelle ma modal de supression (fonction créée dans fonction.php) -->
            </div>
        </div>
    </div>
    
    <?= $msg ?>

<?php require_once("inc/footer.php"); ?>