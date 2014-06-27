<?php
/** @cbertelegni
 * Esta clase imprime en la consola Javascript del browser 
 * Tipos de log:
 * log
 * debug
 * info
 * warn
 * error
 * trace
 * group
 * dir
 */
class ConsoleLog {
    public $mensaje;
    public $loge;

    public function __construct($typeLog, $msj) {
       $this->loge= $typeLog;
       $this->mensaje= $msj;
       $this->consolePrint();
    }

    public function consolePrint()
    {
       $script= '<script type="text/javascript">';
       $script .= 'console.'.$this->loge.'("PHP mensaje: '.$this->mensaje.'")';
       $script .= '</script>';
       echo $script; 
    }
}

// ejemplo:
//$mesj= new ConsoleLog("log","este es el mensaje");

?>