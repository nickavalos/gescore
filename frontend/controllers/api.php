<?php
/**
 * This file is part of the project Gestor de Congresos y Revistas en Joomla (GesCORE).
 *
 * @package		GesCORE frontend 
 * @subpackage  Controllers 
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
jimport('joomla.application.component.controlleradmin');

//jimport( 'joomla.html.html.jgrid' );
 
class GestorcoreControllerApi extends JControllerAdmin {
    var $model_api = 'api';
    
    var $default_type = '*';
    var $default_sort = 'title';
    var $default_sortDirection = 'ASC';
    var $default_numResults = 50;
    var $default_offset = 0;
    var $default_searchInTopics = "true";
    
    /*Restricciones*/
    var $max_numResults = 50;
    
    function search() {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        $model = $this->getModel($this->model_api);
        
        $objAPI = new stdClass();
        $objAPI->filter = JRequest::getVar('search');
        $objAPI->type = JRequest::getVar('type', $this->default_type);
        $objAPI->sort = JRequest::getVar('sort', $this->default_sort);
        $objAPI->sortDirection = JRequest::getVar('sortDirection', $this->default_sortDirection);
        $objAPI->numResults = JRequest::getInt('numResults', $this->default_numResults);
        $objAPI->offset = JRequest::getInt('offset', $this->default_offset);
        $objAPI->searchInTopics = JRequest::getVar('searchInTopics', $this->default_searchInTopics) == "true";
        
        $editionObj = new stdClass();
        $editionObj->place = JRequest::getVar('place', '');
        $editionObj->limReception_min = JRequest::getVar('limReception_min', '');
        $editionObj->limReception_max = JRequest::getVar('limReception_max', '');
        $editionObj->definitiveDate_min = JRequest::getVar('definitiveDate_min', '');
        $editionObj->definitiveDate_max = JRequest::getVar('definitiveDate_max', '');
        $editionObj->startDate_min = JRequest::getVar('startDate_min', '');
        $editionObj->startDate_max = JRequest::getVar('startDate_max', '');
        $editionObj->endDate_min = JRequest::getVar('endDate_min', '');
        $editionObj->endDate_max = JRequest::getVar('endDate_max', '');
        
        $issueObj = new stdClass();
        $issueObj->limReception_min = JRequest::getVar('limReception_min', '');
        $issueObj->limReception_max = JRequest::getVar('limReception_max', '');
        $issueObj->definitiveDate_min = JRequest::getVar('definitiveDate_min', '');
        $issueObj->definitiveDate_max = JRequest::getVar('definitiveDate_max', '');
        $issueObj->publicationDate_min = JRequest::getVar('publicationDate_min', '');
        $issueObj->publicationDate_max = JRequest::getVar('publicationDate_max', '');
        
        /*Tratmiento de restricciones*/
        if($objAPI->numResults > $this->max_numResults) {
            $objAPI->numResults = $this->max_numResults;
        }
        /*Llama callback al cliente*/
        $callback = JRequest::getVar('callback');
        
        /*Obtenemos los resultados del Modelo*/
        $resultsQuery = $model->searchAPI($objAPI, $editionObj, $issueObj);
        
        /*Preparamos la respuesta del servidor*/
        $response = new stdClass();
        
        if ($objAPI->filter == "") { //query vacia
            //$response->WARNING[] = "input empty";
        }
        
        if (is_null($resultsQuery)) {
            $response->status = 400;
            $response->ERROR[] = "Query Failed. Check the syntax";
        } else {
            $definitiveResults = [];
            for ($i = $objAPI->offset; $i < count($resultsQuery) && $i < ($objAPI->offset + $objAPI->numResults); $i++) {
                $definitiveResults[] = $resultsQuery[$i];
            }
            $response->status = 200;    
            $response->OK = new stdClass();
            $response->OK->position = $objAPI->offset;
            $response->OK->returnedResults = count($definitiveResults);
            $response->OK->totalResults = count($resultsQuery);
            $response->OK->results = $definitiveResults;  //cargamos los resultamos        
        }
        
        if($callback != '') {
            echo "$callback(" . json_encode($response) . ")";
        } else {
            echo json_encode($response);
        }
    }

    function display($cachable = false, $urlparams= false)
    {	
        parent::display();
    }
    
}

?>