<?php
/**
 * Home Congresos, Gestor de congresos y revistas.
 *
 * @version		1.0 chdemko $
 * @package		Congreso
 * @subpackage          Components
 * @copyright           Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @author		Nick Avalos
 * @link		http://google.com/+nickavalos 
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior

JHtml::_('behavior.tooltip');

$this->document = JFactory::getDocument();
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-1-9-1.js");
$this->document->addScript(DIR_SCRIPTS_GESCORE . "jquery-ui.js");

$this->document->addScript(DIR_SCRIPTS_GESCORE . 'submenu.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'nuevaEntidadCalidad.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'funciones.js');

$this->document->addStyleSheet("http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");       
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE .  'utils.css');
?>
<form action="<?php echo JRoute::_('index.php?option=com_gestorcore&view=entidadCalidad'); ?>" method="post" name="adminForm" id="form_nuevaEntidadCalidad">
    <!--<input type="button" id="add_congreso" value="Add congreso"/>-->
    <div id="dialog-form_addCongreso" title="Nueva Entidad de Calidad" class="form_addEntidad">
        <div id="content_addCongreso">
            <label><p class="validateTips"> Ingrese los datos de la nueva entidad de calidad </p></label>
            <table class="table_alignTop"><tr>
                <td><fieldset class="fieldMinSize">
                    <legend> Entidad Publicadora (EP)</legend>    
                    <label for="nomEntidad">Nombre</label>
                    <input type="text" name="nomEntidad" id="nomEntidad" class="dialog-ok text ui-widget-content ui-corner-all" />
                    <label for="acronimoEntidad">Siglas de la entidad</label>
                    <input type="text" name="acronimoEntidad" id="acronimoEntidad" class="dialog-ok text ui-widget-content ui-corner-all" />
                    <label for="linkEntidad">Link de la entidad</label>
                    <input type="text" name="linkEntidad" id="linkEntidad" class="dialog-ok text ui-widget-content ui-corner-all" placeholder="http://"/>
                    <label for="criteriosEntidad">Criterios</label>
                    <input type="text" name="criteriosEntidad" id="criteriosEntidad" class=" dialog-ok text ui-widget-content ui-corner-all" />
                </fieldset></td>
                <td><fieldset class="fieldMinSize panelform">
                    <legend> Categorias de la EP </legend>
                    <table class="gescore_width_100">
                        <label>Tipo de categoria:</label>
                        <fieldset id="escogerTipoCategoria" class="radio">
                            <input type="radio" name="radioTipoCategoria" value="lista" id="categoriaLista" checked><label class="noResize">Lista</label>
                            <input type="radio" name="radioTipoCategoria" value="numerico" id="categoriaNumerico"><label class="noResize">Numérico</label>
                        </fieldset>
                    </table>
                    <br>
                    <div id="div_listaCategorias">
                        Ordena de mayor a menor categoría:
                        <ul id='listaCategorias' class="sortable_entidad">
                            <li class="ui-state-default typeCategoria">
                                <span class="ui-icon ui-icon-arrowthick-2-n-s" title="Arrastre para ordenar"></span>
                                <input type="text" size="33" name="categorias[]" class="inputCategoria" placeholder=" Categoría"/>
                                <span class="close_cat pointer"><img src="components/com_gestorcore/imgs/iconDelete2.png" class="iconDeleteCriterio_preguntar" title="close"></span>
                            </li>
                            <li class="ui-state-default typeCategoria">
                                <span class="ui-icon ui-icon-arrowthick-2-n-s" title="Arrastre para ordenar"></span>
                                <input type="text" size="33" name="categorias[]" class="inputCategoria" placeholder=" Categoría"/>
                                <span class="close_cat pointer"><img src="components/com_gestorcore/imgs/iconDelete2.png" class="iconDeleteCriterio_preguntar" title="close"></span>
                            </li>
                            <li class="ui-state-default typeCategoria">
                                <span class="ui-icon ui-icon-arrowthick-2-n-s" title="Arrastre para ordenar"></span>
                                <input type="text" size="33" name="categorias[]" class="inputCategoria" placeholder=" Categoría"/>
                                <span class="close_cat pointer"><img src="components/com_gestorcore/imgs/iconDelete2.png" class="iconDeleteCriterio_preguntar" title="close"></span>
                            </li>
                        </ul>
                        <button type="button" id="addNuevaCategoria">Nueva Categoría</button>
                    </div>
                </fieldset></td>    
            </tr></table>
        </div>
    </div>
    <div>
        <input type="hidden" name="option" value="com_gestorcore" />
        <input type="hidden" name="task" value="" />
        <inupt type="hidden" name="controller" value="entidadCalidad" />                
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>

<div id="htmlOculto" hidden>
    <li class="ui-state-default typeCategoria">
        <span class="ui-icon ui-icon-arrowthick-2-n-s" title="Arrastre para ordenar"></span>
        <input type="text" size="33" name="categorias[]" class="inputCategoria" placeholder=" Categoría"/>
        <span class="close_cat pointer"><img src="components/com_gestorcore/imgs/iconDelete2.png" class="iconDeleteCriterio_preguntar" title="close"></span>
    </li>
</div>

<div id="module-submenu">
    <?php echo GescoreHelper::getSubmenu(); ?>
</div>