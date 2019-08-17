<?php
    require 'conexion.php';
    $database = new Connection();

    $target_path = "uploads/";
    $target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 
    if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
        echo "El archivo ".  basename( $_FILES['uploadedfile']['name']). 
        " ha sido subido";
    } else{
        echo "Ha ocurrido un error, trate de nuevo!";
    }

    require 'PHPExcel/IOFactory.php';

	//$nombreArchivo = 'prn5A.xls';
    $nombreArchivo =$_FILES['uploadedfile']['name'];
    
	$objPHPExcel = PHPEXCEL_IOFactory::load($nombreArchivo);
	
	$objPHPExcel->setActiveSheetIndex(0);
	
	$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
	
	for($i = 5; $i <= $numRows; $i++){
        $date = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
        $fecha = PHPExcel_Style_NumberFormat::toFormattedString($date, "YYYY/M/D");
		$idNota = $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
        $noFac = $objPHPExcel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue();
        $noCli = $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue();
        $cliente = $objPHPExcel->getActiveSheet()->getCell('H'.$i)->getCalculatedValue();
        $noVen = $objPHPExcel->getActiveSheet()->getCell('I'.$i)->getCalculatedValue();
        $Vendedor = $objPHPExcel->getActiveSheet()->getCell('J'.$i)->getCalculatedValue();

        $db = $database->openConnection();
        $data = $db->query("CALL verificarNotaRepetida($idNota,$noFac)")->fetchAll();
        // and somewhere later:
        //print_r($data);
        $repetido=false;
        if(empty($data)){
            $repetido=true;
        }
        echo $repetido;
        //$database->closeConnection();
        if($repetido == true){
            try{
                //$db = $database->openConnection();
                // inserting data into create table using prepare statement to prevent from sql injections
                $stm = $db->prepare("INSERT INTO controlpedido (idNota,fecha,noFac,noClie,cliente,noVende,vendedor) 
                VALUES (:idNota,:fecha,:noFac,:noClie,:cliente,:noVende,:vendedor)") ;
                // inserting a record
                $stm->execute(array(
                ':idNota' => $idNota ,
                ':fecha' => $fecha ,
                ':noFac' => $noFac , 
                ':noClie' => $noCli,
                ':cliente' => $cliente,
                ':noVende' => $noVen,
                ':vendedor' => $Vendedor));
                //echo "New record created successfully";
                $database->closeConnection();
            }
            catch (PDOException $e)
            {
                echo "There is some problem in connection: " . $e->getMessage();
                $database->closeConnection();
            }
        }
    }
    
    header( "refresh:3; url=../index.php" );
	
?>