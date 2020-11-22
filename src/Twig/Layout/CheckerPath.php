<?php

namespace App\Twig\Layout;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

/**
 * Klasa odpowiedzialna za sprawdzanie aktualnej ścieżki w layoucie i oznaczająca przyciski w menu
 * jako aktywne kiedy użytkownik buszuje po aplikacji
 *
 * Class CheckerPath
 * @author mszumiela
 */
class CheckerPath extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('aLinkIsActive', [$this, 'aLinkIsActive']),
            new TwigFunction('menuIsOpen', [$this, 'menuIsOpen']),
            new TwigFunction('menuItemIsActive', [$this, 'menuItemIsActive'])
        ];
    }// end getFunctions

    /**
     * Funkcja sprawdza route i jeżeli się zgadza zwraca aktywną klasę potrzebną do otwarcia menu
     *
     * @param string $pathInfo Path from Request
     * @param string $pathTarget Path target
     * @return string Name of class css
     */
    public function menuItemIsActive(string $pathInfo, string $pathTarget)
    {
        if ($pathInfo === $pathTarget) {
            return 'active';
        }
        return '';
    }// end menuItemIsActive

    /**
     * Funkcja sprawdza podany url czy nazwa 'kontrolera' pokrywa się z zadanymi w tablicy
     * Jeżeli tak to zwraca nazwę klasy CSS do otwarcia menu
     *
     * @param string $pathInfo Path from Request
     * @param array $arrayOfRouteNames Część requesta
     * @return string Name of class css
     */
    public function menuIsOpen(string $pathInfo, array $arrayOfRouteName)
    {
        $pathInfoArray = explode('/', $pathInfo);
        $lengthRoute = count($arrayOfRouteName);

        switch ($lengthRoute) {
            case 4:
                if ($arrayOfRouteName[0] == $pathInfoArray[1] && $arrayOfRouteName[1] == $pathInfoArray[2] && $arrayOfRouteName[2] == $pathInfoArray[3] && $arrayOfRouteName[3] == $pathInfoArray[4]) {
                    return 'menu-open';
                }
                break;
            case 3:
                if ($arrayOfRouteName[0] == $pathInfoArray[1] && $arrayOfRouteName[1] == $pathInfoArray[2] && $arrayOfRouteName[2] == $pathInfoArray[3]) {
                    return 'menu-open';
                }
                break;
            case 2:
                if ($arrayOfRouteName[0] == $pathInfoArray[1] && $arrayOfRouteName[1] == $pathInfoArray[2]) {
                    return 'menu-open';
                }
                break;
            case 1:
                if ($arrayOfRouteName[0] == $pathInfoArray[1]) {
                    return 'menu-open';
                }
                break;
        }
        return '';
    }// end menuIsOpen

    /**
     * Funkcja sprawdza podany url czy nazwa 'kontrolera' pokrywa się z zadanymi w tablicy
     * Jeżeli tak to zwraca nazwę klasy CSS do oznaczenia buttona jako aktywnego
     *
     * @param string $pathInfo Path from Request
     * @param array $arrayOfRouteName Część requesta
     * @return string Name of class css
     */
    public function aLinkIsActive(string $pathInfo, array $arrayOfRouteName)
    {
        $pathInfoArray = explode('/', $pathInfo);
        $lengthRoute = count($arrayOfRouteName);

        switch ($lengthRoute) {
            case 4:
                if ($arrayOfRouteName[0] == $pathInfoArray[1] && $arrayOfRouteName[1] == $pathInfoArray[2] && $arrayOfRouteName[2] == $pathInfoArray[3] && $arrayOfRouteName[3] == $pathInfoArray[4]) {
                    return 'active';
                }
                break;
            case 3:
                if ($arrayOfRouteName[0] == $pathInfoArray[1] && $arrayOfRouteName[1] == $pathInfoArray[2] && $arrayOfRouteName[2] == $pathInfoArray[3]) {
                    return 'active';
                }
                break;
            case 2:
                if ($arrayOfRouteName[0] == $pathInfoArray[1] && $arrayOfRouteName[1] == $pathInfoArray[2]) {
                    return 'active';
                }
                break;
            case 1:
                if ($arrayOfRouteName[0] == $pathInfoArray[1]) {
                    return 'active';
                }
                break;
        }
        return '';
    }// end menuIsOpen
}// end class
