<?php

abstract class ConectionDB{
    protected $instance; 

	protected function getInstance(){ 

		if(!isset($this->instance)){

			try{ //Tente
				$this->instance = new PDO("mysql:host=localhost;dbname=quokka", 'root', 'root');
				$this->instance->setAttribute(PDO::ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);
				$this->instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

			}catch(PDOException $e){
				echo $e->getMessage();
			}

		}
		return $this->instance;
	}

	protected function prepare($sql){
		return $this->getInstance()->prepare($sql);
	}
}