<?php
class Models extends Entidad{
    private $table;
    private $fluent;
    private $query;


    public function __construct($table) {
        $this->table=(string) $table;
        parent::__construct($table);
        //$this->fluent=$this->getConetar()->startFluent();
    }
     
    public function fluent(){
        return $this->fluent;
    }
    
    public function EjecutarSQL($q){
        $this->getConetar();
        $this->query=  $this->prepare($q);
        $this->query->execute();
        if($this->query==TRUE){
           if($this->query->rowCount()>1){
               while ($row=$this->query->fetchObject()) {
                  $resultSet[]=$row; 
               } 
           }elseif ($this->query->rowCount()==1) {
               if($row=$this->query->fetchObject()) {
                  $resultSet[]=$row; 
               } 
                
            }else{
                $resultSet=TRUE;
            }
        }else{
            $resultSet=FALSE;
        }
        return $resultSet;
    }
    
    /*public function ejecutarSql($query){
        $query=$this->
        if($query==true){
            if($query->num_rows>1){
                while($row = $query->fetch_object()) {
                   $resultSet[]=$row;
                }
            }elseif($query->num_rows==1){
                if($row = $query->fetch_object()) {
                    $resultSet=$row;
                }
            }else{
                $resultSet=true;
            }
        }else{
            $resultSet=false;
        }
         
        return $resultSet;
    }*/
     
    //Aqui podemos montarnos métodos para los modelos de consulta
     
}
?>
