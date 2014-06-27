<?php
/**
 * default body template file for Gescore view of HelloWorld component
 *
 * @version		$Id: default_body.php 46 2010-11-21 17:27:33Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://google.com/+nickavalos
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

?>
<div id="div_nuevosTopicos">
    <!--<fieldset>
        <legend><input id="checkNuevosTopicos" name="checkNuevosTopicos" type="checkbox">&nbsp; Crear nuevos tópicos para la edición</legend>-->
        
        <table id="tableNuevosTopicos">
            <tr>
                <td><input name="nuevoTopico[]" size="50" placeholder=" Nuevo Tópico"/></td>
                <td>
                    <input name="descripcionNuevoTopico[]" size="80" placeholder=" Descripción del nuevo tópico"/> 
                    <img class="iconInput pointer iconDelete_sinPreguntar" src="components/com_gestorcore/imgs/iconDelete.png">
                </td>
            </tr>
        </table>
        <div>
            <button id="buttonAddTopico" type="button">Añadir nuevo tópico</button>
        </div>
</div>