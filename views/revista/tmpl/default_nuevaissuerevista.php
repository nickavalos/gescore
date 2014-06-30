<?php
/**
 * This file is part of the project Gestor de Congresos y Revistas en Joomla (GesCORE).
 *
 * @package		GesCORE backend 
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
defined('_JEXEC') or die('Restricted Access');

JHtml::_('behavior.tooltip');


$this->document = JFactory::getDocument();
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-1-9-1.js");
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-ui.js");

$this->document->addScript(DIR_SCRIPTS_GESCORE . 'chosen.jquery.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'utils.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'submenu.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'utilsRevista.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'funciones.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'revistaNewIssue.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'crossSelect.js');

$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'chosen.css');
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'utils.css');
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'crossSelect.css');
?>

<!-- <jdoc:include type="head" /> -->

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="/administrator/components/com_gestorcore/views/utils.js"></script> -->

<form action="<?php echo JRoute::_('index.php?option=com_gestorcore&controller=revista&task=infoRevista&id=' . $this->issnRevista); ?>" method="post" name="adminForm" id="form_gestionIssue">
    <?php echo JHtml::_('tabs.start','config-tabs-', array('useCookie'=>0)); 
        echo JHtml::_('tabs.panel', "Información general", 'infoTab');
            echo $this->loadTemplate('infogeneral'); 
        ?>
    
        <?php echo JHtml::_('tabs.panel', "Tópicos", 'topicosTab'); ?>
    
            <div class="topicosOptions">
                <div id="divTopicosFromRevista" class="divTopicosFromEntidad">
                    <select name="topicosFromRevista[]" class="crossSelectRevista" multiple>
                        <?php 
                        if (is_array($this->topicosFromRevista)):
                            foreach($this->topicosFromRevista as $topico): ?>
                                <option value="<?php echo $topico->nomTopico;?>">
                                    <?php echo $topico->nomTopico; ?> 
                                </option>
                            <?php endforeach;
                        endif;?>
                    </select>
                </div>

                <?php echo JHtml::_('sliders.start', 'PanelID', array('useCookie'=>0)); ?>
                    <?php echo JHtml::_('sliders.panel', "Otros Tópicos", ''); ?>
                        <div class="ui-widget" id="otrosTopicosWidget">
                            <br><input id="inputAddTopico" class="ui-corner-all" size="60" placeholder=" Criterio de búsqueda"/>
                            <input id="checkIncluirDescripcion" type="checkbox" checked> Incluir descripción de los tópicos en la búsqueda
                            <div id="topicosSeleccionados">
                                <br><br><p>Tópicos seleccionados:</p>
                                <select size="7" id="listTopicosSeleccionados"  multiple></select>
                                <select size="7" name="listTopicosSeleccionados[]" id="copy_listTopicosSeleccionados"  multiple hidden></select> 
                                <div class="topicosSeleccionados_deleteTopico">
                                    <button id="delete_topico" type="button">Eliminar seleccionados</button>                        
                                </div>
                            </div>
                        </div>
                    <?php echo JHtml::_('sliders.panel', "Nuevos Tópicos", ''); ?>
                        <?php echo $this->loadTemplate('nuevostopicos'); ?>

                <?php echo JHtml::_('sliders.end'); ?>
            </div>
    
        <?php echo JHtml::_('tabs.panel', "Valoraciones", 'valoracionTab'); ?>
            <p>Nota: Recuerde que una entidad de valoración no puede valorar más de una vez la misma edición.</p>
            <table class="adminlist" width="100%" id="tableValoraciones"> 	
                <thead>
                <tr>
                    <th width="60%"> Entidad de indice de valoración </th>
                    <th width="30%"> Valoración de la issue</th>
                    <th width="10%"> Eliminar?<br><span><input type="checkbox" id="checkBox_noPreg"/>Sin preguntar</span> </th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div class="div_addValoracion">
                <button id="buttonAddValoracion" type="button">Añadir nueva valoración</button>
            </div>
    <?php echo JHtml::_('tabs.end'); ?>

    
    <div>
        <input type="hidden" name="option" value="com_gestorcore" />
        <input type="hidden" name="task" value="" />
        <inupt type="hidden" name="controller" value="revista" />                
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>

<?php echo $this->loadTemplate('hiddenOptions'); ?>    

<div id="dialog-form_addRevista" title="Nueva Revista" class="dialog-form" hidden>
    <br>
    <div></div>
</div>
<div id="module-submenu">
    <?php echo GescoreHelper::getSubmenu(); ?>
</div>