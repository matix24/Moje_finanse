<?php 

namespace App\Service\ObjectHelper;

/**
 * 
 * @author mszumiela
 */
class UserRandomPassword {

    /**
     * Słownik z którego powstanie hasło dla użytkownika 
     * @var string
     */
    const CHAR_DICTIONARY = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Generuje losowy ciąg znaków z podanego słownika
     * Domyślnie jest to 16 znaków
     * Wykorzystuje to dla wymyślenia hasła dla użytkownika systemu, który jest wprowadzany ręcznie 
     * 
     * @param int $lengthPassword
     * @return string
     */
    public static function generatePassword(int $lengthPassword = 16){
        
        if($lengthPassword > 255){
            $lengthPassword = 255; // max dla bazy danych
        }

        $dictionaryLength = strlen(static::CHAR_DICTIONARY);
        $randomPassword = '';
        for ($i = 0; $i < $lengthPassword; $i++) {
            $randomPassword .= static::CHAR_DICTIONARY[rand(0, $dictionaryLength - 1)];
        }
        return $randomPassword;        
    }// end generatePassword

}// end class