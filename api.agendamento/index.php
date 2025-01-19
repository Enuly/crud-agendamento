<?php
include 'agendamentoService.php';
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Max-Age: 86400");

if (isset($_GET['url'])) {
    $url = explode('/', $_GET['url']);
    if ($url[0] === 'api') {
        array_shift($url);
        $service = ucfirst($url[0]) . 'Service';
        array_shift($url);

        $method = strtolower($_SERVER['REQUEST_METHOD']);

        try {
            // Verificar se está tentando verificar a disponibilidade
            if ($service === 'AgendamentoService' && isset($_GET['verificar-disponibilidade']) && $method === 'get') {
                $data_hora = $_GET['data_hora'];
                $agendamentoService = new AgendamentoService();
                $response = $agendamentoService->verificarDisponibilidade($data_hora);

                http_response_code(200);
                echo json_encode(['status' => 'success', 'disponivel' => $response]);
            } else {
                // Caso contrário, tratar outras requisições normais
                $response = call_user_func_array(array(new $service, $method), $url);
                http_response_code(200);
                echo json_encode(['status' => 'success', 'information' => $response]); // Alteração de 'data' para 'information'
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'information' => $e->getMessage()]); // Alteração de 'data' para 'information'
        }
    }
}
?>