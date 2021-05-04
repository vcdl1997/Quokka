<?php

require_once 'ModelTrait.php';

class SchedulingWages extends ModelTrait {
   
    public $_table = 'scheduling_wages';

    public function receivable(){
        $query = "
            select coalesce(round(sum(sw.value),2), 0) as receivable 
            from ".$this->_table." sw
            inner join schedules s on s.id = sw.id_schedule
            where status = false
            and s.id_service = 1
        ";
        $stmt = $this->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return number_format(current($result)['receivable'], 2, ',', '.');
    }

    public function payable(){
        $query = "
            select coalesce(round(sum(sw.value),2), 0) as payable 
            from ".$this->_table." sw
            inner join schedules s on s.id = sw.id_schedule
            where status = false
            and s.id_service = 2
        ";
        $stmt = $this->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return number_format(current($result)['payable'], 2, ',', '.');
    }
   
    public function create($id_schedule, $value, $plots, $maturities, $realization_date){
        try{
           
            $message = "";

            $installmentValue = number_format($value / $plots, 2, '.', '');
            
            $billingDate = date($realization_date);

            $query = "INSERT INTO " . $this->_table . " (id_schedule, sequence, value, billing_date, status) VALUES ";
        
            for($i = 1; $i <= $plots; $i++){
        
                if($i == $plots){
                    $addedPlots = floatval($installmentValue) * $plots;
                    if($value > floatval($addedPlots)) $installmentValue += $value-floatval($addedPlots);
                    else $installmentValue -= floatval($addedPlots)-$value;
                }
        
                $date = new DateTime($billingDate);
                $date->modify("+{$maturities} day");
                $billingDate = $date->format('Y-m-d');
                $comma = $i < $plots ? ',' : '';

                $query .= "({$id_schedule}, {$i}, {$installmentValue}, '{$billingDate}', false){$comma}";
            }

            $stmt = $this->prepare($query);
            $stmt->execute();

            return true;

        } catch (Exception $e) {
            // die(var_dump($e->getMessage()));
            return false;
        }
    }


    public function confirm($id)
    {
        try{
            $stmt = $this->prepare("UPDATE " . $this->_table . " SET status = true WHERE id = :id");
            $stmt->execute(['id' => intval($id)]);

            return [ 'id' => $id, 'message' => "Salvo com sucesso!", 'status' => 200 ];
        } catch (Exception $e) {
            // die(var_dump($e->getMessage()));
            return [ 'id' => $id, 'message' => "Erro ao salvar!", 'status' => 400 ];
        }
    }

}