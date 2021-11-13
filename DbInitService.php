<?php
    class DbInitService {
        //http://localhost/controller.php/dbinit
        function restGet(){
            require "clsDbInit.php";
            $obj = new clsDbInit;
            $isdbExist = $obj->checkDbExist();
            if ($isdbExist === false) {
                $isdbCreated = $obj->createDb();
                if ($isdbCreated === false){
                    //$isInserted = $obj->insertRecords();
                    die ("database created error!");
                }       
            }    
            $isTableExist = $obj->checkTableExist();
            if ($isTableExist === false) {
                $isTableCreated = $obj->createTable();
                if ($isTableCreated === false){
                    die ("table created error!");
                }
            }
            $isTableEmpty = $obj->checkTableEmpty();
            if ($isTableEmpty === true) {
                $isInserted = $obj->insertRecords();
                if ($isInserted === false) {
                    die ("table created error!");
                }
            }
            echo($obj->fetchAllRecords());
        }

    }
?>