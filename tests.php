//adopted and modified from Tutorial 7 Code
<html>
    <head>
        <title>CPSC 304 PHP/Oracle Demonstration</title>
    </head>

    <body>
        <h2>Reset</h2>
        <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>

        <form method="POST" action="tests.php">
            <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p>
        </form>

        <hr />

        <h2>Create New Aquarium Event</h2>
        <form method="POST" action="tests.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
               <!-- Number: <input type="text" name="insNo"> <br /><br /> -->
               <!--Name: <input type="text" name="insName"> <br /><br /> -->

            <input type="submit" value="Insert" name="insert-aquarium-event"></p>
        </form>
        <hr />

        <h2>Create New Customer Event</h2>
        <form method="POST" action="tests.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            Number: <input type="text" name="insNo"> <br /><br />
            Name: <input type="text" name="insName"> <br /><br />

            <input type="submit" value="Insert" name="insert-customer-event"></p>
        </form>
        <hr />

        <h2>Delete Event</h2>
        <form method="POST" action="tests.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            Number: <input type="number" name="id"> <br /><br />
            <input type="submit" value="delete" name="delete"></p>
        </form>
        <hr />

        <h2>Select Event(and view)</h2>
        <form method="POST" action="tests.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
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
		//this tells the system that it's no longer just parsing html; it's now parsing PHP
        require "db_interface.php";

        // PHP action table router
        if(connectToDB()) {
            if (isset($_POST['reset']) || isset($_POST['insert-aquarium-event']) || isset($_POST['insert-customer-event'])
             || isset($_POST['delete']) ||
                isset($_POST['update'])
            ) {
                if (isset($_POST['reset'])) {
                    include_once "reset.php";
                    handleResetRequest();
                } else if (isset($_POST['insert-aquarium-event'])) {
                    include_once "insert_event.php";

                } else if (isset($_POST['insert-customer-event'])) {
                    // use other function in file
                    include_once "insert_event.php";

                }
                 else if (isset($_POST['delete'])) {
                    include_once "delete_event.php";

                } else if (isset($_POST['update'])) {
                    include_once "update_event.php";

                }
                disconnectFromDB();
            }
            else if (isset($_GET['select']) ||  isset($_GET['join']) || isset($_GET['projection']) ||
                isset($_GET['aggr-group-by']) ||  isset($_GET['aggr-with-having']) ||
                isset($_GET['nested-aggr-group-by'])
            ) {
                if (isset($_GET['select'])) {
                    include_once "select_event";

                } else if (isset($_GET['join'])) {
                    include_once "join_checkup.php";

                } else if (isset($_GET['projection'])) {
                    include_once "projection_checkup.php";

                } else if (isset($_GET['aggr-group-by'])) {
                    include_once "aggregation-groupby.php";

                } else if (isset($_GET['aggr-with-having'])) {
                    include_once "aggregation-with-having.php";

                } else if (isset($_GET['nested-aggr-group-by'])) {
                    include_once "nested-aggregation-group-by.php";

                }
            }
            disconnectFromDB();
        }



		?>
	</body>
</html>
