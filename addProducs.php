<?php
$bdd = new PDO('mysql:host=127.0.0.1;dbname=kittyhei','root','');
$producs = $bdd->query('SELECT * FROM produits ORDER BY date_publication DESC');

$mode_edition = 0;
  
if(isset($_GET['edit']) AND !empty($_GET['edit'])) {
  $mode_edition = 1;
  $edit_id = htmlspecialchars($_GET['edit']);
  $edit_producs = $bdd->prepare('SELECT * FROM produits WHERE id = ?');
  $edit_producs->execute(array($edit_id));

  if($edit_producs->rowCount() == 1) {// permet de regarder si le compte existe ou pas
    $edit_producs = $edit_producs->fetch();
  } else {
      die("Erreur: Le produit concerné n'existe pas...");
    }
  } else {
      if(isset($_POST['model'], $_POST['description'], $_POST['price'], $_POST['color'])){
        if(!empty($_POST['model']) AND !empty($_POST['description']) AND !empty($_POST['price']) AND !empty($_POST['color'])){
          $model = htmlspecialchars($_POST['model']);
          $description = htmlspecialchars($_POST['description']);
          $price = htmlspecialchars($_POST['price']);
          $color = htmlspecialchars($_POST['color']);
          $image = $_FILES['picture']['name'];// cet function permet de sauvegarder le nom de la photo
          $target = "img/producs/".basename($image);// cet function permet d'indiquer le chemin ou les images seront downloader
            
            if($mode_edition == 0) {
              $insertmbr = $bdd->prepare("INSERT INTO produits(model,description,price,picture,color,date_publication,updated_publication) VALUES (?, ?, ?, ?, ?,NOW(),NOW())");
              $insertmbr->execute(array($model,$description,$price,$image,$color));
              if (move_uploaded_file($_FILES['picture']['tmp_name'], $target)) {// cet function permet de sauvegarder l'image uploader vers le chemin de destination 
                  $msg = "Image uploaded successfully";
                } else {
                  $msg = "Failed to upload image";
                }
                header('Location: http://kittyhei/addProducs.php');
            }

            elseif($mode_edition == 1) {
              $update = $bdd->prepare('UPDATE produits SET model = ?, description = ?, price = ?, color = ?, updated_publication=NOW() WHERE id = ?');
              $update->execute(array($model,$description,$price,$color,$edit_id));
              echo"Votre produit à bien été mis à jour!";
            }
          } else {
          die("Votre produit n'a pas été enregistrer !");
        }
      }
    }
////////////////////////////////////////////////////////Permet de supprimer les produits/////////////////////////////////////////////////////////////
if(isset($_GET['id']) AND !empty($_GET['id'])) {
  $suppr_id = htmlspecialchars($_GET['id']);
  $suppr = $bdd->prepare('DELETE FROM produits WHERE id = ?');
  $suppr->execute(array($suppr_id));

  header('Location: http://kittyhei/addProducs.php');
}
?>

<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
  <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/modern-business.css">
  <link rel="stylesheet" href="../css/jquery-ui.min.css">
  <link rel="stylesheet" href="./css/bootstrap.css">
  <link rel="stylesheet" href="./css/style.css">
  <title>KittyHei-Produit</title>
</head>
  <body>
    <nav class="navbar fixed-top navbar-expand-lg navbar-light" style="background-color: #A50DFF;" >
      <div class="container">
        <a class="navbar-brand text-white" href="index.php">KittyHei - Tahiti</a>
          <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
          <li class="nav-item">
              <a class="nav-link text-white" href="addProducs.php">Ajouter des produits</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="addNews.php">Ajouter des news</a>
            </li>
          </ul>
        </div>
      </div> 
    </nav>

    <div class="main">
      <div class="main-content">
        <div class="container-fluid">
          <div class="row align-items-center">
            <div class="col-lg-4 col-sm-4">
              <table class="table table-striped table-bordered table-dark">
                <caption class="panel-heading">KittyHei | Les produits</caption>
                <thead>
                  <tr>
                    <th scope="col">modèle</th>
                    <th scope="col">couleur</th>
                    <th scope="col">description</th>
                    <th scope="col">prix(fr)</th>
                    <th scope="col">image</th>
                    <th scope="col">date_publication</th>
                    <th scope="col">modifier</th>
                    <th scope="col">supprimer</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($row = $producs->fetch()) { ?> 
                    <tr>
                      <td><?= $row['model'] ?></td>
                      <td><?= $row['color'] ?></td>
                      <td style="word-wrap: break-word; min-width:300px; max-width: 300px;"><?= $row['description'] ?></td>
                      <td><?= $row['price'] ?></td>
                      <td style="word-wrap: break-word; min-width:300px; max-width: 300px;"><?= $row['picture'] ?></td>
                      <td><?= $row['date_publication'] ?></td>
                      <td><a href="addProducs.php?edit=<?= $row['id'] ?>#producs"  name="edit" id="edit" button class="btn btn-success btn-small btn-detail"><i class="fas fa-pencil-alt"></i></a></td>
                      <td><a href="addProducs.php?id=<?= $row['id'] ?>" name="delete" id="delete" button class="btn btn-danger btn-small btn-delete"><i class="fas fa-trash-alt"></i></a></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="row" id="form">
            <div class="col-lg-10 col-sm-6 offset-lg-1">
            <h2 id="producs">Nouveau produit</h2>
              <form method="POST" action="addProducs.php" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="model">Modèle</label>
                  <?php
                  
                  $producModels = array("Choose...","Couronnes","Fleurs","Poara","Autre...");
                  ?>
                  <select name="model">
                      <?php
                          foreach($producModels as $model){
                          ?>
                                <option value="<?= $model; ?>"><?= $model; ?></option>
                          <?php
                          }
                      ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="color">Couleur<span class="required">*</span></label>
                  <textarea required type="text" name="color" class="form-control" id="color" /><?php if( $mode_edition == 1 ) { ?><?= $edit_producs['color'] ?>"<?php } ?></textarea> 
                </div>
                <div class="form-group">
                  <label for="description">Description<span class="required">*</span></label>
                  <textarea required type="text" name="description" class="form-control" id="description" /><?php if( $mode_edition == 1 ) { ?><?= $edit_producs['description'] ?>"<?php } ?></textarea> 
                </div>
                <div class="form-group">
                  <p>  
                    <label for="price">Prix en XPF<span class="required">*</span></label>
                    <input required type="text" class="form-control" name="price" id="price" class="price" <?php if( $mode_edition == 1 ) { ?> value="<?= $edit_producs['price'] ?>"<?php } ?>/>
                  </p> 
                </div>
                <div class="form-group">
                  <label for="picture">Image<span class="required">*</span></label>
                  <input type="file" accept="image/*" name="picture" onchange="loadFile(event)"/>
                  <img class="img-fluid" id="output" />
                </div>
                <?php if ($mode_edition == 1): ?>
                  <a href="addProducs.php"><button type="submit" name="edit" class="btn btn-success" id="edit">Modifier</button></a>
                <?php else: ?>
                  <a href="addProducs.php"><button type="submit" name="publish" class="btn btn-success" id="publish">Publier</button></a>
                <?php endif ?>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  <script type="text/javascript" src="./js/jquery-3.2.1.min.js"></script>
  <script type="text/javascript" src="./js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="./js/bootstrap.min.js"></script>
  <script type="text/javascript" src="./js/script.js"></script>  
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  </body>
</html>