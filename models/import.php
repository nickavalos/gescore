<?php
/**
 * This file is part of the project Gestor de Congresos y Revistas en Joomla (GesCORE).
 *
 * @package		GesCORE backend 
 * @subpackage  Models 
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

defined('_JEXEC') or die();
jimport( 'joomla.application.component.modellist' );

class GestorcoreModelImport extends JModelList
{
    var $journal = 'j';
    var $book = 'b';
    var $book_series = 'k';
    var $conference_proceeding = 'p';
    var $report = 'r';
    var $trade_publication = 'd';
    
    
    
    function getSourceTypes () {
        // Build the active state filter options.
        $options        = array();
        $options[]      = JHtml::_('select.option', $this->journal, JText::_('COM_GESCORE_IMPORT_DOCTYPE_JOURNAL'));
        $options[]      = JHtml::_('select.option', $this->book, JText::_('COM_GESCORE_IMPORT_DOCTYPE_BOOK'));
        $options[]      = JHtml::_('select.option', $this->book_series, JText::_('COM_GESCORE_IMPORT_DOCTYPE_BOOK_SERIES'));
        $options[]      = JHtml::_('select.option', $this->conference_proceeding, JText::_('COM_GESCORE_IMPORT_DOCTYPE_CONFERENCE_PROCEEDING'));
        $options[]      = JHtml::_('select.option', $this->report, JText::_('COM_GESCORE_IMPORT_DOCTYPE_REPORT'));
        $options[]      = JHtml::_('select.option', $this->trade_publication, JText::_('COM_GESCORE_IMPORT_DOCTYPE_TRADE_PUBLICATION'));

        return $options;
    }
    
    function getMesesAnyo () {
        // Build the active state filter options.
        $options        = array();
        $options[]      = JHtml::_('select.option', 'January', JText::_('COM_GESCORE_ENERO'));
        $options[]      = JHtml::_('select.option', 'February', JText::_('COM_GESCORE_FEBRERO'));
        $options[]      = JHtml::_('select.option', 'March', JText::_('COM_GESCORE_MARZO'));
        $options[]      = JHtml::_('select.option', 'April', JText::_('COM_GESCORE_ABRIL'));
        $options[]      = JHtml::_('select.option', 'May', JText::_('COM_GESCORE_MAYO'));
        $options[]      = JHtml::_('select.option', 'June', JText::_('COM_GESCORE_JUNIO'));
        $options[]      = JHtml::_('select.option', 'July', JText::_('COM_GESCORE_JULIO'));
        $options[]      = JHtml::_('select.option', 'August', JText::_('COM_GESCORE_AGOSTO'));
        $options[]      = JHtml::_('select.option', 'September', JText::_('COM_GESCORE_SEPTIEMBRE'));
        $options[]      = JHtml::_('select.option', 'October', JText::_('COM_GESCORE_OCTUBRE'));
        $options[]      = JHtml::_('select.option', 'November', JText::_('COM_GESCORE_NOVIEMBRE'));
        $options[]      = JHtml::_('select.option', 'December', JText::_('COM_GESCORE_DICIEMBRE'));

        return $options;
    }
    
    function getTiposDocumento () {
        // Build the active state filter options.
        $options        = array();
        $options[]      = JHtml::_('select.option', 'ar', 'Article');
        $options[]      = JHtml::_('select.option', 'ab', 'Abstract Report');
        $options[]      = JHtml::_('select.option', 'ip', 'Article in Press');
        $options[]      = JHtml::_('select.option', 'bk', 'Book');
        $options[]      = JHtml::_('select.option', 'bz', 'Business Article');
        $options[]      = JHtml::_('select.option', 'ch', 'Book Chapter');
        $options[]      = JHtml::_('select.option', 'cp', 'Conference Paper');
        $options[]      = JHtml::_('select.option', 'cr', 'Conference Review');
        $options[]      = JHtml::_('select.option', 'ed', 'Editorial');
        $options[]      = JHtml::_('select.option', 'er', 'Erratum');
        $options[]      = JHtml::_('select.option', 'le', 'Letter');
        $options[]      = JHtml::_('select.option', 'no', 'Note');
        $options[]      = JHtml::_('select.option', 'pr', 'Press Release');
        $options[]      = JHtml::_('select.option', 're', 'Review');
        $options[]      = JHtml::_('select.option', 'sh', 'Short Survey');

        return $options;
    }
    
    function getSubareas () {
        // Build the active state filter options.
        $options        = array();
        $options[]      = JHtml::_('select.option', 'AGRI', 'Agricultural and Biological Sciences');
        $options[]      = JHtml::_('select.option', 'ARTS', 'Arts and Humanities');
        $options[]      = JHtml::_('select.option', 'BIOC', 'Biochemistry, Genetics and Molecular Biology');
        $options[]      = JHtml::_('select.option', 'BUSI', 'Business, Management and Accounting');
        $options[]      = JHtml::_('select.option', 'CENG', 'Chemical Engineering');
        $options[]      = JHtml::_('select.option', 'CHEM', 'Chemistry');
        $options[]      = JHtml::_('select.option', 'COMP', 'Computer Science');
        $options[]      = JHtml::_('select.option', 'DECI', 'Decision Sciences');
        $options[]      = JHtml::_('select.option', 'DENT', 'Dentistry');
        $options[]      = JHtml::_('select.option', 'EART', 'Earth and Planetary Sciences');
        $options[]      = JHtml::_('select.option', 'ECON', 'Economics, Econometrics and Finance');
        $options[]      = JHtml::_('select.option', 'ENER', 'Energy');
        $options[]      = JHtml::_('select.option', 'ENGI', 'Engineering');
        $options[]      = JHtml::_('select.option', 'ENVI', 'Environmental Science');
        $options[]      = JHtml::_('select.option', 'HEAL', 'Health Professions');
        $options[]      = JHtml::_('select.option', 'IMMU', 'Immunology and Microbiology');
        $options[]      = JHtml::_('select.option', 'MATE', 'Materials Science');
        $options[]      = JHtml::_('select.option', 'MATH', 'Mathematics');
        $options[]      = JHtml::_('select.option', 'MEDI', 'Medicine');
        $options[]      = JHtml::_('select.option', 'NEUR', 'Neuroscience');
        $options[]      = JHtml::_('select.option', 'NURS', 'Nursing');
        $options[]      = JHtml::_('select.option', 'PHAR', 'Pharmacology, Toxicology and Pharmaceutics');
        $options[]      = JHtml::_('select.option', 'PHYS', 'Physics and Astronomy');
        $options[]      = JHtml::_('select.option', 'PSYC', 'Psychology');
        $options[]      = JHtml::_('select.option', 'SOCI', 'Social Sciences');
        $options[]      = JHtml::_('select.option', 'VETE', 'Veterinary');
        $options[]      = JHtml::_('select.option', 'MULT', 'Multidisciplinary');

        return $options;
    }
    
    function getSortValues() {
        // Build the active state filter options.
        $options        = array();
        $options[]      = JHtml::_('select.option', 'Date', 'Fecha de Publicación');
        $options[]      = JHtml::_('select.option', 'Relevancy', 'Relevancia');
        $options[]      = JHtml::_('select.option', 'Authors', 'Autor');
        $options[]      = JHtml::_('select.option', 'SourceTitle', 'Título de la entidad');
        $options[]      = JHtml::_('select.option', 'LoadDate', 'Fecha de adición');
        return $options;
    }
    
    
}

?>