<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class EventgalleryHelpersCaptcha
{

    public function validateCaptcha($password)
    {
        /*echo $_SESSION['rechen_captcha_spam'];
        echo "<br>";
        echo session_id();
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";*/
        $sicherheits_eingabe = $password;
        $sicherheits_eingabe = str_replace("=", "", $sicherheits_eingabe);
        if (isset($_SESSION['rechen_captcha_spam']) AND $sicherheits_eingabe == $_SESSION['rechen_captcha_spam']) {
            unset($_SESSION['rechen_captcha_spam']);
            return true;
        }
        return false;
    }

    public static function generateData() {
        $zahl1 = rand(10, 20); //Erste Zahl 10-20
        $zahl2 = rand(1, 10); //Zweite Zahl 1-10
        $operator = rand(1, 2); // + oder -

        if ($operator == "1") {
            $operatorzeichen = " + ";
            $ergebnis = $zahl1 + $zahl2;
        } else {
            $operatorzeichen = " - ";
            $ergebnis = $zahl1 - $zahl2;
        }

        $rechnung = $zahl1 . $operatorzeichen . $zahl2 . " = ?";

        $_SESSION['rechen_captcha_spam'] = $ergebnis;
        $_SESSION['rechen_captcha_calc'] = $rechnung;
    }


    public function displayCaptcha()
    {
        $rechnung = $_SESSION['rechen_captcha_calc'];
        $img = imagecreatetruecolor(80, 15);
        $schriftfarbe = imagecolorallocate($img, 13, 28, 91);
        $hintergrund = imagecolorallocate($img, 162, 162, 162);
        imagefill($img, 0, 0, $hintergrund);
        imagestring($img, 3, 2, 0, $rechnung, $schriftfarbe);
        header("Content-type: image/png");
        imagepng($img);
        imagedestroy($img);
    }

}
