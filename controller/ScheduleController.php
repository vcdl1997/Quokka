<?php 
    spl_autoload_register(function($className) {
        include("model/" . $className . ".php");
    });

    class ScheduleController {

        protected $_funcao;
        protected $_userModel;
        
        function __construct($route){
            $this->_funcao = end($route);
            $this->_userModel = new Schedule();
            $this->foo($this->_funcao);
        }

        public function foo($funcao){
            return call_user_func(array($this, $funcao));
        }

        public function list(){
            echo json_encode($this->_userModel->list());
        }

        public function create(){
            echo json_encode($this->_userModel->create($_REQUEST));
        }

        public function delete(){
            echo json_encode($this->_userModel->delete($_GET['id']));
        }
    }

    $route = array_filter(explode("/", $_GET['url']),'strlen');
    $login = new ScheduleController($route);