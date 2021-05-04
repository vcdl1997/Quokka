<?php

require_once 'ModelTrait.php';

class Schedule extends ModelTrait {

    public $_table;
    public $_modelSchedulingWages;

    function __construct()
    {
        $this->_table = 'schedules';
        $this->_modelSchedulingWages = new SchedulingWages();
    }

    public function list(){

        $query = "
            select 
            sw.id as expiration_id,
            sw.sequence, 
            (select count(*) from scheduling_wages sw2 where sw2.id_schedule = s.id ) as total, 
            billing_date as start,
            DATE_FORMAT(billing_date,'%d/%m/%Y') formatted_date,
            s.id_service,
            concat(
                (
                    CASE WHEN s.id_service = 1 THEN 'Venda'
                    WHEN s.id_service = 2 THEN 'Pagamento de Contas'
                    END
                ),
                ' - ', 
                (
                    CASE WHEN s.id_service = 1 THEN 'Cliente: '
                    WHEN s.id_service = 2 THEN 'Fornecedor: '
                    END
                ),
                c.fullname
            ) as title,
            (
                CASE WHEN s.id_service = 2 THEN 
                    'red'
                WHEN s.id_service = 1 THEN 
                    CASE WHEN sw.status = true THEN 'green' ELSE 'yellow' END
                END
            ) as color,
            c.fullname,
            s.description
            from scheduling_wages sw
            inner join ".$this->_table." s on s.id = sw.id_schedule
            inner join customers c on c.id = s.id_customer
            order by start
        ";

        $stmt = $this->prepare($query);
        $stmt->execute(); 

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
        Cadastra um novo agendamento
    */
    public function create($filters){
        try{
            extract($filters);

            $parameters = [
                'id_service'        => $id_service,
                'realization_date'  => $realization_date,
                'value'             => floatval($value),
                'id_customer'       => intval($id_customer),
                'id_user'           => intval($id_user),
                'id_form_payment'   => intval($id_form_payment),
                'plots'             => intval($plots),
                'maturities'        => intval($maturities),
                'description'       => $description
            ];

            $query = "
                INSERT INTO " . $this->_table . " (id_service, realization_date, value, id_customer, id_user, id_form_payment, plots, maturities, description) 
                VALUES (:id_service, :realization_date, :value, :id_customer, :id_user, :id_form_payment, :plots, :maturities, :description)
            ";  
            $stmt = $this->prepare($query);
            $stmt->execute($parameters); 
            $id = $this->lastInsertID();

            if(!$this->_modelSchedulingWages->create($id, $parameters['value'], $parameters['plots'], $parameters['maturities'], $parameters['realization_date'])) {
                return [ 'message' => "Erro ao realizar agendamento!", 'status' => 400 ];
            }

            return [ 'message' => "Agendamento cadastrado!", 'status' => 200 ];

        } catch (Exception $e) {
            // die(var_dump($e->getMessage()));
            return [ 'message' => "Erro ao realizar agendamento!", 'status' => 400 ];
        }
    }

    /*
        Exclui o agendamento
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
}