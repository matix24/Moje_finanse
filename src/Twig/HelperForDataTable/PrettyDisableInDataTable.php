<?php

namespace App\Twig\HelperForDataTable;

/**
 * Klasa formatująca ikonki specjalnie dla DataTable
 * @author mszumiela
 */
class PrettyDisableInDataTable
{

    /**
     * Kod html do wyświetlenia kiedy sukces
     * @var string
     */
    public const TEXT_SUCCESS = '<button class="btn btn-xs btn-success" data-toogle="tooltip" title="Pozycja włączona"><i class="fas fa-power-off"></i></button>';

    /**
     * Kod html do wyświetlenia kiedy danger
     */
    public const TEXT_DANGER = '<button class="btn btn-xs btn-danger" data-toogle="tooltip" title="Pozycja wyłączona"><i class="fas fa-power-off"></i></button>';

    /**
     * Dodaje ikonkę dla zmiennych typu bool do tabel DataTable
     *
     * @param bool $option
     * @return string
     */
    public static function prettyPrint(bool $option): string {
        return $option ? static::TEXT_DANGER : static::TEXT_SUCCESS;
    }// end prettyPrint

}// end class
