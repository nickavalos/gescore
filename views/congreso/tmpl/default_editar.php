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
//JHtml::_('behavior.formvalidation');
//JHtml::_('behavior.keepalive');

//JHTML::script("jquery-1.9.1.js", "http://code.jquery.com/");
//JHTML::script("jquery-ui.js", "http://code.jquery.com/ui/1.10.3/");
//JHTML::script("validacion.js", "http://pacific-island-4090.herokuapp.com/scripts/");

$this->document = JFactory::getDocument();
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-1-9-1.js");
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-ui.js");

$this->document->addScript(DIR_SCRIPTS_GESCORE . 'chosen.jquery.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'utils.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'submenu.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'funciones.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'utilsCongreso.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'congresoEditEdicion.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'crossSelect.js');
//$this->document->addScript('validacion.js', "/administrator/components/com_gestorcore/scripts/");

$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'chosen.css');
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'utils.css');
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'crossSelect.css');

$this->topicosFromCongreso = $this->edicion->topicosFromCongreso_current;
$this->numeroEdicionMostrada = $this->edicion->orden;

?>

<!-- <jdoc:include type="head" /> -->

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">


<script type="text/javascript">
        /*
	Joomla.submitbutton = function(task) {
		if (task === 'article.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			<?php //echo $this->form->getField('articletext')->save(); ?>
			Joomla.submitform(task, document.getElementById('item-form'));
		} else {
			alert('<?php //echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}*/
</script>

<form action="<?php echo JRoute::_($this->uriPOST); ?>" method="post" name="adminForm" id="form_gestionEdicion" >
    <?php echo JHtml::_('tabs.start','config-tabs-', array('useCookie'=>0)); 
        echo JHtml::_('tabs.panel', "Información general", 'infoTab');
            echo $this->loadTemplate('infogeneral');
        ?>
            
        <?php echo JHtml::_('tabs.panel', "Tópicos", 'topicosTab'); ?>
            <div class="topicosOptions">
                <div class="divTopicosFromEntidad">
                    <select name="topicosFromCongreso[]" class="crossSelect" multiple>
                        <?php 
                        if (is_array($this->topicosFromCongreso)):
                            foreach($this->topicosFromCongreso as $topico): 
                                $trobat = false;
                                    foreach ($this->edicion->topicos as $key => $topicoEdicion):
                                        if ($topicoEdicion->nomTopico == $topico->nomTopico) {
                                            unset($this->edicion->topicos[$key]);
                                            $trobat=true;
                                        }
                                    endforeach;
                                    if ($trobat):
                                    ?>
                                        <option value="<?php echo $topico->nomTopico;?>" selected>
                                    <?php else: ?>
                                        <option value="<?php echo $topico->nomTopico;?>">
                                    <?php endif;?>    

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
                                <select size="7" id="listTopicosSeleccionados" multiple>
                                    <?php foreach ($this->edicion->topicos as $key => $topicoEdicion):?>
                                        <option value="<?php echo $topicoEdicion->nomTopico;?>"><?php echo $topicoEdicion->nomTopico;?></option>
                                        <?php endforeach; ?>
                                </select>
                                <select size="7" name="listTopicosSeleccionados[]" id="copy_listTopicosSeleccionados" multiple hidden>
                                    <?php foreach ($this->edicion->topicos as $key => $topicoEdicion):?>
                                        <option value="<?php echo $topicoEdicion->nomTopico;?>" selected><?php echo $topicoEdicion->nomTopico;?></option>
                                        <?php endforeach; ?>
                                </select> 
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
                        <th width="30%"> Valoración de la edición</th>
                        <th width="10%"> Eliminar?<br><span><input type="checkbox" id="checkBox_noPreg"/>Sin preguntar</span> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($this->edicion->valoraciones as $i=>$valoracion): ?>
                        <tr class="row0" >
                            <td> 
                                <select class="selectorEntidadCalidad chosen"  name="selectorEntidadCalidad[]" style="width: 72%;">
                                    <?php foreach ($this->entidadesCalidad as $entidadCalidad): 
                                        if ($entidadCalidad->nombre == $valoracion->nomEntidad): ?>
                                            <option value="<?php echo $entidadCalidad->nombre;?>" selected> <?php echo $entidadCalidad->nombre;?> </option>
                                        <?php else:?>
                                            <option value="<?php echo $entidadCalidad->nombre;?>"> <?php echo $entidadCalidad->nombre;?> </option>
                                        <?php endif;?>
                                    <?php endforeach;?>    
                                </select>
                            </td>
                            <td class="selectorCategoriaCalidad">
                                <?php if ($valoracion->tipoCategoria == GESCORE_TIPO_CATEGORIA_LISTA): ?>
                                    <select name="selectorCategoriaCalidad[]">
                                        <?php foreach($valoracion->categorias as $categoria):
                                            if ($categoria->categoria == $valoracion->indice): ?>
                                                <option value="<?php echo $categoria->categoria;?>" selected> <?php echo $categoria->categoria;?> </option>
                                            <?php else:?>
                                                <option value="<?php echo $categoria->categoria;?>"> <?php echo $categoria->categoria;?> </option>
                                            <?php endif;?>
                                        <?php endforeach;?>    
                                    </select>
                                <?php else: ?>
                                    <input size="30" class='selectorSpinnerEdicion' placeholder=" Valoración numérica (e.j: 7.45)" name="selectorCategoriaCalidad[]" value="<?php echo $valoracion->indice;?>"/>
                                <?php endif; ?>
                            </td>
                            <td class="center">
                                <img class="iconInput pointer iconDelete_preguntar" src="components/com_gestorcore/imgs/iconDelete.png">
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
            <div class="div_addValoracion">
                <button id="buttonAddValoracion" type="button">Añadir nueva valoración</button>
            </div>
        
    <?php echo JHtml::_('tabs.end'); ?>        
             
    <div>
        <input type="hidden" name="option" value="com_gestorcore" />
        <input type="hidden" name="task" value="" />
        <inupt type="hidden" name="controller" value="congreso" />                
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>

<?php echo $this->loadTemplate('hiddenOptions'); ?>

<div id="dialog-form_addCongreso" title="Nuevo Congreso" class="dialog-form" hidden>
    <br>
    <div></div>
</div>

<div id="module-submenu">
    <?php echo GescoreHelper::getSubmenu(); ?>
</div>
