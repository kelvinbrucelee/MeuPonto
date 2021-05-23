<?php

class BatidaRecord extends TRecord{
	const TABLENAME = 'batida';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'serial'; // {max, serial}
    
     private $servidor;

     function get_nome_servidor(){
     if(empty ($this->servidor)){

         $this->servidor = new ServidorRecord($this->matricula);
         }


         return $this->servidor->nome;
        
     }
    
}
?>