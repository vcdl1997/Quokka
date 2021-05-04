<?php

spl_autoload_register(function($className) {
    include("model/" . $className . ".php");
});

class AuthenticatorController {

    protected $_funcao;
    
    function __construct(){
        $this->_funcao = explode("/", $_GET['url'])[0];
        $this->foo($this->_funcao);
    }

    public function foo($funcao){
        return call_user_func(array($this, $funcao));
    }

    public function enter(){
        extract($_GET);

        $user = new Authentication($username, $password);
        $user = $user->authenticate();

        if($user) $_SESSION['user'] = $user;

        echo !$user ? 
            json_encode([ 'message' => "Usuário não encontrado", 'status' => 400 ]) : 
            json_encode([ 'message' => "Seja Bem vindo: ".$_SESSION['user']['name'], 'status' => 200 ]);
    }


    public function exit(){
        if(!empty($_SESSION['user'])) $_SESSION['user'] = [];

        echo empty($_SESSION['user']) ? 
            json_encode([ 'message' => "Usuário deslogado!", 'status' => 200 ]) : 
            json_encode([ 'message' => "Erro ao deslogar usuário!", 'status' => 400 ]);
    }
}

$login = new AuthenticatorController();