<table border="1" width="100%" cellspacing="0">
    <tr>
        <th>NoTran</th>
        <th>Fecha</th>
        <th>noFac</th>
        <th>noClie</th>
        <th>Cliente</th>
        <th>CodVen</th>
        <th>Vendedor</th>
        <th>Picking</th>
        <th>Revicion</th>
        <th>Embalaje</th>
        <th>Falla</th>
        <th>F.Preparacion</th>
        <th>Cobrador</th>
        <th>F.Salida</th>
        <th>h.Salida</th>

    </tr>
    <?php

    require 'controlPedido/conexion.php';
    $database = new Connection();
    $db = $database->openConnection();

    if(!empty($_POST['fechaInicio']) && !empty($_POST['fechaFin'])){
        $fechaInicial=$_POST['fechaInicio'];
        $fechaFin=$_POST['fechaFin'];
        $sql = 'CALL reporteFecha(:fechaInicial,:fechaFinal)';
        // prepare for execution of the stored procedure
        $stmt = $db->prepare($sql);
        // pass value to the command
        $stmt->bindParam(':fechaInicial', $fechaInicial);
        $stmt->bindParam(':fechaFinal', $fechaFin);
        // execute the stored procedure
        $stmt->execute();
      }else{
        if(!empty($_POST['cod'])){  
            $cod=$_POST['cod'];
            $sql = 'CALL reporteCod(:cod)';
            // prepare for execution of the stored procedure
            $stmt = $db->prepare($sql);
            // pass value to the command
            $stmt->bindParam(':cod', $cod, PDO::PARAM_INT);
            // execute the stored procedure
            $stmt->execute();
        }
      }

    // and somewhere later:
    foreach ($stmt as $row) {
        
    ?>

    <tr>
        <td><?php echo $row['idNota']; ?></td>
        <td><?php echo $row['fecha']; ?></td>
        <td><?php echo $row['noFac']; ?></td>
        <td><?php echo $row['noClie']; ?></td>
        <td><?php echo $row['cliente']; ?></td>
        <td><?php echo $row['noVende']; ?></td>
        <td><?php echo $row['Vendedor']; ?></td>
        <td><?php echo $row['piking']; ?></td>
        <td><?php echo $row['revision']; ?></td>
        <td><?php echo $row['embalaje']; ?></td>
        <td><?php echo $row['falla']; ?></td>
        <td><?php echo $row['fechaPreparacion']; ?></td>
        <td><?php echo $row['cobrador']; ?></td>
        <td><?php echo $row['fechaSalida']; ?></td>
        <td><?php echo $row['horaSalida']; ?></td>
    </tr>
    <?php
    }
    $database->closeConnection();
    ?>
    </tbody>
</table>
              