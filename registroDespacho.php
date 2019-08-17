<?php include 'includes/header.php'; 
require 'controlPedido/conexion.php';
$database = new Connection();
$db = $database->openConnection();
if(!empty($_POST['grabar'])){
  $idNota=$_POST['idNota'];
  $cobrador=$_POST['cobrador'];
  $fecha=$_POST['fecha'];
  $hora=$_POST['hora'];
  //$db->query("CALL insertarPicking($idNota,$piking,$revision,$embalaje,$falla)");

  // calling stored procedure command
  $sql = 'CALL insertarDespacho(:idNot,:cobrador,:fechaSalida,:horaSalida)';
 
  // prepare for execution of the stored procedure
  $stmt = $db->prepare($sql);

  // pass value to the command
  $stmt->bindParam(':idNot', $idNota, PDO::PARAM_INT);
  $stmt->bindParam(':cobrador', $cobrador, PDO::PARAM_STR);
  $stmt->bindParam(':fechaSalida', $fecha);
  $stmt->bindParam(':horaSalida', $hora);

  // execute the stored procedure
  $stmt->execute();

  //date_default_timezone_set('UTC-4');
  
  date_default_timezone_set("America/La_Paz");

}
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Tables</h1>
          <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p>

          <!-- DataTales Example -->
          
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>NoTran</th>
                      <th>NoFac</th>
                      <th>Cobrador</th>
                      <th>FechaSalida</th>
                      <th>HoraSalida</th>
                      <th>Grabar</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>NoTran</th>
                      <th>NoFac</th>
                      <th>Cobrador</th>
                      <th>FechaSalida</th>
                      <th>HoraSalida</th>
                      <th>Grabar</th>
                    </tr>
                  </tfoot>
                  <tbody>

                  <?php
                    
                    $data = $db->query("CALL listarPickingActivo()")->fetchAll();
                    // and somewhere later:
                    foreach ($data as $row) {
                        
                  ?>

                    <tr>
                      <td><?php echo $row['idNota']; ?></td>
                      <td><?php echo $row['noFac']; ?></td>
                      <form action="" method="post">
                        <td>
                          <select name="cobrador">
                            <option value="" selected></option>
                            <option value="sva">Javier Vargas</option> 
                            <option value="ame" >Efrain Solar</option>
                            <option value="csb">Marwin Lino</option>
                          </select>
                        </td>
                        <td>
                          <input type="date" name="fecha" value="<?php echo date("Y-m-d");?>">
                        </td>
                        <td>
                          <input type="time" name="hora" value="<?php echo date('H:i'); ?>">
                        </td>
                        <input type="hidden" name="idNota" value="<?php echo $row['idNota']; ?>" >
                        <input type="hidden" name="grabar" value="<?php echo $row['grabar']; ?>" >
                        <td><button type="submit" class="btn btn-success">Grabar</button></td>
                      </form>
                    </tr>
                    <?php
                    }
                    $database->closeConnection();
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <?php include 'includes/footer.php'; ?>   