<?php 

namespace App\Twig\HelperForDataTable;

use App\Entity\Product;

/**
 * Klasa formatująca waluty
 * @author mszumiela
 */
class PrettyMoneyInDataTable
{
    /**
     * format walutowy
     * 
     * @param float $money
     * @return string
     */
    public static function prettyPrint($money, $currency = 'zł'): string {
        if(!is_numeric($money)){
            return "0,00 ".$currency;
        }
        return number_format($money, 2, ',', ' ').' '.$currency;
    }// end prettyPrint

}// end class
