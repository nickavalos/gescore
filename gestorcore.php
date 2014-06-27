<?php

defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once( JPATH_COMPONENT.DS.'controller.php' );

/*  Definicion de nombres de tablas */
define('TABLE_EDITORIAL', '#__editorial');
define('TABLE_CONGRESO', '#__congreso'); 
define('TABLE_EDICION', '#__edicion');
define('TABLE_REVISTA', '#__revista');
define('TABLE_ISSUE', '#__issue');
define('TABLE_TOPICO', '#__topico');
define('TABLE_ENTIDAD_INDICE_CALIDAD', '#__entidadIndiceCalidad');
define('TABLE_CATEGORIA', '#__categoria');
define('TABLE_CONGRESO_TOPICO', '#__congresoTopico');
define('TABLE_EDICION_TOPICO', '#__edicionTopico');
define('TABLE_REVISTA_TOPICO', '#__revistaTopico');
define('TABLE_ISSUE_TOPICO', '#__issueTopico');
define('TABLE_TOPICO_SIMILAR', '#__topicoSimilar');
define('TABLE_ENTIDAD_CATEGORIA', '#__entidadCategoria');
define('TABLE_VALORACION_EDICION', '#__valoracionEdicion');
define('TABLE_VALORACION_ISSUE', '#__valoracionIssue');

/*Definicion de variables importantes*/
define('FORMAT_NO_DATE', '1970-01-01');
define('ID_EDICION_ESPECIAL', 'specialIssue');

/*Definición de archivos*/
//define('DIR_SCRIPTS_GESCORE', Juri::base() .  "components/com_gestorcore/scripts/");
//define('DIR_STYLESHEETS_GESCORE', Juri::base() . "components/com_gestorcore/stylesheets/");

define('DIR_SCRIPTS_GESCORE', JURI::root(true). "/administrator/components/com_gestorcore/scripts/");
define('DIR_STYLESHEETS_GESCORE', JURI::root(true). "/administrator/components/com_gestorcore/stylesheets/");

/*Definicion de tipo de categorias*/
define('GESCORE_TIPO_CATEGORIA_LISTA', 'lista');
define('GESCORE_TIPO_CATEGORIA_NUMERICO', 'numerico');

if ($controller = JRequest::getWord('controller')) { // controlador espefificado en la url
    $path = JPATH_COMPONENT . DS . 'controllers' . DS . $controller . '.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
    $task = Jrequest::getCmd('task');
    if (!$task) {
        //$task por defecto para cada controlador
        switch($controller) {
            case 'revista':
                $task = 'listRevistas';
                break;
            case 'congreso':
                $task = 'listCongresos';
                break;
        }
    }
    $classname    = 'GestorcoreController'.$controller;
    //$controller   = new $classname( );
    $controller   = new $classname();
    $controller->execute($task);
    

} else { //no especificamos controlador
    //$controller = "congreso";
    //$controller = "revista";
    //$controller = "gescore";
    //require_once( JPATH_COMPONENT . DS . 'controllers' . DS . $controller . '.php');
    
    //instanciamos el controlador principal que tiene la vista por defecto
    $controller = JController::getInstance('gestorcore');
    $controller->execute(JRequest::getVar('task'));
}


//jimport('joomla.application.component.controller');
//$controller = JController::getInstance(JPATH_COMPONENT.DS.'controllers'.DS.'gestorcore');
/*
$classname    = 'GestorcoreController'.$controller;
//$controller   = new $classname( );
$controller   = new $classname( array( 'default_task' => 'gescore' ) );

$task = Jrequest::getCmd('task');

if (!$task) {
    //$task = "listCongresos";
    $task = "listRevistas";
}

$controller->execute(JRequest::getCmd('task') );
//$controller->execute($task);


$controller->redirect();
*/

$controller->redirect();


?>