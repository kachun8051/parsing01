<?php

    class clsDbInit {
        private $server = "localhost";
        private $dbuser = "root";
        private $dbpassword = "";
        private $dbname = "bbq";

        function checkDbExist() {
            $isexist = false;
            $conn = new mysqli($this->server, $this->dbuser, $this->dbpassword, $this->dbname);
            if ($conn->connect_error) {
                //die ("database connection failed");
                $isexist = false;
            } else {
                $isexist = true;
                $conn->close();
            }            
            return $isexist;
        }

        function createDb() {
            $iscreated = false;
            $conn = new mysqli($this->server, $this->dbuser, $this->dbpassword);
            // If database is not exist create one
            if (!mysqli_select_db($conn, $this->dbname)){
                $sql = "CREATE DATABASE " . $this->dbname . " DEFAULT CHARSET utf8";
                //echo($sql."<br/>");
                if ($conn->query($sql) === TRUE) {
                    //echo "Database created successfully";
                    $iscreated = true;
                } else {
                    //die("Error creating database: " . $conn->error);
                    $iscreated = false;
                }            
            } else {
                $iscreated = true;
            }
            $conn->close();
            return $iscreated;
        }

        function checkTableExist() {
            $isexist = false;
            // First, establish a new connection for creating table if not exists
            $conn = new mysqli($this->server, $this->dbuser, $this->dbpassword, $this->dbname);
            if ($conn->connect_error) {                
                $isexist = false;
                die ("database connection failed");                
            } else {
                $sql = "SELECT COUNT(*) FROM `tblbbq`";
                if (!$result=$conn->query($sql)) {
                    $isexist = false;
                } else {
                    $isexist = true;
                }
            }
            $conn->close();   
            return $isexist; 
        }

        function createTable() {
            $iscreated = false;
            $conn = new mysqli($this->server, $this->dbuser, $this->dbpassword, $this->dbname);
            if ($conn->connect_error) {
                $isexist = false;
                die ("database connection failed");
            } else {
                // Second, create table if not exists
                $sql  = "CREATE TABLE IF NOT EXISTS `tblbbq`(";
                $sql .= "name TEXT, district TEXT, district_cn TEXT, address TEXT, longitude TEXT, latitude TEXT) ";
                $sql .= "DEFAULT CHARSET=utf8";
                if (!$result=$conn->query($sql)){
                    //die ("failed to create table");
                    $iscreated = false;
                } else {
                    $iscreated = true;
                }
            }
            return $iscreated;
        }

        function checkTableEmpty() {
            $isempty = true;
            $conn = new mysqli($this->server, $this->dbuser, $this->dbpassword, $this->dbname);
            if ($conn->connect_error) {
                $isempty = false;
                die ("database connection failed");
            } else {
                $sql = "SELECT COUNT(*) FROM `tblbbq`";
                $result = $conn->query($sql);                
                if (!$result) {
                    $isempty = false;
                    die ("database query failed");
                } else {
                    $rows = mysqli_fetch_row($result);                
                    if ($rows[0] > 0) {
                        $isempty = false;
                    } else {
                        $isempty = true;
                    }
                }
            }
            $conn->close();
            return $isempty;
        }

        function insertRecords() {
            
            $jsonFile = file_get_contents('https://www.lcsd.gov.hk/datagovhk/facility/facility-bbqs.json');
            if (!isset($jsonFile)) {                
                die ("Url is invalid. Json file not found!");
                return false;
            }
            $isinserted = true;
            $conn = new mysqli($this->server, $this->dbuser, $this->dbpassword, $this->dbname);
            $bbqs = json_decode($jsonFile, true);
            foreach ($bbqs as $bbq) {
                $district = str_replace("'", "&apos;", $bbq['District_en']);
                //$name = $bbq['Name_en']   .replace("'", "&quot;");
                $district_cn = str_replace("'", "&apos;", $bbq['District_cn']);
                $name = str_replace("'", "&apos;", $bbq['Name_en']);
                $address = str_replace("'", "&apos;", $bbq['Address_en']);  
                $longitude = str_replace("'", "&apos;", $bbq['Longitude']);
                $latitude = str_replace("'", "&apos;", $bbq['Latitude']);        
                $sql = "INSERT INTO tblbbq VALUES ('$name', '$district', '$district_cn', '$address', '$longitude', '$latitude')";
                if (!$result=$conn->query($sql)) {
                    die ("insertion failed");
                    $isinserted = false;
                }
            }
            $conn->close();
            return $isinserted;
        }

        function deleteAllRecords() {
            
            $conn = new mysqli($this->server, $this->dbuser, $this->dbpassword, $this->dbname);
            if ($conn->connect_error) {
                die ("database connection failed");
                return false;
            }
            $isdeleted = true;
            $sql = "DELETE FROM `tblbbq`";
            //echo ($sql."<br/>");
            if ($conn->query($sql) === FALSE) {
                die ("failed to delete table");
                $isdeleted = false;    
            }
            $conn->close();
            return $isdeleted;
        }

        function fetchAllRecords(){            
            $conn = new mysqli($this->server, $this->dbuser, $this->dbpassword, $this->dbname);
            if ($conn->connect_error) {
                //$successArray = array();
                die ("database connection failed");
                return array("issuccess"=>false);
            }
            $resultArray = array();
            $sql = "SELECT name, district, district_cn, address, longitude, latitude FROM tblbbq";
			if ($dbresult=$conn->query($sql)) {
                $dataArray = array();
				// records retrieved
				while ( $row=$dbresult->fetch_object()  ) {
					$record = array();
					$record['name'] = $row->name;
					$record['district'] = $row->district;
					$record['district_cn'] = $row->district_cn;
					$record['address'] = $row->address;
					$record['longitude'] = $row->longitude;
					$record['latitude'] = $row->latitude;
					$dataArray[] = $record;
				}
				//echo json_encode($resultArray);
                $resultArray = array("issuccess"=>true, "data"=>$dataArray);
			}
            $conn->close();
            return json_encode($resultArray);
        }

    }
?>