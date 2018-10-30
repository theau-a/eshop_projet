<?php 

require_once("../ADMIN/inc/header_back.php");

if(isset($_GET['id']) && is_numeric($_GET['id']))
{
    $req = "SELECT * FROM membre WHERE id_membre = :id";
    
    $result = $pdo->prepare($req);

    $result->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

    $result->execute();

    if($result->rowCount() == 1)
    {
        $membre = $result->fetch();
        
        //debug($produit);

    }
    else 
    {
        echo "Dommage réessaie";
        // header("location:index.php?m=fail");    
    }
    
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
    if($_POST) # Je suis en train de modifier un produit
    {

        // CONTROL QUALITE

        if(empty($msg))
        {
            $result = $pdo->prepare("UPDATE membre SET pseudo=:pseudo, prenom=:prenom, nom=:nom, email=:email, adresse=:adresse, code_postal=:code_postal, ville=:ville, civilite=:civilite, statut=:statut WHERE id_membre = :id_membre");
            
            $result->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
            $result->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
            $result->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
            $result->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
            $result->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
            $result->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
            $result->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
            $result->bindValue(':statut', $_POST['statut'], PDO::PARAM_INT);
            $result->bindValue(":id_membre", $membre['id_membre'], PDO::PARAM_INT);
            $result->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);

            if($result->execute())
            {
  
                header("location:liste_user.php?m=update");
                           
                
                // $msg .= "<div class='alert alert-success'>Le membre est bien modifié!</div>";
            }
        }
        debug($_POST);
        

    }
    // if($_GET)
    // {

    //    if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
    //     {
    //         $req = "SELECT * FROM membre WHERE id_membre = :id";

    //         $result = $pdo->prepare($req);
    //         $result->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    //         $result->execute();

    //         if($result->rowCount() == 1)
    //         {
    //             $modif_membre = $result->fetch();

    //             debug($modif_membre);
    //         }
    //         else 
    //         {
    //             $msg .= "<div class='alert alert-danger'>Aucune correspondance en base de donnée.</div>";
    //         }
    //     }
    //     else 
    //     {
    //         $msg .= "<div class='alert alert-danger'>Aucune correspondance en base de donnée.</div>";
    //     } 


    // }
    

?>
<div class="starter-template">
    <h1><?= $page ?></h1>
        <form action="" method="post">
            <small class="form-text text-muted">Vos données ne seront revendues à des services tiers.</small>
            <?= $msg ?>
            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" class="form-control" id="pseudo" placeholder="Choisissez votre pseudo ..." name="pseudo" required value="<?= $membre['pseudo'] ?>">
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" class="form-control" id="prenom" placeholder="Quel est votre prénom ..." name="prenom" value="<?= $membre['prenom']?>">
            </div>
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" placeholder="Quel est votre nom ..." name="nom" value="<?= $membre['nom'] ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Entrez votre email ..." name="email" value="<?= $membre['email']?>">
            </div>
            <div class="form-group">
                <label for="civilite">Civilité</label>
                <select class="form-control" id="civilite" name="civilite">
                    <option value="f" <?php if($membre['civilite'] == 'f'){echo 'selected';} ?> >Femme</option>
                    <option value="m" <?php if ($membre['civilite'] == 'm') {echo 'selected';} ?> >Homme</option>
                    <option value="o" <?php if ($membre['civilite'] == 'o') {echo 'selected';} ?> >Je ne souhaite pas le préciser</option>
                </select>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" class="form-control" id="adresse" placeholder="Quelle est votre adresse ..." name="adresse" value="<?= $membre['adresse'] ?>">
            </div>
            <div class="form-group">
                <label for="code_postal">Code postal</label>
                <input type="text" class="form-control" id="code_postal" placeholder="Quel est votre code postal ..." name="code_postal" value="<?= $membre['code_postal'] ?>">
            </div>

            <div class="form-group">
                <label for="ville">Ville</label>
                <input type="text" class="form-control" id="ville" placeholder="Quelle est votre ville ..." name="ville" value="<?= $membre['ville'] ?>">
            </div>
            <div class="form-group">
                <label for="statut">Statut</label>
                <input type="text" class="form-control" id="statut" placeholder="Quelle est votre statut ..." name="statut" value="<?= $membre['statut'] ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block">modifier user</button>
        </form>
    </div>

<?php 

    require_once("../ADMIN/inc/footer_back.php");

?>