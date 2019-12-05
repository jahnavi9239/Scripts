<?php

    $username = 'root';
    $password = '';
    $database = 'test';
    // Create a new MySQL database connection
    if (!$con = new mysqli('localhost', $username, $password, $database)) {
        die('An error occurred while connecting to the MySQL server!<br><br>' . $con->connect_error);
    }
    
    // Create an array of MySQL queries to run
    $sql = array(
        'DROP TABLE IF EXISTS test_bak.test_bak;',
        'CREATE TABLE test_bak.test_bak SELECT * FROM test.test1'
    );
    
    // Run the MySQL queries
    if (sizeof($sql) > 0) {
        foreach ($sql as $query) {
            if (!$con->query($query)) {
                die('A MySQL error has occurred!<br><br>' . $con->error);
            }
        }
    }
    
    $con->close();
?>
