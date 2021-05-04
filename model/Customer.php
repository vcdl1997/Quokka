<?php

require_once 'ModelTrait.php';

class Customer extends ModelTrait {

    public $_table = 'customers';


    /*
        Lista os registros
    */
    public function list($filters){
        $search = $filters['search']['value'];
        $columns = [ 'fullname', 'surname', 'cell_phone', 'email_address' ];

        $query = "SELECT * FROM " . $this->_table . " WHERE 1=1";
        if( !empty($search) ) {
            $query.=" AND ( fullname LIKE '".$search."%' ";    
            $query.=" OR surname LIKE '".$search."%' )";
            $query.=" OR cell_phone LIKE '".$search."%' ";
            $query.=" OR email_address LIKE '".$search."%' ";
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
                'id'                    => intval($id),
                'fullname'              => $fullname,
                'surname'               => $surname,
                'home_phone'            => $home_phone,
                'cell_phone'            => $cell_phone,
                'contact_for_message'   => $contact_for_message,
                'email_address'         => $email_address,
                'full_address'          => $full_address,
                'birth_date'            => $birth_date
            ];

            if(!empty($id)){

                $validation = $this->whatHasChanged(intval($id), $parameters);
                if($validation['status'] == 400) return $validation;

                $query = "
                    UPDATE " . $this->_table . " 
                    SET 
                        fullname              = :fullname,
                        surname               = :surname,
                        home_phone            = :home_phone,
                        cell_phone            = :cell_phone,
                        contact_for_message   = :contact_for_message,
                        email_address         = :email_address,
                        full_address          = :full_address,
                        birth_date            = :birth_date
                    WHERE
                        id = :id
                ";  

                $stmt = $this->prepare($query);
                $stmt->execute($parameters); 
                $message = 'atualizado';

            }else{

                unset($parameters['id']);

                $validation = $this->validateRepeatedData($cell_phone);
                if($validation['status'] == 400) return $validation;

                $query = "
                    INSERT INTO " . $this->_table . " (fullname, surname, home_phone, cell_phone, contact_for_message, email_address, full_address, birth_date) 
                    VALUES (:fullname, :surname, :home_phone, :cell_phone, :contact_for_message, :email_address, :full_address, :birth_date)
                ";  
                $stmt = $this->prepare($query);
                $stmt->execute($parameters); 

                $id = $this->lastInsertID();
                $message = 'cadastrado';

            }

            return [ 'id' => $id, 'message' => "Cliente {$message}!", 'status' => 200 ];

        } catch (Exception $e) {

            die(var_dump($e->getMessage()));

            return [ 'message' => "Erro ao atualizar!", 'status' => 400 ];
        }
    }
    
    /*
        Exclui o Cliente
    */
    public function delete($id)
    {
        try{
            $this->deleteRegister($id);

            return [ 'id' => $id, 'message' => "Cliente excluido com sucesso!", 'status' => 200 ];

        } catch (Exception $e) {
            // die(var_dump($e->getMessage()));
            return [ 'message' => "Erro ao excluir Cliente!", 'status' => 400 ];
        }
    }

    /*
        Verifica o que mudou em comparação ao registro original,
        se houver ao menos uma mudança nos seguintes campos: (e-mail e celular) 
        é executado o metodo validateRepeatedData();
    */
    public function whatHasChanged(int $id, array $dataForm){
        $dataBD = current($this->findByID($id));

        if($dataBD['cell_phone'] == $dataForm['cell_phone']){
            return [ 
                'status' => 200, 
                'message' => "" 
            ];
        }

        return $this->validateRepeatedData($dataForm['cell_phone']);
    }

    /*
        Verifica o que mudou em comparação ao registro original,
        se houver ao menos uma mudança nos seguintes campos: (e-mail e celular) 
        é executado o metodo validateRepeatedData();
    */
    public function validateRepeatedData($cell_phone)
    {
        try{

            $stmt = $this->prepare("
                SELECT 
                    (SELECT count(*) FROM " . $this->_table . " where cell_phone = :cell_phone) repeated_cell_phone
                FROM users limit 1
            ");

            $stmt->execute([
                'cell_phone'    => $cell_phone
            ]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $results = current($results);

            $message = "";
            $message .= intval($results['repeated_cell_phone']) > 0 ? "Já existem registros utilizando este celular!<br>" : "";

            return [
                'status' => empty($message) ? 200 : 400,
                'message' => $message
            ];

        } catch (Exception $e) {
            return [ 'message' => "Erro ao consultar dados!", 'status' => 400 ];
        }
    }


    /*
        Verifica os aniversariantes do mês
    */
    public function fetchBirthdaysMonth($onlyTotal = false)
    {
        try{
            $columns = $onlyTotal ? "count(*) as total" : "fullname, cell_phone, email_address";

            $stmt = $this->prepare("
                SELECT {$columns}
                FROM " . $this->_table . "
                WHERE DAYOFMONTH(birth_date) = :day
                AND MONTH(birth_date) = :month
            ");
            $stmt->execute([
                'day'       => date('d'),
                'month'     => date('m')
            ]);
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $total = $onlyTotal ? current($results)['total'] : count($results);
            $message = $total > 0 ? 'Aniversariante encontrados' : 'Nenhum aniversariante encontrado';

            return [ 
                'status'    => 200,
                'message'   => $message,
                'data'      => $results,
                'total'     => $total
            ];

        } catch (Exception $e) {
            return [ 
                'status'    => 400,
                'message'   => 'Erro ao buscar aniversariantes!',
                'data'      => [],
                'total'     => 0
            ];
        }
    }
}