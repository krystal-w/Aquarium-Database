<?php

/// Singleton Database Manager

final class DataManager
{
    private static $inst = null;
    private $conn;
    /*
    * Call this method to access Singleton. Like this:
    * $yourVariable = DBManager::Instance();
    */
    public static function Instance()
    {
        if (!self::$inst) {
            self::$inst = new DataManager();

            return self::$inst;
        }
    }

    private function __construct() {
        $this->conn = OCILogon("ora_jlacsama", "a65495079", "dbhost.students.cs.ubc.ca:1522/stu"); // Just for local testing; pls change back upon push to master
        //replace these with your own oracle account
    }

    private function connect() {
        return $this->conn;
    }

    private function disconnect($db_conn) {
        OCILogoff($this->conn);
    }

    private function printErrors() {
        echo "cannot connect";
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
    }

    function executeBoundSQL($cmdstr, $list)
    {
        $db_conn = $this->connect();
        $success = true;
        if ($db_conn) {
            $statement = OCIParse($db_conn, $cmdstr);
            if (!$statement) {
                $e = OCI_Error($db_conn); // handle error in $statement
                $success = false;
                new Exception("<br/>Cannot parse the following command: ".$cmdstr."<br/>".$e['message']);
            }
            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    OCIBindByName($statement, $bind, $val);
                    unset($val);
                }
                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    $e = OCI_Error($statement); // handle error in $statement
                    $success = false;
                    new Exception("<br/>Cannot execute the following command: ".$cmdstr."<br/>".$e['message']);
                }
            }
            OCICommit($db_conn);
            $this->disconnect($db_conn);
            return $statement;
        } else {
            $this->printErrors();
        }
    }

    function executePlainSQL($cmdstr)
    {
        $db_conn = $this->connect();
        $success = true;
        if ($db_conn) {
            $statement = OCIParse($db_conn, $cmdstr);
            if (!$statement) {
                $e = OCI_Error($db_conn); // handle error in $statement
                $success = false;
                new Exception("<br/>Cannot parse the following command: ".$cmdstr."<br/>".$e['message']);
            }
            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                $e = OCI_Error($statement); // handle error in $statement
                $success = false;
                new Exception("<br/>Cannot execute the following command: ".$cmdstr."<br/>".$e['message']);
            } else {
            }
            OCICommit($db_conn);
            $this->disconnect($db_conn);
            return $statement;
        } else {
            $this->printErrors();
        }
    }
}
?>
