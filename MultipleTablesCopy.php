<?php
    /**
     * Connect to database
     */
    $webhost        = 'localhost';
    $webusername    = 'root';
    $webpassword    = '';
    $webdbname      = 'test';
    $webcon         = mysqli_connect($webhost, $webusername, $webpassword, $webdbname);
    if (mysqli_connect_errno())
    {
        echo 'Failed to connect to MySQL: ' . mysqli_connect_error();
    }

    /**
     * Queries for reading
     */
    // $employees = mysqli_query($webcon, 'SELECT * FROM `test1`');

    /**
     * Connect to database
     */
    $mobhost        = 'localhost';
    $mobusername    = 'root';
    $mobpassword    = '';
    $mobdbname      = 'test_bak';
    $mobcon         = mysqli_connect($mobhost, $mobusername, $mobpassword, $mobdbname);
    if (mysqli_connect_errno())
    {
        echo 'Failed to connect to MySQL: ' . mysqli_connect_error();
    }

    /**
     * Insert data from old database
     */

    // Create an array of MySQL queries to run
    $tables = array();
    $list_tables_sql = "SHOW TABLES FROM $webdbname;";
    $result = mysqli_query($webcon, $list_tables_sql);
    if($result)
        while($table = mysqli_fetch_row($result))
    {
        $tables[] = $table[0];
    }

    foreach($tables as $table) {
        echo $table, '<br>';
        $webtab = "$webdbname.$table";
        $mobtab = "$mobdbname.$table";
        $sql = array(
            "DROP TABLE IF EXISTS $mobtab;",
            "CREATE TABLE $mobtab SELECT * FROM $webtab"
        );
        // Run the MySQL queries
         if (sizeof($sql) > 0) {
            foreach ($sql as $query) {
                if (!$mobcon->query($query)) {
                    die('A MySQL error has occurred!<br><br>' . $mobcon->error);
                }
            }
        }
        echo 'insterted';
    }
    
    /*
    Close Connections
    */
    mysqli_close($mobcon);
    mysqli_close($webcon);
?>
