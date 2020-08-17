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

 require_once ('../config/linequest.php');

 function connect () {
    global $CFG, $DB;
    $DB  = new mysqli($CFG->dbhost, $CFG->dbuser, $CFG->dbpass, $CFG->dbname);
    if ($DB->connect_error) {
       die("Connection failed: " . $DB->connect_error);
    }
    $DB->set_charset("utf8");
 }

 function store ($data) {
    global $DB; connect();

    echo $data;
    
    $sql = "INSERT INTO records (id, form)
            VALUES (null, ?)";

    if (!($stmt = $DB->prepare($sql))) {
        echo "Prepare failed: (" . $DB->errno . ") " . $DB->error;
    }
    
    if (!$stmt->bind_param("s", $data)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    $DB->close();
 }

 ?>