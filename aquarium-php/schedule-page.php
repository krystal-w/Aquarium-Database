<html lang="en">

<head>

    <link rel="stylesheet" type="text/css" href="../aquarium-index.css">
    <!-- <link rel="stylesheet" type="text/css" href="../aquarium-css/event-page.css"> -->
    <title>Schedules</title>
    <style>
        table, th, td {
            border: 1px solid black;
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
    echo "<tr><th>Event ID</th><th>Frequency</th><th>Schedule Time</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row['ID'] . "</td><td>" . $row['FREQUENCY'] . "</td><td>" . $row['SCHEDULE_TIME'] . "</td></tr>"; //or just use "echo $row[0]"
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

        <div id="schedule-container">


            <div id="count-schedules">


                <h2>Total Number of Active Schedules</h2><br/>
                <form method="GET" action="schedule-page.php"> <!--refresh page when submitted-->
                    <input type="hidden" id="countScheduleRequest" name="countScheduleRequest">
                    <input type="submit" name="getScheduleAmount" value="Get Schedule Amount"></p>
                </form>

                <form>
                    <?php

                    if (array_key_exists('getScheduleAmount', $_GET)) {
                        $result = $manager->executePlainSQL("SELECT s.frequency, COUNT(*) FROM Schedule s GROUP BY s.frequency");
                        //printResult($result);
                        echo "<table>";
                        echo "<tr><th>Frequency Type</th><th>Number of Schedules</th></tr>";

                        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
                            //        echo $row[0];
                        }

                        echo "</table>";

                    }

                    ?>
                </form>


            </div>

            <hr/>

            <div id="select-schedules">

                <h2>Select Schedule</h2>
                <form method="GET" action="schedule-page.php"> <!--refresh page when submitted-->
                    <input type="hidden" id="getScheduleIDRequest" name="getScheduleIDRequest">
                    Search Scope:
                    <select id="sched-type" name="sched-type">
                        <option value="Schedule">Schedules</option>
                        <option value="Feeding_Schedule">Feeding Schedules</option>
                        <option value="Cleaning_Schedule">Cleaning Schedules</option>
                    </select>
                    <br><br/>
                    Query Inputs:
                    <br>
                    <input type="checkbox" id="srch-byID" name="srch-byID">
                    <label for="srch-byID">ID</label><br/>
                    <input type="checkbox" id="srch-byFreq" name="srch-byFreq">
                    <label for="srch-byFreq">Frequency</label><br/>
                    <input type="checkbox" id="srch-byTime" name="srch-byTime">
                    <label for="srch-byTime">Time</label><br/>
                    <br>
                    ID: <input type="number" id="id" name="id"><br/><br/>
                    Frequency: <input type="text" id="frequency" name="frequency"><br/><br/>
                    Time: <input type="time" id="time" name="time" step="1"><br/>
                    <br>
                    <input type="submit" name="getSchedules" value="Get Schedule"></p>
                </form>

                <form>
                    <?php

                    if (array_key_exists('getSchedules', $_GET)) {
                        $scope_choice = $_GET["sched-type"];

                        $select_by_id = (!empty($_GET["srch-byID"])) ? true : false;
                        $select_by_freq = (!empty($_GET["srch-byFreq"])) ? true : false;
                        $select_by_time = (!empty($_GET["srch-byTime"])) ? true : false;

                        $id = $_GET['id'];
                        $freq = $_GET['frequency'];
                        $time = $_GET['time'];

                        $result = NULL;

                        {if (!$select_by_id && !$select_by_freq && !$select_by_time) {
                            // do nothing

                        } else if (!$select_by_id && !$select_by_freq && $select_by_time) {

                            if ($scope_choice == "Schedule") {
                                $result = $manager->executePlainSQL("SELECT * FROM Schedule WHERE schedule_time = '$time'");
                            } else if ($scope_choice == "Feeding_Schedule") {
                                $result = $manager->executePlainSQL("SELECT s.ID, s.frequency, s.schedule_time FROM Feeding_Schedule f, Schedule s WHERE f.schedule_id = s.ID AND s.schedule_time = '$time'");
                            } else {
                                $result = $manager->executePlainSQL("SELECT s.ID, s.frequency, s.schedule_time FROM Cleaning_Schedule c, Schedule s WHERE c.schedule_id = s.ID AND s.schedule_time = '$time'");
                            }

                        } else if (!$select_by_id && $select_by_freq && !$select_by_time) {

                            if ($scope_choice == "Schedule") {
                                $result = $manager->executePlainSQL("SELECT * FROM Schedule WHERE frequency = '$freq'");
                            } else if ($scope_choice == "Feeding_Schedule") {
                                $result = $manager->executePlainSQL("SELECT s.ID, s.frequency , s.schedule_time FROM Feeding_Schedule f, Schedule s WHERE f.schedule_id = s.ID AND s.frequency = '$freq'");
                            } else {
                                $result = $manager->executePlainSQL("SELECT s.ID, s.frequency , s.schedule_time FROM Cleaning_Schedule c, Schedule s WHERE c.schedule_id = s.ID AND s.frequency = '$freq'");
                            }

                        } else if (!$select_by_id && $select_by_freq && $select_by_time) {

                            if ($scope_choice == "Schedule") {
                                $result = $manager->executePlainSQL("SELECT * FROM Schedule WHERE frequency = '$freq' AND schedule_time = '$time'");
                            } else if ($scope_choice == "Feeding_Schedule") {
                                $result = $manager->executePlainSQL("SELECT s.ID, s.frequency , s.schedule_time FROM Feeding_Schedule f, Schedule s WHERE f.schedule_id = s.ID AND s.frequency = '$freq' AND s.schedule_time = '$time'");
                            } else {
                                $result = $manager->executePlainSQL("SELECT s.ID, s.frequency , s.schedule_time FROM Cleaning_Schedule c, Schedule s WHERE c.schedule_id = s.ID AND s.frequency = '$freq' AND s.schedule_time = '$time'");
                            }

                        } else if ($select_by_id && !$select_by_freq && !$select_by_time) {

                            if ($scope_choice == "Schedule") {
                                $result = $manager->executePlainSQL("SELECT * FROM Schedule WHERE ID = $id");

                            } else if ($scope_choice == "Feeding_Schedule") {
                                $result = $manager->executePlainSQL("SELECT s.ID, s.frequency , s.schedule_time FROM Feeding_Schedule f, Schedule s WHERE f.schedule_id = s.ID AND ID = $id");
                            } else {
                                $result = $manager->executePlainSQL("SELECT s.ID, s.frequency , s.schedule_time FROM Cleaning_Schedule c, Schedule s WHERE c.schedule_id = s.ID AND ID = $id");
                            }

                        } else if ($select_by_id && !$select_by_freq && $select_by_time) {

                            if ($scope_choice == "Schedule") {
                                $result = $manager->executePlainSQL("SELECT * FROM Schedule WHERE ID = $id AND schedule_time = '$time'");
                            } else if ($scope_choice == "Feeding_Schedule") {
                                $result = $manager->executePlainSQL("SELECT s.ID, s.frequency , s.schedule_time FROM Feeding_Schedule f, Schedule s WHERE f.schedule_id = s.ID AND ID = $id AND schedule_time = '$time'");
                            } else {
                                $result = $manager->executePlainSQL("SELECT s.ID, s.frequency , s.schedule_time FROM Cleaning_Schedule c, Schedule s WHERE c.schedule_id = s.ID AND ID = $id AND schedule_time = '$time'");
                            }

                        } else if ($select_by_id && $select_by_freq && !$select_by_time) {

                            if ($scope_choice == "Schedule") {
                                $result = $manager->executePlainSQL("SELECT * FROM Schedule WHERE ID = $id AND frequency = '$freq'");
                            } else if ($scope_choice == "Feeding_Schedule") {
                                $result = $manager->executePlainSQL("SELECT s.ID, s.frequency , s.schedule_time FROM Feeding_Schedule f, Schedule s WHERE f.schedule_id = s.ID AND ID = $id AND frequency = '$freq'");
                            } else {
                                $result = $manager->executePlainSQL("SELECT s.ID, s.frequency , s.schedule_time FROM Cleaning_Schedule c, Schedule s WHERE c.schedule_id = s.ID AND ID = $id AND frequency = '$freq'");
                            }

                        } else if ($select_by_id && $select_by_freq && $select_by_time) {

                            if ($scope_choice == "Schedule") {
                                $result = $manager->executePlainSQL("SELECT * FROM Schedule WHERE ID = $id AND frequency = '$freq' AND schedule_time = '$time'");
                            } else if ($scope_choice == "Feeding_Schedule") {
                                $result = $manager->executePlainSQL("SELECT s.ID, s.frequency , s.schedule_time FROM Feeding_Schedule f, Schedule s WHERE f.schedule_id = s.ID AND ID = $id AND frequency = '$freq' AND schedule_time = '$time'");
                            } else {
                                $result = $manager->executePlainSQL("SELECT s.ID, s.frequency , s.schedule_time FROM Cleaning_Schedule c, Schedule s WHERE c.schedule_id = s.ID AND ID = $id AND frequency = '$freq' AND schedule_time = '$time'");
                            }

                        }}

                        printResult($result);


                        /*if ($scope_choice == "Schedule") {
                            echo "<table>";
                            echo "<tr><th>Event ID</th><th>Frequency</th><th>Schdule Time</th></tr>";

                            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                                echo "<tr><td>" . $row['ID'] . "</td><td>" . $row['FREQUENCY'] . "</td><td>" . $row['SCHEDULE_TIME'] . "</td></tr>"; //or just use "echo $row[0]"
                        //        echo $row[0];
                            }

                            echo "</table>";

                        } else if ($scope_choice == "Feeding_Schedule") {
                            echo "<table>";
                            echo "<tr><th>Event ID</th><th>Frequency</th><th>Schdule Time</th></tr>";

                            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                                echo "<tr><td>" . $row['ID'] . "</td><td>" . $row['FREQUENCY'] . "</td><td>" . $row['SCHEDULE_TIME'] . "</td></tr>"; //or just use "echo $row[0]"
                        //        echo $row[0];
                            }

                            echo "</table>";

                        }







                        /*if ($result != NULL) {
                            $output = "";
                            if ($scope_choice == "Schedule") {
                                $output .= "<div class='sticky'><b>Schedules</b><br><br>";

                            } else if ($scope_choice == "Feeding_Schedule") {
                                $output .= "<div class='sticky'><b>Feeding Schedules</b><br><br>";

                            } else if ($scope_choice == "Cleaning_Schedule") {
                                $output .= "<div class='sticky'><b>Cleaning Schedules</b><br><br>";
                            }
                            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                                if ($scope_choice == "Schedule") {
                                    $output .= "<b>ID:</b> " . $row[0] . "&emsp;" . "<b>Frequency:</b> " . $row[1] . "&emsp;" . "<b>Schedule Time:</b> " . $row[2] . "<br>";
                                } else if ($scope_choice == "Feeding_Schedule") {
                                    if ($feed = OCI_Fetch_Array($instance->executePlainSQL("SELECT * FROM Feeding_Schedule WHERE schedule_id = $row[0]"), OCI_BOTH)) {
                                        $output .= "<b>ID:</b> " . $row[0] . "&emsp;" . "<b>Frequency:</b> " . $row[1] . "&emsp;" . "<b>Schedule Time:</b> " . $row[2] . "&emsp;" . "<b>Food Type:</b> " . $feed[1] . "<br>";
                                    }
                                } else if ($scope_choice == "Cleaning_Schedule") {
                                    if ($cleaning = OCI_Fetch_Array($instance->executePlainSQL("SELECT * FROM Cleaning_Schedule WHERE schedule_id = $row[0]"), OCI_BOTH)) {
                                        $output .= "<b>ID:</b> " . $row[0] . "&emsp;" . "<b>Frequency:</b> " . $row[1] . "&emsp;" . "<b>Schedule Time:</b> " . $row[2] . "&emsp;" . "<b>Enclosure ID:</b> " . $cleaning[1] . "<br>";
                                    }
                                }
                            }
                            $output .= "</div>";
                            echo $output;
                        }*/

                    }


                    ?>


                </form>


            </div>
        </div>
    </div>



</body>

</html>

