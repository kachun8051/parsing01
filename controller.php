<?php
    header('Access-Control-Allow-Origin: *');
    class Controller {
        
        private $serviceProvider;
        // $paramsInBody for POST or PUT Method only
        private $paramsInBody;
        // $urlSegments for GET or DELETE Method
        private $urlSegments;

        function __construct() {
            if (!isset($_SERVER['PATH_INFO']) or $_SERVER['PATH_INFO']=='/' ) {
                echo 'please provide parameters';
                exit;
            }
            //parameters received
		    $this->urlSegments = explode('/', $_SERVER['PATH_INFO']);
            $temp = $this->urlSegments;
		    array_shift($this->urlSegments);		
		    $resource = array_shift($this->urlSegments);
		    $resource = ucfirst($resource);
		    $serviceName = $resource.'Service';
		    $serviceFilename = $resource.'Service'.'.php';
            //retrieve the body 
            $input = file_get_contents('php://input');    
            //$sendingData = $_POST['sendingdata'];       
            if ($input === null) {
                $this->paramsInBody = null;
            } else {
                $this->paramsInBody = json_decode($input);   
                $obj = $this->paramsInBody; 
                //echo ("params in body: " . $this->paramsInBody);            
            }
            //echo ($this->paramsInBody);
            //exit;
		    if (file_exists($serviceFilename)) {
			    require_once $serviceFilename;
			    $this->serviceProvider = new $serviceName;
		    } else {
			    echo 'no such resource';
		    }
        }
        
        function run() {
            $httpMethod = $_SERVER['REQUEST_METHOD'];
            $httpMethod = ucfirst(strtolower($httpMethod));
            $functionName = 'rest'.$httpMethod;
            //dynamic binding
            //$this->serviceProvider->$functionName($this->urlSegments);

            if ($this->paramsInBody == null && $this->urlSegments == null) {
                $this->serviceProvider->$functionName();   
            } else {
                $input = '';
                if ($httpMethod == 'Get' || $httpMethod == 'Delete') {
                    $input = json_encode($this->urlSegments);
                    $this->serviceProvider->$functionName($this->urlSegments);
                } else {
                    $input = json_encode($this->paramsInBody);
                    $this->serviceProvider->$functionName($this->paramsInBody);
                }                
            }            	
        }
    }
    $controller = new Controller();
    $controller->run();
?>