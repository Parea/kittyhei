<?php
  $bdd = new PDO('mysql:host=127.0.0.1;dbname=kittyhei','root','');
  $producs = $bdd->query('SELECT * FROM produits WHERE model="Fleurs" ORDER BY date_publication DESC');
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Fleurs</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">

  </head>

  <body>

    <!-- Navigation -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-light" style="background-color: #A50DFF;" "fixed-top">
      <div class="container">
        <a class="navbar-brand text-white" href="index.php" >KittyHei | Accueil</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link text-white" href="about.php">Qui suis je</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="services.php">Qu'est ce que je fais</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownPortfolio" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Mes créations
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownPortfolio">
                <a class="dropdown-item" href="couronnes.php">Couronnes</a>
                <a class="dropdown-item" href="fleurs.php">Fleurs</a>
                <a class="dropdown-item" href="poara.php">Poara</a>
                <a class="dropdown-item" href="autres.php">Autre...</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="contact.php">Contact</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <div class="container">

      <!-- Page Heading/Breadcrumbs -->
      <h1 class="mt-4 mb-3">Fleurs</h1>

      <ol class="breadcrumb" style="background-color: rgb(209, 130, 255);">
        <li class="breadcrumb-item">
          <a href="index.html">Accueil</a>
        </li>
        <li class="breadcrumb-item active">Fleurs</li>
      </ol>

        <div class="row">
        <?php while ($row = $producs->fetch()) { ?>
          <div class="col-lg-4 col-sm-6 portfolio-item">
            <div class="card h-100">
              <img style="width: 350px; height: 250px;" class="img-fluid thumbnail" src="../img/producs/<?= $row['picture'] ?> "/>
              <div class="card-body">
                <h4 class="card-title">
                  <a href="#"><?= $row['price']?> Fr</a>
                </h4>
                <p class="card-text"><?= $row['description']?></p>
              </div>
            </div>
          </div>
          <hr>
        <?php } ?>
        </div>
      <!-- /.row -->

      <!-- Pagination -->
      <ul class="pagination justify-content-center">
        <li class="page-item">
          <a class="page-link" href="#" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
            <span class="sr-only">Previous</span>
          </a>
        </li>
        <li class="page-item">
          <a class="page-link" href="#">1</a>
        </li>
        <li class="page-item">
          <a class="page-link" href="#">2</a>
        </li>
        <li class="page-item">
          <a class="page-link" href="#">3</a>
        </li>
        <li class="page-item">
          <a class="page-link" href="#" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
            <span class="sr-only">Next</span>
          </a>
        </li>
      </ul>

    </div>
    <!-- /.container -->

    <footer class="py-2" style="background-color: #A50DFF;">
      <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; KittyHei 2018</p>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>
