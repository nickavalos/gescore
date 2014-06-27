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

$this->document->addScript(DIR_SCRIPTS_GESCORE . 'funciones.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'submenu.js');
$this->document->addScript(DIR_SCRIPTS_GESCORE . 'nuevoCongreso.js');

$this->document->addStyleSheet("http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css");       
$this->document->addStyleSheet(DIR_STYLESHEETS_GESCORE . 'utils.css');
?>
<form action="<?php echo JRoute::_('index.php?option=com_gestorcore&controller=congreso&task=listCongresos'); ?>" method="post" name="adminForm" id="form_nuevoCongreso">
    <!--<input type="button" id="add_congreso" value="Add congreso"/>-->
       
    <div id="dialog-form_addCongreso" title="Nuevo Congreso" class="form_addEntidad">
        <div id="content_addCongreso">
            <label><p class="validateTips"> Ingrese los datos del nuevo congreso </p></label>
            <table><tr>
                <td><fieldset id="fieldEntidadPublicadora">
                    <legend> Entidad Publicadora (EP)</legend>    
                    <label for="nomEntidad">Nombre</label>
                    <input type="text" name="nomEntidad" id="nomEntidad" class="dialog-ok text ui-widget-content ui-corner-all" />
                    <label for="acronimoEntidad">Acrónimo de la entidad</label>
                    <input type="text" name="acronimoEntidad" id="acronimoEntidad" class="dialog-ok text ui-widget-content ui-corner-all" />
                    <label for="linkEntidad">Link de la entidad</label>
                    <input type="text" name="linkEntidad" id="linkEntidad" class="dialog-ok text ui-widget-content ui-corner-all" placeholder="http://"/>
                    <label for="periodicidadEntidad">Periodicidad (meses)</label>
                    <input type="number" name="periodicidadEntidad" id="periodicidadEntidad" class="selectorSpinnerEdicion dialog-ok text ui-widget-content ui-corner-all" />
                </fieldset></td>
                <td><fieldset id="fieldEditorial">
                    <legend> Editorial de la EP </legend>
                    <table id="tableEscogerEditorial" class="font_size_105">
                        <tr>
                            <td><input type="radio" name="radioEntidadPublicadora" value="editorialExistente" id="editorialExistente" checked>Editorial Existente </td>
                        </tr>
                        <tr>
                            <td><input type="radio" name="radioEntidadPublicadora" value="nuevaEditorial" id="nuevaEditorial">Nueva Editorial </td>
                        </tr>
                    </table>
                    <div id="existenteEditorialEntidad">    
                        <select name="editorialEntidad" id="selectEditorialesEntidad" size="12">                    
                        </select>
                    </div>
                    <div id="nuevaEditorialEntidad" hidden>    
                        <label for="nomEditorial">Nombre de la Editorial</label>
                        <input type="text" name="nomEditorial" id="nomEditorial" class="dialog-ok text ui-widget-content ui-corner-all" />
                        <label for="siglasEditorial">Siglas de la editorial</label>
                        <input type="text" name="siglasEditorial" id="siglasEditorial" class="dialog-ok text ui-widget-content ui-corner-all" />
                        <label for="linkEditorial">Link de la editorial</label>
                        <input type="text" name="linkEditorial" id="linkEditorial" class="dialog-ok text ui-widget-content ui-corner-all"  placeholder="http://"/>
                    </div>
                </fieldset></td>    
            </tr></table>
        </div>
    </div>
    <div>
        <input type="hidden" name="option" value="com_gestorcore" />
        <input type="hidden" name="task" value="" />
        <inupt type="hidden" name="controller" value="congreso" />                
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>

<div id="module-submenu">
    <?php echo GescoreHelper::getSubmenu(); ?>
</div>