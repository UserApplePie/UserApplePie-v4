<?php
/**
 * Model - the base model
 *
 * Nova Framework
 * @author David Carr - dave@daveismyname.com
 * @version 3.0
 * UserApplePie
 * @author David (DaVaR) Sargent
 * @version 3.0.3
 */

namespace Core;

use Helpers\Database;

/**
 * Base model class all other models will extend from this base.
 */
abstract class Model
{
    /**
     * Hold the database connection.
     *
     * @var object
     */
    protected $db;

    /**
     * Create a new instance of the database helper.
     */
    public function __construct()
    {
        /** connect to PDO here. */
        $this->db = Database::get();
    }
}
