<?php
/**
 * This file is part of the project Gestor de Congresos y Revistas en Joomla (GesCORE).
 *
 * @package		GesCORE frontend 
 * @subpackage  - 
 * @copyright   Copyright (C) 2014 Open Source Matters, Inc. All rights reserved.
 * @authors		Nick Avalos, Enric Mayol, Mª José Casany
 * @link		https://github.com/nickavalos/gescore
 * @license		License GNU General Public License
 *
 * GesCORE is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * GesCORE is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GesCORE. If not, see <http://www.gnu.org/licenses/>.
 */
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
    //instanciamos el controlador principal que tiene la vista por defecto
    $controller = JController::getInstance('gestorcore');
    $controller->execute(JRequest::getVar('task'));
}


$controller->redirect();


?>