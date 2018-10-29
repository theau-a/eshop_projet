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
    debug($info);
    debug($_POST);

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
                <a href="#" class="card-link">Supprimer compte</a>
            </div>
        </div>
    </div>

<?php require_once("inc/footer.php"); ?>