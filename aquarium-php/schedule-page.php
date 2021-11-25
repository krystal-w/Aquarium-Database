<html>

<head>

    <link rel="stylesheet" type="text/css" href="../aquarium-index.css">

</head>

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
            <h2>Total Number of Active Schedules</h2>
            <form method="GET" action="schedule-page.php"> <!--refresh page when submitted-->
                <input type="hidden" id="countScheduleRequest" name="countScheduleRequest">
                <input type="submit" name="getScheduleAmount"></p>
            </form>









        </div>
    </div>
</div>

</body>

</html>

<?php
    include "aquarium_dbmanager.php";

    if (isset($_GET['countScheduleRequest'])) {
        if (connectToDB()) {
            // count schedule
            if (array_key_exists('getScheduleAmount', $_GET)) {
                global $db_conn;

                $result = executePlainSQL("SELECT s.frequency, COUNT(*) FROM Schedule s GROUP BY s.frequency");
                if (($row = oci_fetch_row($result)) != false) {
                    echo "<br> Active Employee Schedules: " . $row[0] . "<br>";
                }
            }
            disconnectFromDB();
        }




    }


?>