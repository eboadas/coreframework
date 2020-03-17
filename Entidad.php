<?php
    class Entidad{
        private $table;
        private $db;
        public $con;
        private $numrows= null;
        private $manejador;

        public function __construct($table) {
            $this->table=(string) $table;
           include_once 'Conectar.php';
            //parent::__construct();
            $con=  new Conectar();
            $this->con=$con;//$this->setConexion($con);
            //$this->db=$this->con->ConectarBD();
        }
        /**
         * Permite obtener datos todos los registros de una consulta mediante un arreglo asociativo o de 
         * objetos, concatenando las columnas y las tablas.
         **/ 
        public function GetAllRecord ($columnas, $tabla, $getObjects=true) {
            $result=null;
            $col=null;
            try {
                 /* Extraemos las columnas para preparar la consulta*/
                 if(isset($columnas)){
                    foreach ($columnas as $key) {
                        if (!empty($key)){
                            $col.=$key.",";
                        } 
                    }
                    $sql=" SELECT ".substr($col, 0, strlen($col)-1)." FROM ".$tabla;
                }else{
                    $sql=" SELECT * FROM " . $tabla;
                }
                 $query=  $this->con->prepare($sql);
                 $query->execute();
                 $this->setNumRows( $query->rowCount());
                 if($getObjects){
                     $result=$query->fetchAll(PDO::FETCH_ASSOC);
                 }  else {
                     $result=$query->fetchAll(PDO::FETCH_OBJ);
                 }
            } catch (PDOException $exc) {
                error_log($exc->getMessage());
                $this->setMerrors($exc->getMessage());
                
            }  
            return $result;
        }

        /**
         * Permite obtener datos todos los registros de una consulta mediante un arreglo asociativo o de 
         * objetos, concatenando las columnas y las tablas.
         **/ 
        public function ExcecuteSQL ($q, $getObjects=false) {
            $result=null;
            try {
                $query= $this->con->prepare($q);
                 $query->execute();
                 $this->setNumRows( $query->rowCount());
                 if($getObjects){
                     $result=$query->fetchAll(PDO::FETCH_ASSOC);
                 }  else {
                     $result=$query->fetchAll(PDO::FETCH_OBJ);
                 }
            } catch (PDOException $exc) {
                error_log($exc->getMessage());
                $this->setMerrors($exc->getMessage());  
            }  
            return $result;
        }
        
        /**
         * Permite obtener datos de un registro de una consulta mediante un arreglo asociativo o de 
         * objetos, concatenando las columnas y las tablas.
         **/ 
         public function GetRecordById($columnas, $tabla, $condicion, $valores,$getObjects=true){
            $sql=null;
            $col=null;
            $auxval=null;
            $auxcol=null;
             
             try {
                 /* Extraemos las columnas para preparar la consulta*/
                if(isset($columnas)){
                    foreach ($columnas as $key) {
                        if (!empty($key)){
                            $col.=$key.",";
                        } 
                    }
                    $sql=" SELECT ".substr($col, 0, strlen($col)-1)." FROM " . $tabla . " WHERE " . $condicion;
                }else{
                    $sql=" SELECT * FROM " . $tabla . " WHERE " . $condicion;
                }
                
                 $query=  $this->con->prepare($sql);
                  $query->execute($valores);
                  $this->setNumRows($query->rowCount());
                  if($getObjects){
                    $result=$query->fetchAll(PDO::FETCH_ASSOC);
                  } else {
                    $result=$query->fetchAll(PDO::FETCH_OBJ);
                  }
                 
             } catch (PDOException $exc) {
                 error_log($exc->getMessage());
                 $this->setMerrors($exc->getMessage());
                
             }
             return $result;
        } 
        
        
        public function GetJoin($columnas, $tabla1,$tabla2,$t1id,$t2id){
             
             $result;       
             try {
                // echo " SELECT " . $columnas . " FROM " . $tabla1 . " INNER JOIN " .$tabla2." ON ".$tabla1.".".$t1id."=".$tabla2.".".$t2id;
                // exit();
                 $query=  $this->con->prepare(" SELECT " . $columnas . " FROM " . $tabla1 . " INNER JOIN " .$tabla2." ON ".$tabla1.".".$t1id."=".$tabla2.".".$t2id);
                
                /* foreach ($valores as $key => $value) {
                     if (!empty($value)){
                         $query->bindParam( $key, $value, $this->getPDOConstantType( $value ));
                     }
                 }*/
                  $query->execute();
                  
                  $this->setNumRows($query->rowCount());
                  $result = $query->fetchAll(PDO::FETCH_ASSOC);
             } catch (PDOException $exc) {
                 error_log($exc->getMessage());
                 $this->setMerrors($exc->getMessage());
                
             }
             return $result;
        } 
        
       // SELECT *FROM empresa INNER JOIN usuario on empresa.ruc= usuario.id_emp
        
        /**
        * Guarda valores en la tabla que se desee, concatenando las columnas, tabla, campos y los valores que serán utilizados.
        **/
        public function AddRecord($tabla, $valores){
            
           // $result=null;
            $val=null;
            $col=null;
            $auxval=null;
            $auxcol=null;
            try {
               
                /* Extraemos las columnas para preparar la consulta*/
                foreach ($valores as $key => $value) {
                    if (!empty($value)){
                        $auxval.=$key.",";
                        $auxcol.=substr($key, 1, strlen($key)).",";
                    }
                   
                }
                $val=substr($auxval, 0, strlen($auxval)-1);
                $col=substr($auxcol, 0, strlen($auxcol)-1);
                
                $query = $this->con->prepare( "INSERT INTO ".$tabla." (".$col.") VALUES (".$val.")" );
                $query->execute($valores);
                $this->setNumRows($query->rowCount());
                  
            } catch (PDOException $exc) {
                
                error_log($exc->getMessage());
                //$this->setMerrors($exc->getMessage());
                
            }
            
        }
        
        /**
        * Modifica valores en la tabla que se desee, concatenando la tabla, campos, valores y la condicion que sera utilizada para ejecutar esta consulta.
        **/
        public function UpdateRecord($tabla, $valores, $condicion) {
            $val=null;
            $col=null;
            $auxval=null;
            $auxcol=null;
            
            try {
                 /* Extraemos las columnas para preparar la consulta*/
                 foreach ($valores as $key => $value) {
                    if (!empty($value)){
                        $auxcol.=substr($key, 1, strlen($key))."=".$key.",";
                    }
                   
                }
                $col=substr($auxcol, 0, strlen($auxcol)-1);
                //exit();
                $query = $this->con->prepare( "UPDATE ".$tabla." SET ".$col." WHERE ".$condicion );
                $query->execute($valores);
                $this->setNumRows($query->rowCount());
                 
            } catch (PDOException $exc) {
                error_log($exc->getMessage());
                $this->setMerrors($exc->getMessage());
                
            }
         
        }
        
        /**
        * Modifica valores en la tabla que se desee, concatenando la tabla, campos, valores y la condicion que sera utilizada para ejecutar esta consulta.
        **/
        public function ExProcedure($ProcedureName, $valores) {
            $auxcol=null;
            $result=null;
            try {
                 /* Extraemos las columnas para preparar la consulta*/
                 foreach ($valores as $key => $value) {
                    if (!empty($value)){
                        $auxcol.=$key.",";
                    }
                   
                }
                $col=substr($auxcol, 0, strlen($auxcol)-1);
                $query = $this->con->prepare( "CALL ".$ProcedureName."(".$col.")" );
                $retVal = ($query->execute($valores)) ? true : false ;
                return $retVal;
                
            } catch (PDOException $exc) {
                error_log($exc->getMessage());
                $this->setMerrors($exc->getMessage());  
            }
            
        }
       
        public function getConexion() {
            return $this->con;
        }
        
        /**
        * Guarda la cantidad de filas afectadas en una consulta. 
        **/
       private function setNumRows( $rows )
        {
            $this->numrows = $rows;
        }
        /**
        * Devuelve la cantidad de filas afectadas en una consulta. 
        **/
       public function getNumRows(){
            return $this->numrows;
       }
       
       private function getPDOConstantType( $var ){
            if(is_int($var)) return PDO::PARAM_INT;
            if(is_string($var)) return PDO::PARAM_STR;
            if(is_bool($var)) return PDO::PARAM_BOOL;
            if(is_null($var))return PDO::PARAM_NULL;
            
       }

       
    };
 ?>