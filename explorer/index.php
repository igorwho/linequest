
<?php
    session_start();
    if (!isset($_SESSION['hash_user'])) {
        header('Location: login.php');
        exit;
    }
?>  

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>LInE Quest</title>
    <style>
        body {
            font-family: Arial;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 1em;
        }

        table td, table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table tr:nth-child(even){background-color: #f2f2f2;}

        table tr:hover {background-color: #ddd;}

        table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }

        .pagination {
            display: inline-block;
            margin-top: 1em;
        }

        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
        }

        .pagination a:hover:not(.active) {background-color: #ddd;}

        .pagination a:first-child {
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        .pagination a:last-child {
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .disabled {

            pointer-events: none;
            cursor: default;
            text-decoration: none;
            color: #918a8a !important;;
            background-color: #f0efef;
        }

        .export {
            float: right;
        }

        .total {
            float: left;
        }
    </style>
</head>
<body>

    <?php 
        require_once('../controller/forms.php');
        $all_forms = get_forms();
    ?>

    <form>
        Selecione o identificador do formulário: 
            <select name="formulario" onchange="this.form.submit()">
                <option></option>
                <?php
                    foreach($all_forms as $form) {
                        if ((isset($_GET['formulario'])
                            && $_GET['formulario'] == $form)) {
                            print "<option selected>$form</option>";
                        } else {
                            print "<option>$form</option>";
                        }
                    }
                ?>
            </select>
    </form>
        
    <table>
    <?php
        if ((!isset($_GET['formulario']))) exit;

        if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
        } else {
            $pageno = 1;
        }

        $no_of_records_per_page = 10;
        $offset = ($pageno-1) * $no_of_records_per_page;
        $form_id = $_GET['formulario'];

        $total_rows = get_total_rows($form_id);
        $total_pages = ceil($total_rows / $no_of_records_per_page);

        $res_data = get_records_pagination($form_id, $offset, $no_of_records_per_page);
        
        $all_fields = array();

        foreach($res_data as $form) {
            foreach($form as $key => $val) {
                if(!in_array($key, $all_fields)) {
                    $all_fields[] = $key;
                }
            }
        }

        print '<tr> <th>id</th>';
        foreach($all_fields as $field) {
            if ($field == 'id') continue;
            print "<th>$field</th>";
        }
        print '</tr>';

        foreach($res_data as $form) {

            print '<tr>';
            print '<td>'.$form->id.'</td>';
            foreach($all_fields as $field) {
                if ($field == 'id') continue;
                print '<td>';
                if ($field === 'timestamp') {
                    print date("H:i:s d/m/Y", $form->$field).'</td>';
                    continue;
                }
                print $form->$field.'</td>';
            
            }
            print '</tr>';
        }
        
    ?>
    </table>
    <div class="total">
        Total de registros: <b><?= $total_rows ?></b>
    </div>
    <div class="export">
        Exportar como: <a href="export.php?formulario=<?= $form_id ?>&format=csv">CSV</a>
    </div>
    <center>
        <div class="pagination">
            <a class="<?php if($pageno <= 1){ echo 'disabled'; } ?>" href="?formulario=<?= $form_id ?>&pageno=1">Primeira</a>

            <a class="<?php if($pageno <= 1){ echo 'disabled'; } ?>" href="<?php if($pageno <= 1){ echo '#'; } else { echo "?formulario=$form_id&pageno=".($pageno - 1); } ?>">Anterior</a>
            
            <a class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?formulario=$form_id&pageno=".($pageno + 1); } ?>">Próxima</a>
            <a class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>" href="?formulario=<?= $form_id ?>&pageno=<?php echo $total_pages; ?>">Última</a>
        </div>
    </center>
</body>
</html>