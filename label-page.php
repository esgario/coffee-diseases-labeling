<?php

include("db-connection.php");

if (isset($_GET['image_id'])) {
  $img_id = intval($_GET['image_id']); // $test = isset($_GET['test']) ? $_GET['test'] : null;
} else {
  $img_id = 0;
}

if(isset($_POST["update"])) {
  
  $disease = [0,0,0,0];
  for ($i=0; $i < 4; $i++) {
      if (isset($_POST['D'.$i])) {
        $disease[$i] = 1;
      }
  }

  $audit = "0";
  if (isset($_POST['audit'])) {
    $audit = $_POST['audit'];
  }

  $note = $_POST['textnote'];

  $sql = "UPDATE images SET auditado=$audit, bichomineiro=$disease[0], ferrugem=$disease[1], phoma=$disease[2], cercosporiose=$disease[3], observacao='$note' WHERE id=$img_id";
  $query = $mysqli->query($sql) or die($mysqli->error);
  if(!$query) { echo 'Erro ao acessar o banco de dados!';}

  $img_id = $img_id + 1;
  header("Location: label-page.php?image_id=$img_id");
}

$sql_query = "SELECT count(*) as total from images";
$sql_ret_all = $mysqli->query($sql_query) or die($mysqli->error);
$records = $sql_ret_all->fetch_assoc();

$sql_query = "SELECT id FROM images WHERE auditado=0 AND id<$img_id ORDER BY id DESC LIMIT 1";
$sql_ret_all = $mysqli->query($sql_query) or die($mysqli->error);
$img_id_back = $sql_ret_all->fetch_assoc();

$sql_query = "SELECT id FROM images WHERE auditado=0 AND id>$img_id ORDER BY id ASC LIMIT 1";
$sql_ret_all = $mysqli->query($sql_query) or die($mysqli->error);
$img_id_next = $sql_ret_all->fetch_assoc();

echo $img_id_back['id'];

$back = max($img_id-1,0);
$next = min($img_id+1,$records['total']-1);

if(empty($img_id_back)){ $backnotaudit = $img_id; } else { $backnotaudit = $img_id_back['id']; }
if(empty($img_id_next)){ $nextnotaudit = $img_id; } else { $nextnotaudit = $img_id_next['id']; }

$sql_query = "SELECT id, auditado, bichomineiro, ferrugem, phoma, cercosporiose, observacao FROM images WHERE id=$img_id";
$sql_ret_all = $mysqli->query($sql_query) or die($mysqli->error);
$content = $sql_ret_all->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Features ABCD</title>
  <meta name="description" content="Auditoria de doenças das folhas do cafeeiro">
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

  <!-- Include header page -->
  <nav>
    <div class="nav-wrapper red darken-4 center">
      <a href="index.php" style="font-size: 30px;">Folhas de café</a>
    </div>
  </nav>
      

  <main>
    <div class="container">

      <div class="row">
      </div>
      
      <div class="row">
        <div class="col s12 m12 center">
    			<img id="lesionImage" class="materialboxed responsive-img" style="width: 100%;" src="../<?php echo $content['id'] ?>.jpg">
          <br>
        </div>

        <div class="col s12 m12">
        	<form method="POST" action="">

        		<table class="striped">
        			<thead>
        				<tr class="tableback">
        					<th colspan="2">Folha <?php echo $content['id']; ?></th>
        				</tr>
        			</thead>

        			<tbody>
        				<tr>
                  <th>Doenças:</th>
                      <td>
                        <label><input name="D0" type="checkbox" value="1" <?php if($content['bichomineiro']==1){ ?>checked<?php } ?> /><span>Bicho mineiro</span></label><br>
                      
                        <label><input name="D1" type="checkbox" value="1" <?php if($content['ferrugem']==1){ ?>checked<?php } ?> /><span>Ferrugem</span></label><br>
                      
                        <label><input name="D2" type="checkbox" value="1" <?php if($content['phoma']==1){ ?>checked<?php } ?> /><span>Phoma</span></label><br>

                        <label><input name="D3" type="checkbox" value="1" <?php if($content['cercosporiose']==1){ ?>checked<?php }?> /><span>Cercosporiose</span></label>
                      </td>
        				</tr>
                <tr>
                  <th>Observações:</th>
                  <td><textarea maxlength="200" name="textnote" class="mytextarea"><?php echo $content['observacao']; ?></textarea></td>
                </tr>
                <tr class="tableback">
                  <th>Auditar:</th>
                  <td>
                    <label><input name="audit" type="checkbox" value="1" <?php if($content['auditado']==1){ ?> checked <?php } ?> /><span></span></label>

                    <button style="float:right;" class="btn waves-effect waves-light btn-small" type="submit" name="update">Salvar<i class="material-icons right">send</i></button>
                  </td>
                </tr>
        			</tbody>
        		</table>

        	</form>
          <br>
          <form method="POST" action="">
            <a href="label-page.php?image_id=<?php echo $backnotaudit; ?>" class="grey darken-2 waves-effect waves-light btn-small"><<</a>
            <a href="label-page.php?image_id=<?php echo $back; ?>" class="grey darken-2 waves-effect waves-light btn-small">Anterior</a>
            <a href="label-page.php?image_id=<?php echo $next; ?>" class="grey darken-2 waves-effect waves-light btn-small">Próxima</a>
            <a href="label-page.php?image_id=<?php echo $nextnotaudit; ?>" class="grey darken-2 waves-effect waves-light btn-small">>></a>
          </form>
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

   $(document).ready(function(){
      $('.slider').slider();
    });

  $(document).ready(function(){
    $('.materialboxed').materialbox();
  });

  </script>
</body>
</html>