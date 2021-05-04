<?php 
    spl_autoload_register(function($className) {
        include("model/" . $className . ".php");
    });

    class SchedulingWagesController {

        protected $_funcao;
        protected $_userModel;
        
        function __construct($route){
            $this->_funcao = end($route);
            $this->_userModel = new SchedulingWages();
            $this->foo($this->_funcao);
        }

        public function foo($funcao){
            return call_user_func(array($this, $funcao));
        }

        public function confirm(){
            echo json_encode($this->_userModel->confirm($_GET['id']));
        }
    }

    $route = array_filter(explode("/", $_GET['url']),'strlen');
    $login = new SchedulingWagesController($route);