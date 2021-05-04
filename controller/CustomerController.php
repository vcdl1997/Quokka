<?php

spl_autoload_register(function($className) {
    include("model/" . $className . ".php");
});

class CustomerController {

    protected $_funcao;
    protected $_customerModel;
    
    function __construct($route){
        $this->_funcao = end($route);
        $this->_customerModel = new Customer();
        $this->foo($this->_funcao);
    }

    public function foo($funcao){
        return call_user_func(array($this, $funcao));
    }

    public function list(){
        echo json_encode($this->_customerModel->list($_REQUEST));
    }

    public function save(){
        echo json_encode($this->_customerModel->createOrUpdate($_REQUEST));
    }

    public function delete(){
        echo json_encode($this->_customerModel->delete($_GET['id']));
    }
    
}

$route = array_filter(explode("/", $_GET['url']),'strlen');
$login = new CustomerController($route);