    <?php

    $page = "Mon profil";
    $seo_description = "Regardez votre profil qui est sublime, magnifique, vous êtes une star !";

    require_once("inc/header.php");
        # Règles SEO
        if ($_POST) {
            if(!empty($_FILES['changePhoto']['name']))
            {

                # Nous allons donner un nom aléatoire à notre photo
                $photo = $_SESSION['user']['pseudo'] . '_' . $_SESSION['user']['prenom'] . '_' . time() . '-' . rand(1,999) . $_FILES['changePhoto']['name'];
                $photo = str_replace(' ', '-', $photo);
                $photo = str_replace(array('é','è','à','ç','ù'), 'x', $photo);

                // Enregistrons le chemin de notre fichier
                $chemin_photo = RACINE . '/assets/uploads/user/' . $photo;

                $taille_max = 2*1048576; # On définit ici la taille maximale autorisée (2Mo)

                if($_FILES['changePhoto']["size"] > $taille_max || empty($_FILES['changePhoto']["size"]))
                {
                    $msg .= "<div class='alert alert-danger'>Veuillez sélectionner un fichier de 2Mo maximum.</div>";
                }

                $type_photo = [
                    'image/jpeg',
                    'image/png',
                    'image/gif'
                ];

                if (!in_array($_FILES['changePhoto']["type"], $type_photo) || empty($_FILES['changePhoto']["type"])) 
                {
                    $msg .= "<div class='alert alert-danger'>Veuillez sélectionner un fichier JPEG/JPG, PNG ou GIF.</div>";
                }

            }
            elseif(isset($_POST['photo_actuelle']))
            {
                $photo = $_POST['photo_actuelle'];
            }
            else 
            {
                $photo = "default.png";
            }

        }
        
        
            // inserer la photo dans la base de données
            if ($msg) {
                $result = $pdo->prepare("UPDATE membre SET photo=:photo");
                $result->bindValue(':photo', $photo, PDO::PARAM_STR);

                if($result->execute()) # Si j'enregistre bien en BDD
                {
                    if(!empty($_FILES['changePhoto']['name']))
                    {
                        copy($_FILES['changePhoto']['tmp_name'], $chemin_photo);
                    }
                    
                    if(!empty($_POST['id_membre']))
                    {
                        header("location:index.php?m=update");
                    }
                    
                    $msg .= "<div class='alert alert-success'>Le produit est bien enregistré !</div>";
                }

            }

        

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
                    $chemin_photo = RACINE . 'assets/uploads/img/' . $membre['photo'];
                    
                    if(file_exists($chemin_photo) && $membre['photo'] != "default.png") # la fonction fil_exists() me permet de vérifier si le fichier existe bel et bien
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
        
        debug($_FILES);

        // $choosePhoto = choosePhoto();
        ?>

        <div class="starter-template">
            <h1><?= $page ?></h1>
            <div class="card">
                <img class="card-img-top img-thumbnail rounded mx-auto d-block" src="<?= URL.$chemin_photo ?>" alt="Card image cap" style="width:25%;">
                <div class="card-body">
                    <h5 class="card-title">Bonjour <?= $info['pseudo'] ?></h5>
                    <p class="card-text">Nous sommes râvi de vous revoir sur notre plateforme.</p>
                </div>
                <ul class="list-group list-group-flush">
                <form method="POST" class="form-group" enctype="multipart/form-data">
                    <label for="changePhoto">Photo du produit</label>
                    <input type="file" class="form-control-file" id="changePhoto" name="changePhoto">

                <input type='submit' name="change_photo" class='btn btn-danger' value='changer la photo'>   
                </form>
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