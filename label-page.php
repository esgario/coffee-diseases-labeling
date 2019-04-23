<?php

// Realiza a conexão com o banco de dados
include("db-connection.php");

// Verifica a existência do arg image_id na url
if (isset($_GET['image_id'])) {
  $img_id = intval($_GET['image_id']); // $test = isset($_GET['test']) ? $_GET['test'] : null;
} else {
  $img_id = 1;
}

/*
* Se o formulário for submetido
* atualiza os dados no servidor
*/
if(isset($_POST["atualiza"])) {

  $doenca = [0,0,0,0];
  for ($i=0; $i < 4; $i++) {
      if (isset($_POST['Doenca'.$i])) {
        $doenca[$i] = 1;
      }
  }

  $predominante = 0;
  if (isset($_POST['Predominante'])) {
    $predominante = $_POST['Predominante'];
  }

  $severidade = 0;
  if (isset($_POST['Severidade'])) {
    $severidade = $_POST['Severidade'];
  }

  $audit = "0";
  if (isset($_POST['Auditado'])) {
    $audit = $_POST['Auditado'];
  }

  $nota = "";
  if (isset($_POST['NotaObservacao'])) {
    $nota = $_POST['NotaObservacao'];
  }

  $sql = "UPDATE images SET auditado=$audit, doenca_predominante=$predominante, bichomineiro=$doenca[0], ferrugem=$doenca[1], phoma=$doenca[2], cercosporiose=$doenca[3], severidade=$severidade, observacao='$nota' WHERE id=$img_id";
  $query = $mysqli->query($sql) or die($mysqli->error);
  if(!$query) { echo 'Erro ao acessar o banco de dados!';}

  $img_id = $img_id + 1;
  header("Location: label-page.php?image_id=$img_id");
}

// Query que retorna a quantidade de imagens total
// $sql_query = "SELECT count(*) as total from images";
$sql_query = "SELECT id FROM images ORDER BY id DESC LIMIT 1";
$sql_ret_all = $mysqli->query($sql_query) or die($mysqli->error);
$last_id = $sql_ret_all->fetch_assoc();

// Query para encontrar a imagem anterior não auditada
$sql_query = "SELECT id FROM images WHERE auditado=0 AND id<$img_id ORDER BY id DESC LIMIT 1";
$sql_ret_all = $mysqli->query($sql_query) or die($mysqli->error);
$img_id_ant = $sql_ret_all->fetch_assoc();

// Query para encontrar a imagem seguinte não auditada
$sql_query = "SELECT id FROM images WHERE auditado=0 AND id>$img_id ORDER BY id ASC LIMIT 1";
$sql_ret_all = $mysqli->query($sql_query) or die($mysqli->error);
$img_id_prox = $sql_ret_all->fetch_assoc();

// Max e Min caso ultrapasse os limites
$ant = max($img_id-1, 1);
$prox = min($img_id+1, $last_id['id']);

// Se não encontrar novos id's mantém os atuais
if(empty($img_id_ant)){ $ant_nao_audit = $img_id; } else { $ant_nao_audit = $img_id_ant['id']; }
if(empty($img_id_prox)){ $prox_nao_audit = $img_id; } else { $prox_nao_audit = $img_id_prox['id']; }

// Retorna os dados da imagem que está sendo analisada
$sql_query = "SELECT * FROM images WHERE id=$img_id";
$sql_ret_all = $mysqli->query($sql_query) or die($mysqli->error);
$content = $sql_ret_all->fetch_assoc();

