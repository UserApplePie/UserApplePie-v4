<?php
/**
* Language Plugin
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.0
*
* @author Bartek Kuśmierczuk - contact@qsma.pl - http://qsma.pl
* @version 3.0
 */

namespace Libs;

/**
 * Language class to load the requested language file.
 */
class Language
{
    /**
     * Variable holds array with language.
     *
     * @var array
     */
    private $array;

    /**
     * Variable for current language code.
     *
     * @var string
     */
    private $code;

    /**
     * Check to see if user changed the language from default
     */
    public function __construct()
    {

    }

    /**
     * Load language function.
     *
     * @param string $name
     * @param string $code
     */
    public function load($name)
    {
        $code = SELF::setLang();
        /** lang file */
        $file = APPDIR."System/Language/$code/$name.php";

        /** check if is readable */
        if (is_readable($file)) {
            /** require file */
            $this->array[$code] = include $file;
        } else {
            /** display error */
            echo \Libs\ErrorMessages::display_raw("Could not load language file '$code/$name.php'");
            die;
        }
    }

    /**
     * Get element from language array by key.
     *
     * @param  string $value
     *
     * @return string
     */
    public function get($value)
    {
        $code = SELF::setLang();
        if (!empty($this->array[$code][$value])) {
            return $this->array[$code][$value];
        } elseif(!empty($this->array[LANGUAGE_CODE][$value])) {
            return $this->array[LANGUAGE_CODE][$value];
        } else {
            return $value;
        }
    }

    /**
     * Get lang for views.
     *
     * @param  string $value this is "word" value from language file
     * @param  string $name  name of file with language
     * @param  string $code  optional, language code
     *
     * @return string
     */
    public static function show($value, $name)
    {
        $code = SELF::setLang();
        /** lang file */
        $file = APPDIR."System/Language/$code/$name.php";

        /** check if is readable */
        if (is_readable($file)) {
            /** require file */
            $array = include($file);
        } else {
            /** display error */
            echo \Libs\ErrorMessages::display_raw("Could not load language file '$code/$name.php'");
            die;
        }

        if (!empty($array[$value])) {
            return $array[$value];
        } else {
            return $value;
        }
    }

    /**
     * Get List of All Enabled Languages from LangList.php
     *
     */
    public static function getlangs()
    {
        $code = SELF::setLang();
        /** lang list file */
        $file = APPDIR."System/Language/LangList.php";
        /** check if is readable */
        if (is_readable($file)) {
          /** require file */
          $array = include($file);
          return $array;
        } else {
          /** display error */
          echo \Libs\ErrorMessages::display_raw("Could not load language file '$code/$name.php'");
          die;
        }
    }

    public static function setLang()
    {
        if(isset($_SESSION['cur_lang'])){
            $code = $_SESSION['cur_lang'];
        }else{
            if(!isset($code)){
                $code = LANGUAGE_CODE;
            }
        }
        return $code;
    }
}
