<?php
$bdd = new PDO('mysql:host=127.0.0.1;dbname=kittyhei','root','');
$news = $bdd->query('SELECT * FROM news ORDER BY date_publication DESC');
$mode_edition = 0;

if(isset($_GET['edit']) AND !empty($_GET['edit'])) {
  $mode_edition = 1;
  $edit_id = htmlspecialchars($_GET['edit']);
  $edit_news = $bdd->prepare('SELECT * FROM news WHERE id = ?');
  $edit_news->execute(array($edit_id));

  if($edit_news->rowCount() == 1) {// permet de regarder si le compte existe ou pas
    $edit_news = $edit_news->fetch();
  } else {
      die("Erreur: La news concerné n'existe pas...");
    }
  } else {
      if(isset($_POST['title'],$_POST['description'])){
        if(!empty($_POST['title']) AND !empty($_POST['description'])){
          $title = htmlspecialchars($_POST['title']);
          $description = htmlspecialchars($_POST['description']);
          $image = $_FILES['picture']['name'];// cet function permet de sauvegarder le nom de la photo
          $target = "img/news/".basename($image);// cet function permet d'indiquer le chemin ou les images seront downloader
            
            if($mode_edition == 0) {
              $insertmbr = $bdd->prepare("INSERT INTO news (title,description,picture,date_publication) VALUES (?, ?, ?, NOW())");
              $insertmbr->execute(array($title, $description, $image));
              if (move_uploaded_file($_FILES['picture']['tmp_name'], $target)) {// cet function permet de sauvegarder l'image uploader vers le chemin de destination 
                  $msg = "Image uploaded successfully";
                } else {
                  $msg = "Failed to upload image";
                }
                header('Location: http://iss-tahiti/addNews.php');
            }

            elseif($mode_edition == 1) {
              $update = $bdd->prepare('UPDATE news SET title = ?, description = ?, picture = ?, updated_publication = NOW() WHERE id = ?');
              $update->execute(array($title, $description, $image, $edit_id));
              if (move_uploaded_file($_FILES['picture']['tmp_name'], $target)) {// cet function permet de sauvegarder l'image uploader vers le chemin de destination 
                $msg = "Image uploaded successfully";
              } else {
                $msg = "Failed to upload image";
              }
              echo"Votre news à bien été mis à jour!";
            }
          } else {
          die("Votre news n'a pas été enregistrer !");
        }
      }
    }
////////////////////////////////////////////////////////Permet de supprimer les news/////////////////////////////////////////////////////////////
if(isset($_GET['id']) AND !empty($_GET['id'])) {
  $suppr_id = htmlspecialchars($_GET['id']);
  $suppr = $bdd->prepare('DELETE FROM news WHERE id = ?');
  $suppr->execute(array($suppr_id));

  header('Location: http://iss-tahiti/addNews.php');
}
?>

<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta title="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
  <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/modern-business.css">
  <link rel="stylesheet" href="../css/jquery-ui.min.css">
  <link rel="stylesheet" href="./css/bootstrap.css">
  <link rel="stylesheet" href="./css/style.css">
  <title>KittyHei-News</title>
</head>
<body>
  <nav class="navbar fixed-top navbar-expand-lg navbar-light" style="background-color: #A50DFF;">
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
  <div clas="main">
    <div class="main-content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-4 col-sm-4">
            <table class="table table-striped table-bordered table-dark">
              <caption class="panel-heading">KittyHei | Les news</caption>
              <thead>
                <tr>
                  <th scope="col">titre</th>
                  <th scope="col">description</th>
                  <th scope="col">image</th>
                  <th scope="col">date_publication</th>
                  <th scope="col">modfier</th>
                  <th scope="col">supprimer</th>
                </tr>
              </thead>
              <tbody>
                <?php while($row = $news->fetch()) { ?> 
                  <tr>
                    <td><?= $row['title'] ?></td>
                    <td style="word-wrap: break-word; min-width:360px; max-width: 360px;"><?= $row['description'] ?></td>
                    <td style="word-wrap: break-word; min-width:360px; max-width: 360px;"><?= $row['picture'] ?></td>
                    <td><?= $row['date_publication'] ?></td>
                    <td><a href="addNews.php?edit=<?= $row['id'] ?>#news"  name="edit" id="edit" button class="btn btn-success btn-small btn-detail"><i class="fas fa-pencil-alt"></i></a></td>
                    <td><a href="addNews.php?id=<?= $row['id'] ?>" name="delete" id="delete" button class="btn btn-danger btn-small btn-delete"><i class="fas fa-trash-alt"></i></a></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        
        <div class="row" id="form">
          <div class="col-lg-10 col-sm-6 offset-lg-1">
            <h2 id="news">Nouvel News</h2>
            <form method="POST" action="addnews.php" enctype="multipart/form-data">
              <div class="form-group">
                <label for="title">Titre<span class="required">*</span></label>
                <input required type="text" name="title" class="form-control" id="title" placeholder="your title" <?php if( $mode_edition == 1 ) { ?> value="<?= $edit_news['title'] ?>"<?php } ?>/>
              </div>
              <div class="form-group">
                <label for="description">Description<span class="required">*</span></label>
                <textarea required type="text" name="description" class="form-control" id="description" placeholder="your description" /><?php if( $mode_edition == 1 ) { ?><?= $edit_news['description'] ?><?php } ?></textarea>
              </div>
              <div class="form-group">
                <label for="picture">Image<span class="required">*</span></label>
                <input type="file" accept="image/*" name="picture" onchange="loadFile(event)" <?php if( $mode_edition == 1 ) { ?> value="<?= $edit_news['picture'] ?>"<?php } ?>/>
              </div>
                <?php if ($mode_edition == 1): ?>
                  <a href="addnews.php"><button type="submit" name="edit" class="btn btn-success" id="edit">Modifier</button></a>
                <?php else: ?>
                  <a href="addnews.php"><button type="submit" name="publish" class="btn btn-success" id="publish">Publier</button></a>
                <?php endif ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/script.js"></script>  
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>