<?php
    session_start();
    if (!isset($_SESSION['hash_user'])) {
        header('Location: login.php');
        exit;
    }
?>  

<?php

    $form_id = $_GET['formulario'];

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename='.$form_id.'.csv');

    require_once('../controller/forms.php');

    $total_rows = get_total_rows($form_id);

    $res_data = get_records_pagination($form_id, 0, $total_rows);

    $output = fopen('php://output', 'w');

    
    $all_fields = array();

    foreach($res_data as $form) {
        foreach($form as $key => $val) {
            if(!in_array($key, $all_fields)) {
                $all_fields[] = $key;
            }
        }
    }

    fputcsv($output, $all_fields);


    foreach($res_data as $form) {

        $prepared = array();
        foreach($all_fields as $field) {
            
            if ($field === 'timestamp') {
                $prepared[] = date("H:i:s d/m/Y", $form->$field);
                continue;
            }
            $prepared[] = $form->$field;
            
        }
        fputcsv($output, $prepared);
    }

?>