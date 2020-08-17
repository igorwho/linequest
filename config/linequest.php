<?php

/**
 * Este arquivo é parte do software linequest
 * Laboratório de Informática na Educação - LInE
 * https://www.usp.br/line/
 * 
 * Utilize os atributos definidos abaixo para
 * configurar o ambiente de questionários.
 * 
 * @author Lucas Calion
 * @author Igor Félix
 */
    global $CFG;
    $CFG = new stdClass();
    
    $CFG->dbhost    = 'localhost'; # Endereço do hospedeiro a ser acessado
    $CFG->dbname    = ''; # Nome da base criada no MySQL ou MariaDB
    $CFG->dbuser    = '';  # Usuário com permissão de leitura/escrita na base
    $CFG->dbpass    = ''; # Senha do usuário

?>