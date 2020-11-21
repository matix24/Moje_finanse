<?php

namespace App\Service\ObjectHelper;

/**
 * Klasa pomagająca w debugowaniu aplikacji
 * @author mszumiela
 */
class DebugPrinter
{

    /**
     * Debuguje zmienną klasycznym php
     *
     * <code>
     *  print_r('<pre>');
     *  print_r($data);
     *  print_r('</pre>');
     * </code>
     *
     * @param mixed $data
     * @param bool $exit
     * @return void
     */
    public static function printOld($data, bool $exit = true)
    {
        print_r('<pre>');
        print_r($data);
        print_r('</pre>');
        if ($exit) {
            die;
        }
    } // end printOld

    /**
     * Debuguje zmienną wykorzystując metodę wbudowaną w symfony
     *
     * @param mixed $data
     * @param bool $exit
     * @return void
     */
    public static function printNew($data, bool $exit = true)
    {
        dump($data);
        if ($exit) {
            die;
        }
    } // end printNew

}// end class