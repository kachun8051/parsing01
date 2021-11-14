<?php
    class BarbecueService{

        private $server = "localhost";
        private $dbuser = "root";
        private $dbpassword = "";
        private $dbname = "bbq";

        function restGet() {
            //echo "abc";
            //exit;
            $conn = new mysqli($this->server, $this->dbuser, $this->dbpassword, $this->dbname);
            if ($conn->connect_error) {
	            //die ("Database failed");
                echo json_encode(array("issuccess"=>false, "errcode"=>"102", "errmsg"=>"Database connection failure"));
                exit;
            }
            $resultArray = array();
            $sql = "SELECT GIHS, name, district, district_cn, address, longitude, latitude FROM tblbbq";
            //echo $sql;
            //exit;
			if ($dbresult=$conn->query($sql)) {
                $dataArray = array();
				// records retrieved
				while ( $row=$dbresult->fetch_object()  ) {
					$record = array();
                    $record['GIHS'] = $row->GIHS;
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
            //echo "test";
            //exit;
            echo json_encode($resultArray);
        }

        //$params is json object
        function restDelete($params) {
            if ($params === null) {
                echo json_encode(array("issuccess"=>false, "errcode"=>"101", "errmsg"=>"No parems provided"));
                exit;
            }        
            //echo $params[0];
            //exit;    
            if ($params[0] !== 'GIHS') {
                echo json_encode(array("issuccess"=>false, "errcode"=>"101", "errmsg"=>"No GIHS provided", "input"=> json_encode($params)));
                exit;
            }
			$conn = new mysqli($this->server, $this->dbuser, $this->dbpassword, $this->dbname);
            if ($conn->connect_error) {
	            //die ("Database failed");
                echo json_encode(array("issuccess"=>false, "errcode"=>"102", "errmsg"=>"Database connection failure"));
                exit;
            }
            //echo $params;
            //exit;
            $GIHS = $params[1];
			$sql = "DELETE FROM tblbbq where GIHS='$GIHS'";
			if ($dbresult=$conn->query($sql)) {
				echo json_encode(array("issuccess"=>true, "msg"=>"bbq facility is deleted"));
				exit;
			} else {
				$arr = array();
				//$arr["status"] = "error";
                $arr["issuccess"]=false;
				$arr["errcode"] = "103";
				$arr["errmsg"] = "SQL failed to delete booking record";
				echo json_encode($arr);
				exit;
			}			
		}        
    }
?>