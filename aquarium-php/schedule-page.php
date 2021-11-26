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
                <input type="submit" name="getScheduleAmount" value="Get Schedule Amount"></p>
            </form>

            <h2>Select Schedule</h2>
            <form method="GET" action="schedule-page.php"> <!--refresh page when submitted-->
                <input type="hidden" id="getScheduleIDRequest" name="getScheduleIDRequest"> 
                Search Scope: 
                    <select id="sched-type" name="sched-type">
                        <option value="Schedule">Schedules</option>
                        <option value="Feeding_Schedule">Feeding Schedules</option>
                        <option value="Cleaning_Schedule">Cleaning Schedules</option>
                    </select>
                <br>    
                Query Inputs: 
                <br>  
                <input type="checkbox" id="srch-byID" name="srch-byID"> 
                <label for="srch-byID">ID</label>
                <input type="checkbox" id="srch-byFreq" name="srch-byFreq">
                <label for="srch-byFreq">Frequency</label>
                <input type="checkbox" id="srch-byTime" name="srch-byTime">
                <label for="srch-byTime">Time</label>
                <br>
                ID: <input type="number" id="id" name="id">
                Frequency: <input type="text" id="frequency" name="frequency">
                Time: <input type="time" id="time" name="time" step="1">
                <br>
                <input type="submit" name="getSchedules" value="Get Schedule"></p>
            </form>









        </div>
    </div>
</div>

