<?php

spl_autoload_register(function($className) {
    include("model/" . $className . ".php");
});

class UserController {

    protected $_funcao;
    protected $_userModel;
    
    function __construct($route){
        $this->_funcao = end($route);
        $this->_userModel = new User();
        $this->foo($this->_funcao);
    }

    public function foo($funcao){
        return call_user_func(array($this, $funcao));
    }

    public function list(){
        echo json_encode($this->_userModel->list($_REQUEST));
    }

    public function save(){
        echo json_encode($this->_userModel->createOrUpdate($_REQUEST));
    }

    public function delete(){
        echo json_encode($this->_userModel->delete($_GET['id']));
    }
    
}

$route = array_filter(explode("/", $_GET['url']),'strlen');
$login = new UserController($route);