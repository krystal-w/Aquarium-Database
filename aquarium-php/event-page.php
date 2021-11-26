<html lang="en">

<head>

    <link rel="stylesheet" type="text/css" href="../aquarium-index.css">
    <!-- <link rel="stylesheet" type="text/css" href="../aquarium-css/event-page.css"> -->
    <title>Events</title>
    <style>
        table, th, td {
        border:1px solid black;
        padding: 2;
        }
    </style>

</head>

<?php

ini_set('session.save_path', getcwd() . "/../../../public_html_sessions");
$start = session_start();

include 'aquarium_dbmanager.php';
$manager = DataManager::Instance();

function printResult($result)
{ //prints results from a select statement
    echo "<table>";
    echo "<tr><th>Event ID</th><th>Event Name</th><th>Event Date</th><th>Start Time</th><th>Event Time</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row['ID'] . "</td><td>" . $row['NAME'] . "</td><td>" . $row['EVENT_DATE'] . "</td><td>" . $row['START_TIME'] . "</td><td>" . $row['END_TIME'] . "</td></tr>"; //or just use "echo $row[0]"
//        echo $row[0];
    }

    echo "</table>";
}

?>

<body>

<div id="container">

    <!-- HEADER -->
    <div onclick="location.href='../aquarium-index.php'" id="header">
        <p>UBC Aquarium</p>
    </div>

    <!-- NAVIGATION -->
    <div id="nav">
        <div id="user_container">
            <img src="../dolphin-for-index.jpg" style="width:100px;height:100px;"/>
            <p>&nbsp;</p>
        </div>
        <div onclick="location.href='./animal-page.php'" class="nav-item">
            <p>ANIMALS</p>
        </div>
        <div onclick="location.href='./schedule-page.php'" class="nav-item">
            <p>SCHEDULES</p>
        </div>
        <div onclick="location.href='./event-page.php'" class="nav-item">
            <p>EVENTS</p>
        </div>
        <div onclick="location.href='./employee-page.php'" class="nav-item">
            <p>EMPLOYEES</p>
        </div>
        <div onclick="location.href='./checkup-page.php'" class="nav-item">
            <p>CHECKUPS</p>
        </div>
    </div>

    <!-- CONTENT -->
    <div id="content">

        <div id="events-container">

            <div id="create-event">

                <h2>Create Event</h2>
                <form method="POST" action="event-page.php"> <!--refresh page when submitted-->
                    <input type="hidden" id="insertEvent" name="insertEvent">
                    Event Name: <input type="text" name="eventName"> <br/><br/>
                    Date: <input type="date" name="eventDate"> <br/><br/>
                    Start Time: <input type="time" name="startTime" step="1"> <br/><br/>
                    End Time: <input type="time" name="endTime" step="1"> <br/><br/>

                    <input type="submit" value="Insert" name="insertSubmit">

                </form>

                <form>

                    <?php

                    if (array_key_exists('insertSubmit', $_POST)) {

                        $maxId = $manager->executePlainSQL("SELECT MAX(ID) FROM Event");
                        $maxId = OCI_Fetch_Array($maxId, OCI_BOTH);
                        $maxId = $maxId[0];
                        if (is_nan($maxId) || $maxId === 0) {
                            $maxId = 0;
                        } else {
                            $maxId++;
                        }

                        $tmpName = $_POST["eventName"];
                        $tmpDate = $_POST["eventDate"];
                        $tmpStartTime = $_POST["startTime"];
                        $tmpEndTime = $_POST["endTime"];


                        //$manager->executeBoundSQL("INSERT INTO Event Values (:tmpID, ':tmpName', TO_DATE(':tmpDate', 'YYYY-MM-DD'), TO_TIMESTAMP(':tmpStartTime', 'HH24:MI:SS'),TO_TIMESTAMP(':tmpStartTime', 'HH24:MI:SS'))", $alltuples);*/
                        //$manager->executePlainSQL("INSERT INTO Event(ID, name, event_date, start_time, end_time) Values (89, 'Birthday', TO_DATE('2021-12-12', 'YYYY-MM-DD'), TO_TIMESTAMP('12:12:35', 'HH24:MI:SS'), TO_TIMESTAMP('13:20:50', 'HH24:MI:SS'))");
                        //$manager->executePlainSQL("INSERT INTO Event(ID, name, event_date, start_time, end_time) Values ($maxId, '$tmpName', TO_DATE('$tmpDate', 'YYYY-MM-DD'), TO_TIMESTAMP('$tmpStartTime', 'HH24:MI:SS'), TO_TIMESTAMP($tmpEndTime, 'HH24:MI:SS'))");
                        //$manager->executePlainSQL("INSERT INTO Event(ID, name, event_date, start_time, end_time) Values ($maxId, '".'$tmpName'."', TO_DATE('".'$tmpDate'."', 'YYYY-MM-DD'), TO_TIMESTAMP('".'$tmpStartTime'."', 'HH24:MI:SS'), TO_TIMESTAMP('".'$tmpEndTime'."', 'HH24:MI:SS'))");

                        //$result = $manager->executePlainSQL("INSERT INTO Event(ID, name, event_date, start_time, end_time) Values ($maxId, '" . $tmpName . "', TO_DATE('" . $tmpDate . "', 'YYYY-MM-DD'), TO_TIMESTAMP('" . $tmpStartTime . "', 'HH24:MI:SS'), TO_TIMESTAMP('" . $tmpEndTime . "', 'HH24:MI:SS'))");
                        $result = $manager->executePlainSQL("INSERT INTO Event(ID, name, event_date, start_time, end_time) Values ($maxId, '" . $tmpName . "', TO_DATE('" . $tmpDate . "', 'YYYY-MM-DD'), '" . $tmpStartTime . "', '" . $tmpEndTime . "')");

                        if ($result) {
                            echo "Insert Successful.<br>";
                        } else {
                            echo "Insert Unsuccessful.<br>";
                        }
                    }

                    ?>

                </form>

            </div>

            <hr/>

            <div id="update-event">

                <h2>Update Event</h2>

                <form method="POST" action="event-page.php"> <!--refresh page when submitted-->
                    <input type="hidden" id="updateEvent" name="updateEvent">
                    Event ID To Update: <input type="number" name="eventID"> <br/><br/>
                    New Event Name: <input type="text" name="newName"> <br/><br/>
                    New Date: <input type="date" name="newEventDate"> <br/><br/>
                    New Start Time: <input type="time" name="newStartTime" step="1"> <br/><br/>
                    New End Time: <input type="time" name="newEndTime" step="1"> <br/><br/>

                    <input type="submit" value="Update" name="updateSubmit">

                </form>

                <form>

                    <?php

                    if (array_key_exists('updateSubmit', $_POST)) {

                        $IDUpdate = $_POST["eventID"];
                        $newName = $_POST["newName"];
                        $newDate = $_POST["newEventDate"];
                        $newStartTime = $_POST["newStartTime"];
                        $newEndTime = $_POST["newEndTime"];

                        //$manager->executeBoundSQL("INSERT INTO Event Values (:tmpID, ':tmpName', TO_DATE(':tmpDate', 'YYYY-MM-DD'), TO_TIMESTAMP(':tmpStartTime', 'HH24:MI:SS'),TO_TIMESTAMP(':tmpStartTime', 'HH24:MI:SS'))", $alltuples);*/
                        //$manager->executePlainSQL("INSERT INTO Event(ID, name, event_date, start_time, end_time) Values (89, 'Birthday', TO_DATE('2021-12-12', 'YYYY-MM-DD'), TO_TIMESTAMP('12:12:35', 'HH24:MI:SS'), TO_TIMESTAMP('13:20:50', 'HH24:MI:SS'))");
                        //$manager->executePlainSQL("INSERT INTO Event(ID, name, event_date, start_time, end_time) Values ($maxId, '$tmpName', TO_DATE('$tmpDate', 'YYYY-MM-DD'), TO_TIMESTAMP('$tmpStartTime', 'HH24:MI:SS'), TO_TIMESTAMP($tmpEndTime, 'HH24:MI:SS'))");
                        //$manager->executePlainSQL("INSERT INTO Event(ID, name, event_date, start_time, end_time) Values ($maxId, '".'$tmpName'."', TO_DATE('".'$tmpDate'."', 'YYYY-MM-DD'), TO_TIMESTAMP('".'$tmpStartTime'."', 'HH24:MI:SS'), TO_TIMESTAMP('".'$tmpEndTime'."', 'HH24:MI:SS'))");

                        //$result = $manager->executePlainSQL("UPDATE Event SET name= '$newName' , event_date = TO_DATE('$newDate', 'YYYY-MM-DD'), start_time= TO_TIMESTAMP('$newStartTime', 'HH24:MI:SS'), end_time= TO_TIMESTAMP('$newEndTime', 'HH24:MI:SS')  WHERE ID = $IDUpdate");
                        $result = $manager->executePlainSQL("UPDATE Event SET name= '$newName' , event_date = TO_DATE('$newDate', 'YYYY-MM-DD'), start_time= '$newStartTime' , end_time= '$newEndTime'  WHERE ID = $IDUpdate");

                        if ($result) {
                            echo "Update Successful.<br>";
                        } else {
                            echo "Update Unsuccessful.<br>";
                        }
                    }

                    ?>
                </form>

            </div>

            <hr/>

            <div id="delete-event">

                <h2>Delete Event</h2>

                <form method="POST" action="event-page.php"> <!--refresh page when submitted-->
                    <input type="hidden" id="deleteEvent" name="deleteEvent">
                    Event ID To Delete: <input type="number" name="eventIDDelete"> <br/><br/>

                    <input type="submit" value="Delete" name="deleteSubmit">

                </form>

                <form>

                    <?php

                    if (array_key_exists('deleteSubmit', $_POST)) {

                        $idToDelete = $_POST["eventIDDelete"];
                        $result = $manager->executePlainSQL("DELETE FROM Event WHERE ID = $idToDelete");
                        if ($result) {
                            echo "Delete Successful.<br>";
                        } else {
                            echo "Delete Unsuccessful.<br>";
                        }
                    }

                    ?>

                </form>

            </div>

            <hr/>

            <div id="agg-event">

                <h2>Find the Date with the Maximum Average Number of Group Size of Customer Events</h2>

                <form method="GET" action="event-page.php"> <!--refresh page when submitted-->

                <input type="submit" value="Let's Find Out!" name="aggSubmit">

                </form>

                <form>

                    <?php

                    if (array_key_exists('aggSubmit', $_GET)) {

                        $result = $manager->executePlainSQL("SELECT e.event_date, avg(c.group_size) as AverageGroupSize FROM Event e, Customer_Event c WHERE e.ID = c.event_id GROUP BY e.event_date HAVING avg(c.group_size) >= ALL(SELECT avg(c1.group_size) FROM Event e1, Customer_Event c1 WHERE e1.ID = c1.event_id GROUP BY e1.event_date)");

                        echo "<table>";
                        echo "<tr><th>Event Date</th><th>Average Group Size</th></tr>";

                        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                            echo "<tr><td>" . $row['EVENT_DATE'] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
                        }

                        echo "</table>";
                    }

                    ?>

                </form>

            </div>

            <hr/>

            <div id="select-event">
                <h2>Search Event</h2>
                <form method="GET" action="event-page.php">
                    <label>Select Search Criteria:</label>
                    <select id="criteria" name="criteria" required>
                        <option value=1 selected>Event Name</option>
                        <option value=2>Event Date</option>
                        <option value=3>Both</option>
                    </select><br/><br/>
                    Event Name: <input type="text" name="nameSearch"> <br/><br/>
                    Event Date: <input type="date" name="eventDateSearch"> <br/><br/>
                    <input type="submit" value="Search" name="searchSubmit">
                </form>

                <?php

                if (array_key_exists('searchSubmit', $_GET)) {

                    $option = $_GET["criteria"];

                    if ($option == 1) {
                        $nameSearch = $_GET["nameSearch"];
                        $result = $manager->executePlainSQL("SELECT * FROM EVENT WHERE name = '$nameSearch'");
                        printResult($result);


                    } else if ($option == 2) {
                        $eventDateSearch = $_GET["eventDateSearch"];
                        $result = $manager->executePlainSQL("SELECT * FROM EVENT WHERE event_date = TO_DATE('$eventDateSearch', 'YYYY-MM-DD')");
                        printResult($result);
                    } else {
                        $nameSearch =$_GET["nameSearch"];
                        $eventDateSearch = $_GET["eventDateSearch"];
                        $result = $manager->executePlainSQL("SELECT * FROM EVENT WHERE name = '$nameSearch' AND event_date = TO_DATE('$eventDateSearch', 'YYYY-MM-DD')");
                        printResult($result);
                    }
                }


                ?>

                </form>

            </div>

        </div>

    </div>
</div>

</body>

</html>