$indice_pag_home = floor($img_id / 40);

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Folhas de café</title>
  <meta name="description" content="Auditoria de doenças de folhas do cafeeiro">
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
      <a class="btnback" href="index.php?pag_atual=<?php echo $indice_pag_home?>"><</a>
      <a href="index.php?pag_atual=<?php echo $indice_pag_home?>" style="font-size: 30px;">Folhas de café</a>
    </div>
  </nav>
      

  <main>
    <div class="container">

      <div class="row">
      </div>
      
      <div class="row">
        <div class="col s12 m12 center">
          <img id="lesionImage" class="materialboxed responsive-img" style="width: 100%;" src="images/<?php echo $content['id'] ?>.jpg">
          <br>
        </div>

        <div class="col s12 m12">

          <!-- FORM -->
          <form method="POST" action="">

            <table class="striped">
              <thead>
                <tr class="tableback">
                  <th colspan="2">Folha <?php echo $content['id']; ?></th>
                </tr>
              </thead>

              <tbody>
                <!-- DOENÇAS -->
                <tr>
                      <th>Doenças:</th>
                        <td>
                          <label><input name="Doenca0" type="checkbox" value="1" <?php if($content['bichomineiro']==1){ ?>checked<?php } ?> /><span>Bicho mineiro</span></label><br>
                        
                          <label><input name="Doenca1" type="checkbox" value="1" <?php if($content['ferrugem']==1){ ?>checked<?php } ?> /><span>Ferrugem</span></label><br>
                        
                          <label><input name="Doenca2" type="checkbox" value="1" <?php if($content['phoma']==1){ ?>checked<?php } ?> /><span>Phoma</span></label><br>

                          <label><input name="Doenca3" type="checkbox" value="1" <?php if($content['cercosporiose']==1){ ?>checked<?php }?> /><span>Cercosporiose</span></label>
                        </td>
                </tr>
                
                <!-- DOENÇAS PREDOMINANTE -->
                <tr>
                      <th>Doença predominante:</th>
                        <td>
                          <label><input name="Predominante" type="radio" value="0" <?php if($content['doenca_predominante']==0){ ?>checked<?php } ?> /><span>Saudável</span></label><br>

                          <label><input name="Predominante" type="radio" value="1" <?php if($content['doenca_predominante']==1){ ?>checked<?php } ?> /><span>Bicho mineiro</span></label><br>
                        
                          <label><input name="Predominante" type="radio" value="2" <?php if($content['doenca_predominante']==2){ ?>checked<?php } ?> /><span>Ferrugem</span></label><br>
                        
                          <label><input name="Predominante" type="radio" value="3" <?php if($content['doenca_predominante']==3){ ?>checked<?php } ?> /><span>Phoma</span></label><br>

                          <label><input name="Predominante" type="radio" value="4" <?php if($content['doenca_predominante']==4){ ?>checked<?php }?> /><span>Cercosporiose</span></label><br>
                        </td>
                </tr>

                <!-- SEVERIDADE -->
                <tr>
                      <th>Severidade:</th>
                        <td>
                          <label><input name="Severidade" type="radio" value="0" <?php if($content['severidade']==0){ ?>checked<?php } ?> /><span>Saudável ( < 0.1% )</span></label><br>
                        
                          <label><input name="Severidade" type="radio" value="1" <?php if($content['severidade']==1){ ?>checked<?php } ?> /><span>Muito Baixa ( 0.1% - 5.0% )</span></label><br>
                        
                          <label><input name="Severidade" type="radio" value="2" <?php if($content['severidade']==2){ ?>checked<?php } ?> /><span>Baixa ( 5.1% - 10.0% )</span></label><br>

                          <label><input name="Severidade" type="radio" value="3" <?php if($content['severidade']==3){ ?>checked<?php }?> /><span>Alta ( 10.1% - 15.0% )</span></label><br>

                          <label><input name="Severidade" type="radio" value="4" <?php if($content['severidade']==4){ ?>checked<?php }?> /><span>Muito Alta ( > 15.0% )</span></label>
                        </td>
                </tr>

                    <tr>
                      <th>Observações:</th>
                      <td><textarea maxlength="200" name="NotaObservacao" class="mytextarea"><?php echo $content['observacao']; ?></textarea></td>
                    </tr>
                    <tr class="tableback">
                      <th>Auditar:</th>
                      <td>
                        <label><input name="Auditado" type="checkbox" value="1" <?php if($content['auditado']==1){ ?> checked <?php } ?> /><span></span></label>

                        <button style="float:right; margin-right:8px;"
                            class="btn waves-effect waves-light btn-small"
                            type="submit"
                            name="atualiza">
                            Salvar
                            <i class="material-icons right">send</i>
                        </button>

                        <button style="float:right; margin-right:8px;"
                            class="btn waves-effect waves-light btn-small red darken-2"
                            name="delete"
                            onclick="return apagar();">
                            Apagar
                        </button>

                      </td>
                    </tr>
              </tbody>
            </table>

          </form>
          <!-- END FORM -->

          <br>
          <form method="POST" action="">
            <a href="label-page.php?image_id=<?php echo $ant_nao_audit; ?>" class="grey darken-2 waves-effect waves-light btn-small"><<</a>
            <a href="label-page.php?image_id=<?php echo $ant; ?>" class="grey darken-2 waves-effect waves-light btn-small">Anterior</a>
            <a href="label-page.php?image_id=<?php echo $prox; ?>" class="grey darken-2 waves-effect waves-light btn-small">Próxima</a>
            <a href="label-page.php?image_id=<?php echo $prox_nao_audit; ?>" class="grey darken-2 waves-effect waves-light btn-small">>></a>
          </form>
          
          <br><br>
          
        </div>

      </div>
               
    </div>
  </main>

  <!--JavaScript no fim do body para carregamento otimizado -->
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

  function apagar(){
    if(confirm('Tem certeza que deseja apagar esta imagem?')){
      window.location.href ='remove-sample.php?image_id=<?php echo $img_id ?>';
      return false;
    }
  }

  document.onkeydown = function(evt) {
      evt = evt || window.event;
      if (evt.keyCode == 39) {
          window.location.href = 'label-page.php?image_id=<?php echo $prox; ?>';
      }

      if (evt.keyCode == 37) {
          window.location.href = 'label-page.php?image_id=<?php echo $ant; ?>';
      }
  };

  </script>
</body>
</html>