<?php 

namespace App\Twig\HelperForDataTable;

use App\Entity\Product;

/**
 * Klasa formatująca ikonki specjalnie dla DataTable
 * @author mszumiela
 */
class PrettyProductInDataTable
{

    /**
     * Kod html do wyświetlenia kiedy product is new
     * @var string
     */
    public const BTN_NEW_SUCCESS = '<button data-link="%s" data-type="partner_product_new" data-idproduct="%s" data-idpartnerproduct="%s" class="btn btn-xs btn-success btn-productSings" data-toogle="tooltip" title="Produkt oznaczony jako nowy. Kliknij, aby wyłączyć.">N</button> ';
    public const BTN_NEW_DEFAULT  = '<button data-link="%s" data-type="partner_product_new" data-idproduct="%s" data-idpartnerproduct="%s" class="btn btn-xs btn-default btn-productSings" data-toogle="tooltip" title="Produkt NIE oznaczony jako nowy. Kliknij, aby włączyć.">N</button> ';

    /**
     * Kod html do wyświetlenia kiedy product is new
     * @var string
     */
    public const BTN_TOP_SUCCESS = '<button data-link="%s" data-type="partner_product_top" data-idproduct="%s" data-idpartnerproduct="%s" class="btn btn-xs btn-success btn-productSings" data-toogle="tooltip" title="Produkt oznaczony jako polecany. Kliknij, aby wyłączyć.">T</button> ';
    public const BTN_TOP_DEFAULT  = '<button data-link="%s" data-type="partner_product_top" data-idproduct="%s" data-idpartnerproduct="%s" class="btn btn-xs btn-default btn-productSings" data-toogle="tooltip" title="Produkt NIE oznaczony jako polecany. Kliknij, aby włączyć.">T</button> ';

    /**
     * Kod html do wyświetlenia kiedy product is new
     * @var string
     */
    public const BTN_SALE_SUCCESS = '<button data-link="%s" data-type="partner_product_sale" data-idproduct="%s" data-idpartnerproduct="%s" class="btn btn-xs btn-success btn-productSings" data-toogle="tooltip" title="Produkt oznaczony jako wyprzedaż. Kliknij, aby wyłączyć.">S</button> ';
    public const BTN_SALE_DEFAULT  = '<button data-link="%s" data-type="partner_product_sale" data-idproduct="%s" data-idpartnerproduct="%s" class="btn btn-xs btn-default btn-productSings" data-toogle="tooltip" title="Produkt NIE oznaczony jako wyprzedaż. Kliknij, aby włączyć.">S</button> ';

    /**
     * Kod html do wyświetlenia kiedy product is new
     * @var string
     */
    public const BTN_BESTSELLER_SUCCESS = '<button data-link="%s" data-type="partner_product_bestseller" data-idproduct="%s" data-idpartnerproduct="%s" class="btn btn-xs btn-success btn-productSings" data-toogle="tooltip" title="Produkt oznaczony jako bestseller. Kliknij, aby wyłączyć.">B</button> ';
    public const BTN_BESTSELLER_DEFAULT  = '<button data-link="%s" data-type="partner_product_bestseller" data-idproduct="%s" data-idpartnerproduct="%s" class="btn btn-xs btn-default btn-productSings" data-toogle="tooltip" title="Produkt NIE oznaczony jako bestseller. Kliknij, aby włączyć.">B</button> ';

    /**
     * Dodaje ikonkę dla zmiennych typu bool do tabel DataTable
     * 
     * @param Product $product
     * @return string
     */
    public static function prettyPrint(Product $product, string $link = '#'): string {
        
        $resultString = '';
        if($product->getPartnerProductNew() !== null && $product->getPartnerProductNew() === true){
            $resultString .= sprintf(static::BTN_NEW_SUCCESS, $link, $product->getIdProduct(), $product->getIdPartnerProduct());
        }else{
            $resultString .= sprintf(static::BTN_NEW_DEFAULT, $link, $product->getIdProduct(), $product->getIdPartnerProduct());
        }
        if($product->getPartnerProductTop() !== null && $product->getPartnerProductTop() === true){
            $resultString .= sprintf(static::BTN_TOP_SUCCESS, $link, $product->getIdProduct(), $product->getIdPartnerProduct());
        }else{
            $resultString .= sprintf(static::BTN_TOP_DEFAULT, $link, $product->getIdProduct(), $product->getIdPartnerProduct());
        }
        if($product->getPartnerProductSale() !== null && $product->getPartnerProductSale() === true){
            $resultString .= sprintf(static::BTN_SALE_SUCCESS, $link, $product->getIdProduct(), $product->getIdPartnerProduct());
        }else{
            $resultString .= sprintf(static::BTN_SALE_DEFAULT, $link, $product->getIdProduct(), $product->getIdPartnerProduct());
        }
        if($product->getPartnerProductBestseller() !== null && $product->getPartnerProductBestseller() === true){
            $resultString .= sprintf(static::BTN_BESTSELLER_SUCCESS, $link, $product->getIdProduct(), $product->getIdPartnerProduct());
        }else{
            $resultString .= sprintf(static::BTN_BESTSELLER_DEFAULT, $link, $product->getIdProduct(), $product->getIdPartnerProduct());
        }                        
        return '<br />'.$resultString;
    }// end prettyPrint

}// end class
