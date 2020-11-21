<?php 

namespace App\Twig\HelperForDataTable;

/**
 * Klasa formatująca ikonki specjalnie dla DataTable
 * @author mszumiela
 */
class PrettyYesNoInDataTable
{
    /**
     * Dodaje ikonkę dla zmiennych typu bool do tabel DataTable
     * 
     * @param bool $option
     * @return string
     */
    public static function prettyPrint(bool $option): string {
        return $option ? 'Tak' : 'Nie';
    }// end prettyPrint

}// end class
