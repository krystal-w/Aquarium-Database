//adopted and modified from Tutorial 7 Code
<html>
    <head>
        <title>CPSC 304 PHP/Oracle Demonstration</title>
    </head>

    <body>
        <h2>Reset(JUST FOR DEBUG PURPOSES)</h2>
        <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>

        <form method="POST" action="tests.php">
            <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p>
        </form>

        <hr />

        <h2>Create New Aquarium Event</h2>
        <form method="POST" action="tests.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertAQEventRequest">
               <!-- Number: <input type="text" name="insNo"> <br /><br /> -->
               <!--Name: <input type="text" name="insName"> <br /><br /> -->
               ID: <input type = "number" name = "id" >
               Date: <input type = "date" name = "date" >
               Name: <input type = "text" name = "name" >
               Start: <input type = "time" name = "time_start" >
               End: <input type = "time" name = "time_end" >

               Type: <input type = "text" name = "type" >
               Price: <input type = "number" name = "ticket_price" >
               Capacity: <input type = "number" name = "capacity" >
               Location: <input type = "text" name = "location" >


            <input type="submit" value="Insert" name="insert-aquarium-event"></p>
        </form>
        <hr />

        <h2>Create New Customer Event</h2>
        <form method="POST" action="tests.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertCUEventRequest">
               ID: <input type = "number" name = "id" >
               Date: <input type = "date" name = "date" >
               Name: <input type = "text" name = "name" >
               Start: <input type = "time" name = "time_start" >
               End: <input type = "time" name = "time_end" >

               Customer_ID: <input type = "number" name = "customer_id" >
               Group_size: <input type = "number" name = "group_size" >
               Cost: <input type = "number" name = "cost" >

            <input type="submit" value="Insert" name="insert-customer-event"></p>
        </form>
        <hr />

        <h2>Delete Event</h2>
        <form method="POST" action="tests.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="deleteRequest">
            Number: <input type="number" name="id"> <br /><br />
            <input type="submit" value="delete" name="delete"></p>
        </form>
        <hr />

        <h2>Select Event(and view)</h2>
        <form method="GET" action="tests.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="selectRequest">
            Number: <input type="number" name="id"> <br /><br />
            <input type="submit" value="select" name="select"></p>
        </form>
        <hr />

        <h2>Join Biologist, checkup, checkopportunity, Employee (select name), biologist specialty, to see who is doing checkup </h2>

        <hr />

        <h2>Update Event</h2>
        <hr />

        <h2>Projection(checkup)</h2>
        <hr />

        <h2>Aggregate with group by- Count how many weekly/daily schedules</h2>
        <hr />

        <h2>Aggregate with having- Count animals that have poor health</h2>
        <hr />

        <h2>Nested agg with group by- Find the maximum of the average capacity of aquarium events group by date</h2>
        <hr />

        <h2>Division- Find employees who are in all events</h2>
        <hr />

        <?php
            $success = True; //keep track of errors so it redirects the page only if there are no errors
            $db_conn = NULL; // edit the login credentials in connectToDB()
            $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

            function debugAlertMessage($message) {
                global $show_debug_alert_messages;

                if ($show_debug_alert_messages) {
                    echo "<script type='text/javascript'>alert('" . $message . "');</script>";
                }
            }

            function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
                //echo "<br>running ".$cmdstr."<br>";
                global $db_conn, $success;

                $statement = OCIParse($db_conn, $cmdstr);
                //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

                if (!$statement) {
                    echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                    echo htmlentities($e['message']);
                    $success = False;
                }

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                    echo htmlentities($e['message']);
                    $success = False;
                }

    			return $statement;
    		}

            function executeBoundSQL($cmdstr, $list) {
                /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
    		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
    		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
    		See the sample code below for how this function is used */

    			global $db_conn, $success;
    			$statement = OCIParse($db_conn, $cmdstr);

                if (!$statement) {
                    echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($db_conn);
                    echo htmlentities($e['message']);
                    $success = False;
                }

                foreach ($list as $tuple) {
                    foreach ($tuple as $bind => $val) {
                        //echo $val;
                        //echo "<br>".$bind."<br>";
                        OCIBindByName($statement, $bind, $val);
                        unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
    				}

                    $r = OCIExecute($statement, OCI_DEFAULT);
                    if (!$r) {
                        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                        $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                        echo htmlentities($e['message']);
                        echo "<br>";
                        $success = False;
                    }
                }
            }

            function connectToDB() {
                global $db_conn;

                // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    			// ora_platypus is the username and a12345678 is the password.
                $db_conn = OCILogon("ora_jlacsama", "a65495079", "dbhost.students.cs.ubc.ca:1522/stu");

                if ($db_conn) {
                    debugAlertMessage("Database is Connected");
                    return true;
                } else {
                    debugAlertMessage("Cannot connect to Database");
                    $e = OCI_Error(); // For OCILogon errors pass no handle
                    echo htmlentities($e['message']);
                    return false;
                }
            }

            function disconnectFromDB() {
                global $db_conn;

                debugAlertMessage("Disconnect from Database");
                OCILogoff($db_conn);
            }

    		if (isset($_POST['reset']) || isset($_POST['insert-aquarium-event']) || isset($_POST['insert-customer-event'])
            || isset($_POST['delete']) || isset($_POST['update']))
            {
                handlePOSTRequest();
            } else if (isset($_GET['select']) ||  isset($_GET['join']) || isset($_GET['projection']) ||
              isset($_GET['aggr-group-by']) ||  isset($_GET['aggr-with-having']) || isset($_GET['nested-aggr-group-by']) ||
              isset($_GET['division']))
            {
                handleGETRequest();
            }

            // HANDLE ALL POST ROUTES
    	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
            function handlePOSTRequest() {
                if (connectToDB()) {
                    if (array_key_exists('resetTablesRequest', $_POST)) {
                        handleResetRequest();
                    } else if (array_key_exists('insertAQEventRequest', $_POST)) {
                        insertAQEvent();
                    } else if (array_key_exists('insertCUEventRequest', $_POST)) {
                        insertCUEvent();
                    } else if (array_key_exists('deleteRequest', $_POST)) {
                        deleteEvent();
                    } else if (array_key_exists('updateRequest', $_POST)) {
                        updateRequest();
                    }

                    disconnectFromDB();
                }
            }

            // HANDLE ALL GET ROUTES
    	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
            function handleGETRequest() {
                if (connectToDB()) {
                    if (array_key_exists('selectRequest', $_GET)) {
                        selectRequest();
                    } else if (array_key_exists('joinRequest', $_GET)) {
                        joinRequest();
                    } else if (array_key_exists('projectionRequest', $_GET)) {
                        projectionRequest();
                    } else if (array_key_exists('aggr-group-byRequest', $_GET)) {
                        aggrByGroupRequest();
                    } else if (array_key_exists('aggr-group-with-havingRequest', $_GET)) {
                        aggrGroupWithHavingRequest();
                    } else if (array_key_exists('nested-aggr-group-byRequest', $_GET)) {
                        nestedAggrGroupByRequest();
                    } else if (array_key_exists('divisionRequest', $_GET)) {
                        divisionRequest();
                    }

                    disconnectFromDB();
                }
            }

            function handleResetRequest() {
                global $db_conn;
                // Drop old table
                executePlainSQL("DROP TABLE demoTable");

                // Create new table
                echo "<br> creating new table <br>";
                executePlainSQL("CREATE TABLE demoTable (id int PRIMARY KEY, name char(30))");
                OCICommit($db_conn);
            }

            // query functions
            function insertAQEvent() {

            }

            function insertCUEvent() {

            }

            function deleteEvent() {

            }

            function updateRequest() {

            }

            function selectRequest() {

            }

            function joinRequest() {

            }

            function projectionRequest() {

            }

            function aggrByGroupRequest() {

            }

            function aggrGroupWithHavingRequest() {

            }

            function nestedAggrGroupByRequest() {

            }

            function divisionRequest() {

            }
		?>
	</body>
</html>
