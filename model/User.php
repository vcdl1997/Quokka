<?php

require_once 'ModelTrait.php';

class User extends ModelTrait {

    public $_table = 'users';
    
    /*
        Lista os registros
    */
    public function list($filters){
        $search = $filters['search']['value'];
        $columns = [ 'fullname', 'cell_phone', 'email_address' ];

        $query = "SELECT * FROM " . $this->_table . " WHERE 1=1";
        if( !empty($search) ) {
            $query.=" AND ( fullname LIKE '".$search."%' ";    
            $query.=" OR cell_phone LIKE '".$search."%' ";
            $query.=" OR email_address LIKE '".$search."%' )";
        }
        $query .= " ORDER BY ". $columns[$filters['order'][0]['column']]."   ".$filters['order'][0]['dir']."  LIMIT ".$filters['start']." ,".$filters['length']."   ";
        
        $stmt = $this->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total = count($results);

        return [
            "draw" => intval( $filters['draw'] ), 
            "recordsTotal" => intval($total),   
            "recordsFiltered" => intval($total),
            "data" => $results  
        ];
    }

    /*
        Cadastra ou atualiza o registro
    */
    public function createOrUpdate($filters){
        try{
            extract($filters);

            $message = "";

            $parameters = [
                'id' => intval($id),
                'fullname' => $fullname,
                'username' => $username,
                'email_address' => $email_address,
                'home_phone' => $home_phone,
                'cell_phone' => $cell_phone,
                'password' => password_hash(base64_decode($password), PASSWORD_DEFAULT),
                'password_extension' => intval($password_extension),
                'gender' => $gender
            ];

            if(!empty($id)){

                $validation = $this->whatHasChanged(intval($id), $parameters);
                if($validation['status'] == 400) return $validation;

                $query = "
                    UPDATE " . $this->_table . " 
                    SET 
                        fullname = :fullname,
                        username = :username,
                        email_address = :email_address,
                        home_phone = :home_phone,
                        cell_phone = :cell_phone,
                        password = :password,
                        password_extension = :password_extension,
                        gender = :gender
                    WHERE
                        id = :id
                ";  

                $stmt = $this->prepare($query);
                $stmt->execute($parameters); 
                $message = 'atualizado';

            }else{

                unset($parameters['id']);

                $validation = $this->validateRepeatedData($username, $email_address, $cell_phone);
                if($validation['status'] == 400) return $validation;

                $query = "
                    INSERT INTO " . $this->_table . " (fullname, username, email_address, home_phone, cell_phone, password, password_extension, gender) 
                    VALUES (:fullname, :username, :email_address, :home_phone, :cell_phone, :password, :password_extension, :gender)
                ";  
                $stmt = $this->prepare($query);
                $stmt->execute($parameters); 

                $id = $this->lastInsertID();
                $message = 'cadastrado';

            }
        
            return [ 'id' => $id, 'message' => "Usuário {$message}!", 'status' => 200 ];

        } catch (Exception $e) {
            // die(var_dump($e->getMessage()));
            return [ 'message' => "Erro ao atualizar!", 'status' => 400 ];
        }
    }

    /*
        Exclui o usuário
    */
    public function delete($id)
    {
        try{
            $this->deleteRegister($id);
            
            return [ 'id' => $id, 'message' => "Usuário excluido com sucesso!", 'status' => 200 ];

        } catch (Exception $e) {
            die(var_dump($e->getMessage()));
            return [ 'message' => "Erro ao excluir usuário!", 'status' => 400 ];
        }
    }

    /*
        Verifica o que mudou em comparação ao registro original,
        se houver ao menos uma mudança nos seguintes campos: (nome de usuário, e-mail e celular) 
        é executado o metodo validateRepeatedData();
    */
    public function whatHasChanged(int $id, array $dataForm){
        $dataBD = current($this->findByID($id));

        $differences = array_diff($dataBD, $dataForm);

        $affectedFields = array_intersect(['username', 'email_address', 'cell_phone'], array_keys($differences));

        if(count($affectedFields) == 0){
            return [ 
                'status' => 200, 
                'message' => "" 
            ];
        }

        $affectedFields = array_values($affectedFields);

        foreach($dataForm as $index => $value){
            if(!(in_array($index, $affectedFields))) $dataForm[$index] = "";
        };

        return $this->validateRepeatedData($dataForm['username'], $dataForm['email_address'], $dataForm['cell_phone']);
    }

    /*
        Valida se já existem registros utilizando o nome de usuário ou endereço de e-mail ou celular
    */
    public function validateRepeatedData($username, $email_address, $cell_phone)
    {
        try{

            $stmt = $this->prepare("
                SELECT 
                    (SELECT count(*) FROM " . $this->_table . " where username = :username) as repeated_username,
                    (SELECT count(*) FROM " . $this->_table . " where email_address = :email_address) as repeated_email_address,
                    (SELECT count(*) FROM " . $this->_table . " where cell_phone = :cell_phone) repeated_cell_phone
                FROM " . $this->_table . " limit 1
            ");

            $stmt->execute([
                'username'      => $username,
                'email_address' => $email_address,
                'cell_phone'    => $cell_phone
            ]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $results = current($results);

            $message = "";
            $message .= intval($results['repeated_username']) > 0 ? "Já existem registros utilizando este nome de usuário!<br>" : "";
            $message .= intval($results['repeated_email_address']) > 0 ? "Já existem registros utilizando este e-mail!<br>" : "";
            $message .= intval($results['repeated_cell_phone']) > 0 ? "Já existem registros utilizando este celular!<br>" : "";

            return [
                'status' => empty($message) ? 200 : 400,
                'message' => $message
            ];

        } catch (Exception $e) {
            return [ 'message' => "Erro ao consultar dados!", 'status' => 400 ];
        }
    }
}