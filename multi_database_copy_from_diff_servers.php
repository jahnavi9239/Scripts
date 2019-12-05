<?php
//This code is used to copy all the database and tables date form one server to another server
    set_time_limit(5000);

    $serverDatabases = array('db1', 'db2', 'db3');
    $localDatabases = array('db1', 'db2', 'db3');

    //Function to connect to server databases
    function serverDbConnection($database){
        $webhost       = 'webHostIP';
        $webusername   = 'webHostUsername';
        $webpassword   = 'webHostPassword';
        $webdsn        = "mysql:host=$webhost;dbname=$database";

         // create a PDO connection with the configuration data for first db
        try{
            $pdo1 = new PDO($webdsn, $webusername, $webpassword);
            // display a message if connected to database successfully
            if($pdo1){
                echo "Connected to the <strong>$database</strong> database successfully!<br/>";
            }
        }catch (PDOException $e){
            // report error message
             echo $e->getMessage();
             //for email error message
             error_log("Error while connecting to server db.", 1,
                "email@fakedomain.com");
        }

        return $pdo1;
    }

    //Function to connect to local databases
    function localDbConnection($database){
        $localhost     = 'localHostIP';
        $localusername = 'localHostUsername';
        $localpassword = 'localHostPass';
        $localdsn      = "mysql:host=$localhost;dbname=$database";

        // create a PDO connection with the configuration data for second db
        try{
            $pdo2 = new PDO($localdsn, $localusername, $localpassword);

            if($pdo2){
                echo "Connected to the <strong>$database</strong> database successfully!<br/>";
            }
        }catch (PDOException $e){
            // report error message
            echo $e->getMessage();
            //for email error message
            error_log("Error while connecting to sql db.", 1,
               "email@fakedomain.com");
        }

        return $pdo2;
    }


    // Function fetching column names from a given table
    function columnNames($tablesData, $pdo1, $pdo2){
        foreach($tablesData as $table){
            echo "Table: $table <br/>";
            $tableName = "'".$table."'";
            
            $columnsStmt = $pdo1->query("SELECT column_name FROM information_schema.COLUMNS WHERE TABLE_NAME=$tableName and TABLE_SCHEMA=DATABASE()");
            $columnResult = $columnsStmt->fetchAll(PDO::FETCH_COLUMN, 0);
            
            $names =  "`".implode("`,`",$columnResult)."`";
            $values =  ":".implode(", :",$columnResult);
            echo "<br/>";
            echo "<br/>";
            
            //inserting data into Database
            dataInsertion($table, $pdo1, $pdo2, $names, $values);
                    
        }
    }

    //Function inserting data into tables
    function dataInsertion($table, $pdo1, $pdo2, $names, $values){
        $tableSelect = "`".$table."`"; //Adding back ticks for table name
        $insertStmt1 = $pdo2->prepare("INSERT IGNORE INTO $tableSelect ($names) VALUES ($values) ");
        $selectResults1 = $pdo1->query("SELECT * FROM $tableSelect LIMIT 10");
        print_r($selectResults1);
        if($selectResults1){
            while ($row = $selectResults1->fetch(PDO::FETCH_ASSOC)) {
                $insertStmt1->execute($row);
            }
        }else{
            echo $pdo1->errorInfo();   
        }
    }

    //Function to loop through databases;
    function databaseLooping($serverDatabases, $localDatabases){
        foreach($serverDatabases as $index => $database){            
            //Calling function for dbConnection
            $pdo1 = serverDbConnection($database);
            $pdo2 = localDbConnection($localDatabases[$index]);

            //To retrieve tables from the database
            $tablesStmt = $pdo1->query("SHOW tables;");
            $tablesData = $tablesStmt->fetchAll(PDO::FETCH_COLUMN, 0);
            
            //looping through tables and fetching column names
            columnNames($tablesData, $pdo1, $pdo2); 
        }
    }

    //calling function to loop through databases
    databaseLooping($serverDatabases, $localDatabases);
?>
