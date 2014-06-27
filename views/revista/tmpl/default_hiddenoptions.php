<?php
/**
 * default body template file for Gescore view of HelloWorld component
 *
 * @version		$Id: default_body.php  $
 * @package		Congreso
 * @subpackage	Components
 * @copyright	Copyright (C) 2014 Open Source Matters, Inc. All rights reserved.
 * @author		Nick Avalos
 * @link		http://google.com/+nickavalos
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

?>

<div id="dialog-form_addTopicoEntidad" title="Nuevo Tópico" class="dialog-form" hidden>
    <br><p class="validateTips">Por favor seleccione los tópicos que quiere añadir a la revista.</p>
    <form>
        <table id="table_listTopicos" class="adminlist">
            <thead>
                <tr>
                    <th width="2">
                        <input type="checkbox" class="checkall_topicos" />
                    </th>
                    <th width="10"> <?php echo JText::_('Nombre del Tópico'); ?> </th>
                    <th width="5"> <?php echo JText::_('Descripción'); ?> </th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </form>
</div>

<div id="htmlOculto" hidden>
    <table id="filaTableNuevosTopicos">
        <tr>
            <td><input name="nuevoTopico[]" size="50" placeholder=" Nuevo Tópico"/></td>
            <td><input name="descripcionNuevoTopico[]" size="80" placeholder=" Descripción del nuevo tópico"/> <img class="iconInput pointer iconDelete_sinPreguntar" src="components/com_gestorcore/imgs/iconDelete.png"></td>
        </tr>
    </table>
    <table id="filaTableEntidadValoracion">
        <tr class="row0">
            <td> 
                <select class="selectorEntidadCalidad"  name="selectorEntidadCalidad[]">
                    <option value="" selected>Selecciona una Entidad</option>
                    <?php foreach ($this->entidadesCalidad as $entidadCalidad): ?>
                            <option value="<?php echo $entidadCalidad->nombre;?>"> <?php echo $entidadCalidad->nombre;?> </option>
                    <?php endforeach;?>    
                </select>
            </td>
            <td class="selectorCategoriaCalidad">
                <select name="selectorCategoriaCalidad[]"></select> 
            </td>
            <td>
                <img class="iconInput pointer iconDelete_preguntar" src="components/com_gestorcore/imgs/iconDelete.png">
            </td>
        </tr>
    </table>
    <select name="topicosFromRevista[]" class="crossSelectRevista" multiple></select>>  
    <div id="rowNuevoTopico">
        <table>
            <tr>
                <td width="1%"> <input type="checkbox" name="toggleTopicos[]" class="checkBox_topicos" /></td>
            </tr>
        </table>
    </div>
</div>