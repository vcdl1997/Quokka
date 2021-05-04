<?php
    require_once 'ConectionDB.php';

    abstract class ModelTrait extends ConectionDB{

        /*
            Busca o registro pelo ID
        */
        public function findByID($id){

            $stmt = $this->prepare("SELECT * FROM " . $this->_table . " users WHERE id=:id");
            $parameters = ['id' => $id ];
            $stmt->execute($parameters); 

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /*
            Retorna o id do último registro
        */
        public function lastInsertID()
        {
            $stmt = $this->prepare("SELECT MAX(id) AS id FROM " . $this->_table);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return intval(current($results)['id']);
        }

        /*
            Exclui o usuário
        */
        public function deleteRegister($id)
        {
            $stmt = $this->prepare("DELETE FROM  " . $this->_table . "  WHERE id = :id");
            $stmt->execute(['id' => intval($id)]);
        }


        /*
            Monta as options do select de acordo com a Model
        */
        public function buildSelectOptions(array $columns)
        {
            $implodedColumns = implode(",", $columns);

            $stmt = $this->prepare("SELECT {$implodedColumns} FROM " . $this->_table);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $html = "";

            if(count($results) > 0){
                foreach($results as $result){   
                    $html .= "<option value='".$result[$columns[0]]."'>".$result[$columns[1]]."</option>";
                }
            }

            return $html;
        }
    }
