<?php
/**
 * This file is part of the project Gestor de Congresos y Revistas en Joomla (GesCORE).
 *
 * @package		GesCORE frontend 
 * @subpackage  Views 
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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

$this->document = JFactory::getDocument();

//$this->document->addScript('http://localhost/gestorRevistasCongresos16/components/com_gestorcore/api.js');
$this->document->addScript(JURI::root(true). '/components/com_gestorcore/api.js');
//$this->document->addScript(JURI::root(true). '/components/com_gestorcore/gescoreAPI.jsp');

$this->document->addStyleSheet(JURI::root(true). '/components/com_gestorcore/gescoreAPI_hilight.css');

?>
<script type="text/javascript">
    callback = function() { 
        document.gescoreForm.searchButton.disabled = false;
        
        //alert(gescore.getTotalHits());
    };
    
    runSearch = function(){
        document.gescoreForm.searchButton.disabled = true;
        var varSearchObj = new searchObj();
        varSearchObj.setSearch(document.gescoreForm.searchString.value);
        //varSearchObj.setNumResults(50);
        varSearchObj.setOffset(0);
        varSearchObj.setSearchInTopics(true);
        varSearchObj.setSort("fecha");
        varSearchObj.setSortDirection("DESC");
        
        var edition = new editionObj();
        edition.setPlace('Barcelona');
        edition.setLimReception_min('06/06/2013');
        edition.setEndDate_max('18/06/2014');
        varSearchObj.setObjectType(edition);
        
        gescore.searchAndPrint(varSearchObj);
    };
    
    //debug.setDebug(true);
    gescore.setCallback(callback);
</script>

<h1>
    <?php echo "Probando gescoreAPI"; ?>
</h1>

<form action="" method="post" name="gescoreForm">
    <div id="gescoreDebugArea"></div>
    <input type="text" name="searchString"/><button onClick="runSearch()" name="searchButton"/>Search</button>
    <br/><div id="gescore_output"></div>
</form>


