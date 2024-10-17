<?php
 include 'UsuarioService.php';
 header("Content-Type: application/json; charset=UTF-8");

 if ($_GET['url']){
    $url = explode('/', $_GET['url']);
    if ($url[0]==='api'){
        array_shift($url);
        $service = ucfirst($url[0]).'Service';
        array_shift($url);

        $method = strtolower($_SERVER['REQUEST_METHOD']);

        try{
            $response = call_user_func_array(array(new $service, $method),$url);
            http_response_code(200);
            echo json_encode(array('status' => 'sucess','data' => $response));
        }
        catch(Exception $e){
            http_response_code(400);
            echo json_encode(array('status' => 'error','data'=> $e->getMessage()));
        }
    }
 }

?>