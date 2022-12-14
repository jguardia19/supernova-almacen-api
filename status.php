<?php
// conexion con base de datos 
include 'conexion/conn.php';

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){
    
    //echo "Informacion".file_get_contents('php://input');

   $methodApi = $_SERVER['REQUEST_METHOD'];

   switch($methodApi){
       // metodo post 
       case 'POST':
        $_POST = json_decode(file_get_contents('php://input'),true);
        //echo "guardar informacion data: =>".json_encode($_POST);
        $sql = 'INSERT INTO estados (nombre_status,fecha_created,user_created) VALUES ("'.$_POST['nombre_status'].'","'.$_POST['fecha_create'].'",1)';
        $result = mysqli_query($con,$sql);
            if($result)
                echo 'informacion registrada exitosamente';
            else
                echo 'no se pudo registrar';
       break;
       // metodo get 
       case 'GET':
        // para obtener un registro especifico
        if(isset($_GET['id'])){
            $sql = 'SELECT *FROM estados  where id_status="'.$_GET['id'].'"';
            $result = mysqli_query($con,$sql);
            $i=0;
            while($row = mysqli_fetch_assoc($result)){
                $response['id'] = $row['id_status'];
                $response['nombre_status'] = $row['nombre_status'];
                $response['fecha_created'] = $row['fecha_created'];
                $i++;
            }
            echo json_encode($response,JSON_PRETTY_PRINT);
         } else{
             // es para obtener todos los registros 
            $sql = 'select *from estados';
            $result = mysqli_query($con,$sql);
            $i=0;
            while($row = mysqli_fetch_assoc($result)){
                $response[$i]['id'] = $row['id_status'];
                $response[$i]['nombre_status'] = $row['nombre_status'];
                $response[$i]['fecha_created'] = $row['fecha_created'];
                $i++;
            }
           echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
         }
       break;
       case 'PUT':
        $_PUT = json_decode(file_get_contents('php://input'),true);
        $sql = 'UPDATE estados SET nombre_status="'.$_PUT['nombre_status'].'"  WHERE id_status='.$_GET['id'].'';
        $result = mysqli_query($con,$sql);
        if($result)
                echo 'registro actualizado correctamente';
            else
                echo 'no se pudo actualizar';
       break;
       case 'DELETE':
            $sql = 'DELETE  from estados where id_status='.$_GET['id'].'';
            $result = mysqli_query($con,$sql);
            if($result)
                echo "registro eliminado satisfactoriamente";
            else
                echo "no se pudo eliminar el registro";
       break;
   }
}else{
    echo "DB FOUND CONNECTED";
}
?>