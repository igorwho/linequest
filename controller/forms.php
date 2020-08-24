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

 function get_id_by_uuid ($uuid) {
    global $DB; connect();

    $sql = "SELECT * FROM records";

    if (!($stmt = $DB->prepare($sql))) {
        echo "Prepare failed: (" . $DB->errno . ") " . $DB->error;
    }
    
    $stmt->execute();
    
    $res_data = mysqli_stmt_get_result($stmt);
    
    $all_data = array();
    
    while($row = mysqli_fetch_array($res_data)){
        $data = json_decode($row['form']);
        if (isset($data->uuid) && ($data->uuid == $uuid)) {
            return $row['id'];
        }
    }
    return -1;
 }

 function store ($data, $uuid = null) {
    global $DB; connect();

    $sql = "";

    $id_p = get_id_by_uuid($uuid);
    if ($id_p >= 0) {
        $sql =  "UPDATE records SET form = ?
                WHERE id = " . $id_p;
    } else {
        $sql =  "INSERT INTO records (id, form)
                VALUES (null, ?)";
    }
    
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

 function get_forms () {
    global $DB; connect();

    $sql = "SELECT form FROM records";

    if (!($stmt = $DB->prepare($sql))) {
        echo "Prepare failed: (" . $DB->errno . ") " . $DB->error;
    }

    $stmt->execute();

    $res_data = mysqli_stmt_get_result($stmt);

    $all_forms = array();

    while($row = mysqli_fetch_array($res_data)){
        $data = json_decode($row['form']);
        if(!in_array($data->form, $all_forms)){
            $all_forms[] = $data->form;
        }
    }

    sort($all_forms);

    return $all_forms;
 }

 function get_total_rows ($form_id) {
    global $DB; connect();
    
    $sql = "SELECT form FROM records";

    if (!($stmt = $DB->prepare($sql))) {
        echo "Prepare failed: (" . $DB->errno . ") " . $DB->error;
    }
    
    $stmt->execute();

    $res_data = mysqli_stmt_get_result($stmt);

    $count = 0;

    while($row = mysqli_fetch_array($res_data)){
        $data = json_decode($row['form']);
        if ($data->form == $form_id) {
            $count ++;
        }
    }

    return $count;
 }

 function get_records_pagination($form_id, $offset, $no_of_records_per_page) {
    global $DB; connect();

    $sql = "SELECT * FROM records";

    if (!($stmt = $DB->prepare($sql))) {
        echo "Prepare failed: (" . $DB->errno . ") " . $DB->error;
    }
    
    $stmt->execute();
    
    $res_data = mysqli_stmt_get_result($stmt);
    
    $all_data = array();

    $count = 0;
    $included = 0;
    
    while($row = mysqli_fetch_array($res_data)){
        $data = json_decode($row['form']);
        if ($data->form == $form_id) {
            $data->id = $row['id'];
            $count++;
            if ($count-1 >= $offset) {
                $included ++;
                if ($included-1 == $no_of_records_per_page) break;
                $all_data[] = $data;
            }
            
        }
    }
    return $all_data;
 }

 ?>