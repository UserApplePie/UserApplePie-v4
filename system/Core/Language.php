<?php
/**
 * Language - simple language handler.
 *
 * @author Bartek Kuśmierczuk - contact@qsma.pl - http://qsma.pl
 * @version 3.0
 */

namespace Core;

use Core\Error;

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
     * Check to see if user changed the language from default
     */
    public function __construct()
    {
        if(isset($_SESSION['cur_lang'])){
          define('LANG_CODE', $_SESSION['cur_lang']);
        }else{
          define('LANG_CODE', LANGUAGE_CODE);
        }
    }

    /**
     * Load language function.
     *
     * @param string $name
     * @param string $code
     */
    public function load($name, $code = LANG_CODE)
    {
        /** lang file */
        $file = APPDIR."Language/$code/$name.php";

        /** check if is readable */
        if (is_readable($file)) {
            /** require file */
            $this->array[$code] = include $file;
        } else {
            /** display error */
            echo Error::display("Could not load language file '$code/$name.php'");
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
    public function get($value, $code = LANG_CODE)
    {
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
    public static function show($value, $name, $code = LANG_CODE)
    {
        /** lang file */
        $file = APPDIR."Language/$code/$name.php";

        /** check if is readable */
        if (is_readable($file)) {
            /** require file */
            $array = include($file);
        } else {
            /** display error */
            echo Error::display("Could not load language file '$code/$name.php'");
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
      /** lang list file */
      $file = APPDIR."Language/LangList.php";
      /** check if is readable */
      if (is_readable($file)) {
          /** require file */
          $array = include($file);
          return $array;
      } else {
          /** display error */
          echo Error::display("Could not load language file '$code/$name.php'");
          die;
      }
    }
}
