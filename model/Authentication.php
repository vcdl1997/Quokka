<?php

require_once 'ConectionDB.php';

class Authentication extends ConectionDB {
    protected $_username;
    protected $_password;

    function __construct(string $username = '', string $password = ''){
        $this->_username = $username;
        $this->_password = base64_decode($password);
    }

    public function authenticate(){
        
        $stmt = $this->prepare("SELECT * FROM users WHERE username=:username");
        $parameters = [
            'username' => $this->_username
        ];
        $stmt->execute($parameters); 
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($results) == 0) return false;

        $user = current($results);

        if(!password_verify($this->_password, $user['password'])) return false;
            
        return [
            'id' => $user['id'], 
            'name' => ucfirst(current(explode(" ", $user['fullname'])))
        ];
    }
}