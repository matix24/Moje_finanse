<?php 

namespace App\Twig\HelperForDataTable;

/**
 * Klasa formatująca datę specjalnie dla DataTable
 * @author mszumiela
 */
class PrettyDateInDataTable
{
    /**
     * Ładnie formatuje datę wyświetlaną w tabeli DataTable generowaną w JSON
     * 
     * @param \DateTime $date
     * @return string
     */
    public static function prettyPrint(\DateTime $date): string {
        return $date->format('Y-m-d').'<br /><span class="small">('.$date->format('H:i:s').')</span>';
    }// end prettyPrint

}// end class
