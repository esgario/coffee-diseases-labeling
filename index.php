<?php

include("db-connection.php");

// Número total de itens por página
$items_per_page = 20;

// Pegar a página atual

if (isset($_GET['curr_page']))
{
  $curr_page = intval($_GET['curr_page']); // $test = isset($_GET['test']) ? $_GET['test'] : null;
} else {
  $curr_page = 0;
}

$item = $curr_page * $items_per_page;

// Puxar páginas do banco
$sql_query = "SELECT id, auditado FROM images LIMIT $item, $items_per_page";
$sql_ret_all = $mysqli->query($sql_query) or die($mysqli->error);
$content = $sql_ret_all->fetch_assoc();
$qnt_obj = $sql_ret_all->num_rows;

// Quantidade total de objetos no DB
$total_obj = $mysqli->query("SELECT id FROM images")->num_rows;

// Definir numero de paginas
$total_pages = ceil($total_obj/$items_per_page);

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Features ABCD</title>
  <meta name="description" content="Coleta de features ABCD de lesões pigmentadas">
  <meta name="robots" content="index, follow">
  <meta name="author" content="Guilherme Esgario">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <!--Import Google Icon Font-->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!--Import materialize.css-->
  <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="css/mystyles.css"  media="screen,projection"/>
  <!--Icons-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

   <nav>
    <div class="nav-wrapper red darken-4 center">
      <a href="index.php" style="font-size: 30px;">Folhas de café</a>
    </div>
  </nav>

  <!-- Include header page -->
  <main>
    <br>
    <!-- SUB MENU -->
    <div class="container">

      <div class="row" style="margin-bottom: 0;">
        <div class="col s7">
          <blockquote><h5>Lista de folhas</h5></blockquote>
        </div>

        <div class="col s5">
          <a href="export-csv.php" target="_blank"><h5 class="right" style="font-size:15px;margin-top:25px;">Exportar CSV</h5></a>
        </div>
      </div>

      <div class="row">
        <div class="collection">
          <?php if($qnt_obj > 0) {
            do { ?>
            <a href="label-page.php?image_id=<?php echo $content['id']; ?>" class="collection-item"><span>Folha - <?php echo $content['id']; ?></span><?php 
            $aux = $content['auditado'];
            if($aux==0) { ?>
              <span class="right feat nauditado">NÃO AUDITADO</span></a>
            <?php } else { ?>
              <span class="right feat auditado">AUDITADO</span></a>
            <?php }
            } while($content = $sql_ret_all->fetch_assoc());
          } ?>          
        </div>

        <!-- PAGINAÇÃO -->
          <div class="center">
            <ul class="pagination">
              <li class="waves-effect"><a class="page-link" href="index.php?curr_page=0"><i class="material-icons">chevron_left</i></a></li>
              <?php for($i=0;$i<$total_pages;$i++) {
                $style = "class=\"waves-effect\"";
                if($curr_page == $i){
                  $style = "class=\"active red darken-4\"";
                } ?>
              <li <?php echo $style; ?>><a class="page-link" href="index.php?curr_page=<?php echo $i; ?>"><?php echo $i+1; ?></a></li>
              <?php } ?>
              <li class="waves-effect"><a href="index.php?curr_page=<?php echo $total_pages-1 ?>"><i class="material-icons">chevron_right</i></a></li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </main>

	<!--JavaScript at end of body for optimized loading-->
  <script src="js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/materialize.min.js"></script>
  <script>
    $(document).ready(function(){
    	$('.sidenav').sidenav(); 
  	});

    $(document).ready(function(){
	    $('.materialboxed').materialbox();
	  });

  </script>
</body>
</html>
