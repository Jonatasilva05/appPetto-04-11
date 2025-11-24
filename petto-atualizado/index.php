<?php
// -----------------------------
// ROTEADOR PRINCIPAL PETTO MVC (suporte a classes)
// -----------------------------

session_start();

// Captura e trata a URL
$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
$path = preg_replace("#^petto/?#", "", $path);
$path = $path === "" ? "inicio" : $path;

// Divide rota/ação
$partes = explode("/", $path);
$rota = $partes[0];
$acao = $partes[1] ?? "index";

// Caminho dos controllers
$controllerPath = __DIR__ . "/app/controllers/";
$controllerFile = $controllerPath . $rota . "Controller.php";
$controllerClass = ucfirst($rota) . "Controller";

// Verifica se o controller existe
if (file_exists($controllerFile)) {
    require_once $controllerFile;

    // Se for uma classe (como AuthController)
    if (class_exists($controllerClass)) {
        $controller = new $controllerClass();

        if (method_exists($controller, $acao)) {
            $controller->$acao();
            exit;
        } else {
            echo "<h1>Erro interno: método '$acao' não encontrado na classe '$controllerClass'.</h1>";
            exit;
        }
    }

    // Se for um controller baseado em funções
    if (function_exists($acao)) {
        $acao();
        exit;
    }

    if (function_exists('index')) {
        index();
        exit;
    }
}

// Se nada for encontrado, vai para a página inicial
header("Location: /petto/inicio");
exit;
?>
