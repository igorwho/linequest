<?php

/**
 * Este arquivo é parte do software linequest
 * Ambiente de questionários para a coleta de dados
 * 
 * Laboratório de Informática na Educação - LInE
 * https://www.usp.br/line/
 * 
 * Utilize os atributos definidos abaixo para
 * configurar o ambiente de questionários.
 * 
 * @author Lucas Calion
 * @author Igor Félix
 */

    # Os dados podem ser submetidos para o linequest por
    # meio de requisições POST ou GET
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        execute($_POST);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        execute($_GET);
    }

    /**
     * Extrai todos os parâmetros enviados na requisição
     * @param $data - representa o conteúdo de $_POST ou $_GET
     */
    function execute ($data) {

        if (count($data) < 1) {
            echo print_error('empty_data');
            exit;
        }

        if (!isset($data['form'])) {
            echo print_error('key_missing');
            exit;
        }
        
        $data["ip"] = get_user_ip();
        $data["timestamp"] = time();

        require_once('../controller/forms.php');
        try {
            if (isset($data['uuid'])) {
                store(json_encode($data, JSON_UNESCAPED_UNICODE), $data['uuid']);
            } else {
                store(json_encode($data, JSON_UNESCAPED_UNICODE));
            }
        } catch (Exception $e) {
            echo print_error($e->getMessage());
            exit;
        }
        
        header('Location: thanks.html');
    }
    
    /**
     * Imprime as mensagens de erro padrão:
     * @param $code - identifica o erro a ser retornado
     */
    function print_error ($code) {
        switch($code) {
            case 'key_missing':
                return "Error: The data could not be stored. <br>Reason: The 'form' parameter was not sent in the request. This parameter is required, it identifies which form this data is associated.";
            
            case 'empty_data':
                return "Error: The data could not be stored. <br>Reason: None parameter was sent in the request.<br>";
        }
    }

    function get_user_ip () {
        $ip = "";
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
?>