<?php
    include("aquarium_dbmanager.php");
    $instance = DataManager::Instance();


    if (isset($_GET['countScheduleRequest']) || isset($_GET['getScheduleIDRequest'])) {
        // count schedule
        if (array_key_exists('getScheduleAmount', $_GET)) {
            $result = $instance->executePlainSQL("SELECT s.frequency, COUNT(*) FROM Schedule s GROUP BY s.frequency");
            if (($row = oci_fetch_row($result)) != false) {
                echo "<p class = 'sticky'>Active Employee Schedules:" . $row[1] . "</p>";
            } 
        }
        //get a schedule by ID
        else if (array_key_exists('getSchedules' , $_GET)) {
            $scope_choice = $_GET["sched-type"];


            $select_by_id =  (!empty($_GET["srch-byID"])) ? true : false;
            $select_by_freq = (!empty($_GET["srch-byFreq"])) ? true : false;
            $select_by_time = (!empty($_GET["srch-byTime"])) ? true : false;

            $id = $_GET['id'];
            $freq = $_GET['frequency'];
            $time = $_GET['time'];

            $result = NULL;

            if (!$select_by_id && !$select_by_freq && !$select_by_time) {
                echo "<div class='sticky'> Invalid Query</div>";
            } 
            else if (!$select_by_id && !$select_by_freq && $select_by_time) {
                $result = $instance->executePlainSQL("SELECT * FROM Schedule WHERE schedule_time = '$time'");
            } 
            else if (!$select_by_id && $select_by_freq && !$select_by_time) {
                $result = $instance->executePlainSQL("SELECT * FROM Schedule WHERE frequency = '$freq'");
            } 
            else if (!$select_by_id && $select_by_freq && $select_by_time) {
                $result = $instance->executePlainSQL("SELECT * FROM Schedule WHERE frequency = '$freq' AND schedule_time = '$time'");
            } 
            else if ($select_by_id && !$select_by_freq && !$select_by_time) {
                $result = $instance->executePlainSQL("SELECT * FROM Schedule WHERE ID = $id");
                
            } 
            else if ($select_by_id && !$select_by_freq && $select_by_time) {
                $result = $instance->executePlainSQL("SELECT * FROM Schedule WHERE ID = $id AND schedule_time = '$time'"); 

            } 
            else if ($select_by_id && $select_by_freq && !$select_by_time) {
                $result = $instance->executePlainSQL("SELECT * FROM Schedule WHERE ID = $id AND frequency = '$freq'");

            } 
            else if (!$select_by_id && $select_by_freq && $select_by_time) {
                $result = $instance->executePlainSQL("SELECT * FROM Schedule WHERE ID = $id AND frequency = '$freq' AND schedule_time = '$time'");

            } 



            if ($result != NULL) {
                $output = "";
                if ($scope_choice == "Schedule") {
                    $output .= "<div class='sticky'><b>Schedules</b><br><br>";
                } 
                else if ($scope_choice == "Feeding_Schedule") {
                    $output .= "<div class='sticky'><b>Feeding Schedules</b><br><br>";
                } 
                else if ($scope_choice == "Cleaning_Schedule") {
                    $output .= "<div class='sticky'><b>Cleaning Schedules</b><br><br>";
                }
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    if ($scope_choice == "Schedule") {
                        $output .= "<b>ID:</b> " . $row[0] . "&emsp;" . "<b>Frequency:</b> " . $row[1] . "&emsp;" . "<b>Schedule Time:</b> " . $row[2] . "<br>";
                    } 
                    else if ($scope_choice == "Feeding_Schedule") {
                        if ($feed = OCI_Fetch_Array($instance->executePlainSQL("SELECT * FROM Feeding_Schedule WHERE schedule_id = $row[0]"), OCI_BOTH) ) {
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
            }


            // if ($feed = OCI_Fetch_Array($instance->executePlainSQL("SELECT * FROM Feeding_Schedule WHERE schedule_id = $row[0]"), OCI_BOTH) ) {
            //     $output .= "<b>ID:</b> " . $row[0] . "&emsp;" . "<b>Frequency:</b> " . $row[1] . "&emsp;" . "<b>Schedule Time:</b> " . $row[2] . "&emsp;" . "<b>Food Type:</b> " . $feed[1] . "<br>" . "</div>";
            // }
            // //if is a cleaning schedule
            // else if ($cleaning = OCI_Fetch_Array($instance->executePlainSQL("SELECT * FROM Cleaning_Schedule WHERE schedule_id = $row[0]"), OCI_BOTH)) {
            //     $output .= "<b>ID:</b> " . $row[0] . "&emsp;" . "<b>Frequency:</b> " . $row[1] . "&emsp;" . "<b>Schedule Time:</b> " . $row[2] . "&emsp;" . "<b>Enclosure ID:</b> " . $cleaning[1] . "<br>" . "</div>";
            // }
            // //if is generic schedule
            // else {
            //     $output .= "<b>ID:</b> " . $row[0] . "&emsp;" . "<b>Frequency:</b> " . $row[1] . "&emsp;" . "<b>Schedule Time:</b> " . $row[2] . "<br>" . "</div>";
            // }

            // $result = $instance->executePlainSQL("SELECT * FROM Schedule WHERE ID = $id");
            // if ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                //if is a feeding schedule
                // if ($feed = OCI_Fetch_Array($instance->executePlainSQL("SELECT * FROM Feeding_Schedule WHERE schedule_id = $id"), OCI_BOTH) ) {
                //     echo "<div class='sticky'>" . "<b>Feeding Schedule</b>" ."<br>" . "ID: " . $row[0] . "<br>" . "Frequency: " . $row[1] . "<br>" . "Schedule Time: " . $row[2] . "<br>" . "Food Type: " . $feed[1] . "</div>";
                // }
                // //if is a cleaning schedule
                // else if ($cleaning = OCI_Fetch_Array($instance->executePlainSQL("SELECT * FROM Cleaning_Schedule WHERE schedule_id = $id"), OCI_BOTH)) {
                //     echo "<div class='sticky'>" . "<b>Cleaning Schedule</b>" ."<br>" . "ID: " . $row[0] . "<br>" . "Frequency: " . $row[1] . "<br>" . "Schedule Time: " . $row[2] . "<br>" . "Enclosure ID: " . $cleaning[1] .  "</div>";
                // }
                // //if is generic schedule
                // else {
                //     echo "<div class='sticky'>" . "<b>Schedule</b>" ."<br>" . "ID: " . $row[0] . "<br>" . "Frequency: " . $row[1] . "<br>" . "Shedule Time: " . $row[2] .  "</div>";
                // }
            // } else {
            //     echo "<p class = 'sticky'> No schedule by that ID found.</p>";
            // }


        }






    }
?>

</body>

</html>
