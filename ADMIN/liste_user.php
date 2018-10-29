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
            default:
            $msg .= "<div class='alert alert-warning'>A pas compris !</div>";
            break;
        }
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
        $contenu .= "<th scope='col'>" . ucfirst(str_replace('_', ' ', $colonne['name'])) . "</th>";
        
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
        
        $contenu .= "<td><a href='modif_user.php?id=" . $membre['id_membre'] . "'><i class='fas fa-pen'></i></a></td>";
        
        $contenu .= "<td><a data-toggle='modal' data-target='#deleteModal" . $membre['id_membre'] . "'><i class='fas fa-trash-alt'></i></a></td>";
        
        # J'appelle ma modal de supression (fonction créée dans fonction.php)
        $contenu .= "</tr>";
    }
    
    $contenu .= "</tbody></table>";
    $contenu .= "</div>";
    ?>
    
           <?= $msg ?>
        <?= $contenu ?>
    <div class="starter-template">
    <h1><?= $page ?></h1>
        <form action="" method="post">
            <small class="form-text text-muted">Vos données ne seront revendues à des services tiers.</small>
            <?= $msg ?>
            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" class="form-control" id="pseudo" placeholder="Choisissez votre pseudo ..." name="pseudo" required value="<?= $info['pseudo'] ?>">
                
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" class="form-control" id="password" placeholder="Choisissez votre mot de passe ..." name="password" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" class="form-control" id="prenom" placeholder="Quel est votre prénom ..." name="prenom" value="<?= $info['prenom']?>">
            </div>
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" placeholder="Quel est votre nom ..." name="nom" value="<?= $info['nom'] ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Entrez votre email ..." name="email" value="<?= $info['email']?>">
            </div>
            <div class="form-group">
                <label for="civilite">Civilité</label>
                <select class="form-control" id="civilite" name="civilite">
                    <option value="f" <?php if($info['civilite'] == 'f'){echo 'selected';} ?> >Femme</option>
                    <option value="m" <?php if ($info['civilite'] == 'm') {echo 'selected';} ?> >Homme</option>
                    <option value="o" <?php if ($info['civilite'] == 'o') {echo 'selected';} ?> >Je ne souhaite pas le préciser</option>
                </select>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" class="form-control" id="adresse" placeholder="Quelle est votre adresse ..." name="adresse" value="<?= $info['adresse'] ?>">
            </div>
            <div class="form-group">
                <label for="code_postal">Code postal</label>
                <input type="text" class="form-control" id="code_postal" placeholder="Quel est votre code postal ..." name="code_postal" value="<?= $info['code_postal'] ?>">
            </div>
            <div class="form-group">
                <label for="ville">Ville</label>
                <input type="text" class="form-control" id="ville" placeholder="Quelle est votre ville ..." name="ville" value="<?= $info['ville'] ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block">modifier user</button>
        </form>
    </div>
  <?php require_once("inc/footer_back.php"); ?>