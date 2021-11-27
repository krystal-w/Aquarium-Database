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


                <form>
                    <?php









                    ?>


                </form>


            </div>
        </div>
    </div>



</body>

</html>

