<?php 

namespace App\Twig\HelperForDataTable;

/**
 * Klasa formatująca ikonki specjalnie dla DataTable
 * @author mszumiela
 */
class PrettyProductDescriptionInDataTable
{
    /**
     * Dodaje ikonkę dla zmiennych typu bool do tabel DataTable
     * 
     * @param string|null $description
     * @return string
     */
    public static function prettyPrint(?string $description, int $lengthDes = 15): string {

        # jeżeli opis jest pusty to zostawiam
        if($description === null || $description == ''){
            return '';
        }

        # jeżeli opis jest krótki to opuszczam
        $currentStringLength = strlen($description);
        if($currentStringLength <= $lengthDes){
            return $description;
        }

        # wyświetlam ładny opis
        $htmlToolTip = '<span data-toogle="tooltip" title="%s">%s</span>';
        return sprintf($htmlToolTip, $description, substr($description, 0, $lengthDes-1).'...');
    }// end prettyPrint

}// end class
