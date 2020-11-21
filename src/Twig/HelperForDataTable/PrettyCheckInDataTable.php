<?php 

namespace App\Twig\HelperForDataTable;

/**
 * Klasa formatująca ikonki specjalnie dla DataTable
 * @author mszumiela
 */
class PrettyCheckInDataTable
{

    /**
     * Kod html do wyświetlenia kiedy sukces
     * @var string
     */
    public const TEXT_SUCCESS = '<i class="text-success fas fa-check"></i>';

    /**
     * Kod html do wyświetlenia kiedy danger
     */
    public const TEXT_DANGER = '<i class="text-danger fas fa-times"></i>';

    /**
     * Dodaje ikonkę dla zmiennych typu bool do tabel DataTable
     * 
     * @param bool $option
     * @return string
     */
    public static function prettyPrint(bool $option): string {
        return $option ? static::TEXT_SUCCESS : static::TEXT_DANGER;
    }// end prettyPrint

}// end class
