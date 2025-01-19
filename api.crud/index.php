<?php
// Definindo os cabeçalhos CORS para permitir requisições de qualquer origem
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");  // Permite qualquer origem acessar a API
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");  // Permite os métodos que serão usados
header("Access-Control-Allow-Headers: Content-Type, Authorization");  // Permite cabeçalhos específicos
header("Access-Control-Max-Age: 86400");  // Cache da resposta de pré-vôo por 24 horas
header("Cache-Control: no-cache, no-store, must-revalidate");  // Evita cache

// Verificando se a requisição é do tipo OPTIONS (preflight request)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Se for uma requisição OPTIONS (de pré-vôo), responda com um status OK
    http_response_code(200);
    exit();  // Finaliza a execução aqui, sem continuar o processamento da requisição
}

// Inclusão dos arquivos das classes
include 'UsuarioService.php';
include 'LoginService.php';

// Verificando a URL
if (isset($_GET['url'])) {
    $url = explode('/', $_GET['url']);  // Divide a URL em partes

    // Verificando se a URL começa com 'api'
    if ($url[0] === 'api') {
        array_shift($url);  // Remove 'api' da URL
        $service = ucfirst($url[0]) . 'Service';  // Cria o nome da classe (ex: LoginService)
        array_shift($url);  // Remove o nome do serviço da URL

        // Obtém o método da requisição (GET, POST, PUT, DELETE)
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        // Verifica se a classe e o método existem
        if (class_exists($service) && method_exists($service, $method)) {
            try {
                // Chama o método dinâmico da classe
                $response = call_user_func_array(array(new $service, $method), $url);
                http_response_code(200);  // Retorna um status 200 OK
                echo json_encode(array('status' => 'success', 'information' => $response));  // Retorna os dados em JSON
            } catch (Exception $e) {
                // Em caso de erro, retorna o erro no formato JSON
                http_response_code(400);
                echo json_encode(array('status' => 'error', 'information' => $e->getMessage()));
            }
        } else {
            // Se o serviço ou o método não existe, retorna erro
            http_response_code(400);
            echo json_encode(array('status' => 'error', 'information' => 'Serviço ou método inválido.'));
        }
    } else {
        // Caso a URL não seja válida (não comece com 'api')
        http_response_code(400);
        echo json_encode(array('status' => 'error', 'information' => 'URL inválida.'));
    }
} else {
    // Se o parâmetro 'url' não for passado na requisição
    http_response_code(400);
    echo json_encode(array('status' => 'error', 'information' => 'URL não especificada.'));
}
?>
