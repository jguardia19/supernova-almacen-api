<?php
// conexion con base de datos 
include '../conexion/conn.php';
//incluir middleware
include '../middleware/midleware.php';

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept,Authorization, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){

    $methodApi = $_SERVER['REQUEST_METHOD'];
    if($validate === 'validado'){
        if($methodApi == 'GET'){
            if(isset($_GET['codigo'])){
                // es para obtener todos los registros  por codigo
                $sql = 'SELECT *FROM productos where codigo="'.$_GET['codigo'].'"';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response[$i]['id'] = $row['id'];
                    $response[$i]['nombre'] = $row['nombre'];
                    $response[$i]['codigo'] = $row['codigo'];
                    $response[$i]['almacen'] = $row['almacen'];
                    $response[$i]['inventario'] = $row['inventario'];
                    $response[$i]['cantidad'] = null;
                    $response[$i]['precio'] = $row['preciou'];
                    $i++;
                }
                echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                if(isset($_GET['id'])){
                    $sql = 'SELECT *FROM productos  where id="'.$_GET['id'].'"';
                    $result = mysqli_query($con,$sql);
                    $i=0;
                    while($row = mysqli_fetch_assoc($result)){
                        $response['id'] = $row['id'];
                        $response['nombre'] = $row['nombre'];
                        $response['codigo'] = $row['codigo'];
                        $response['almacen'] = $row['almacen'];
                        $i++;
                    }
                    echo json_encode($response,JSON_PRETTY_PRINT);
                } else{
                    if(isset($_GET['total'])){
                        // es para obtener todos los registros 
                        $sql = 'SELECT *FROM productos';
                        $result = mysqli_query($con,$sql);
                        $i=0;
                        while($row = mysqli_fetch_assoc($result)){
                            $response[$i]['id'] = $row['id'];
                            $response[$i]['nombre'] = $row['nombre'];
                            $response[$i]['codigo'] = $row['codigo'];
                            $response[$i]['almacen'] = $row['almacen'];
                            $response[$i]['preciou'] =  $row['preciou'];
                            $response[$i]['preciom'] =  $row['preciom'];
                            $response[$i]['precioc'] =  $row['precioc'];
                            $response[$i]['preciov'] =  $row['preciov'];
                            $response[$i]['topem'] =  $row['topem'];
                            $response[$i]['topec'] =  $row['topec'];
                            $response[$i]['topev'] =  $row['topev'];
                            $response[$i]['descuento'] =  $row['descuento'];
                            $response[$i]['descuento_precio_docena'] =  $row['descuento_precio_docena'];
                            if($row['estatus'] == "0"){
                                $response[$i]['estatus'] = false;
                            }else{
                                $response[$i]['estatus'] = true;
                            }
                            $response[$i]['fullname'] = $row['codigo']." ".$row['nombre'];
                            $i++;
                        }
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                    }else{
                        // es para obtener todos los registros 
                        $sql = 'SELECT id,codigo,nombre,almacen FROM productos GROUP BY codigo';
                        $result = mysqli_query($con,$sql);
                        $i=0;
                        while($row = mysqli_fetch_assoc($result)){
                            $response[$i]['id'] = $row['id'];
                            $response[$i]['nombre'] = $row['nombre'];
                            $response[$i]['codigo'] = $row['codigo'];
                            $response[$i]['almacen'] = $row['almacen'];
                            $response[$i]['fullname'] = $row['codigo']." ".$row['nombre'];
                            $i++;
                        }
                        echo  json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
                    }
                    
                }
            }
        }

        if($methodApi == 'POST'){
            $_POST = json_decode(file_get_contents('php://input'),true);
            $sqlUpdateProducto = 'UPDATE productos SET estatus='.$_POST['estatus'].' WHERE  id='.$_POST['id_producto'].'';
            $resUpdate = mysqli_query($con,$sqlUpdateProducto);

            if($resUpdate){
                header("HTTP/1.1 200 OK");
                $response['status'] = 200;
                $response['mensaje'] = 'Registro actualizado correctamente';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                header("HTTP/1.1 400");
                $response['status'] = 400;
                $response['mensaje'] = 'No se pudo Guardar el registro';
                echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        }
    }else{
        header("HTTP/1.1 200");
        $response['status'] = 401;
        $response['mensaje'] = 'Token '.$validate;
        echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
  
}else{
    echo "DB FOUND CONNECTED";
}
?>