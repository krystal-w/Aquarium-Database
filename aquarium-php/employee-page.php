<html lang="en">

<head>

    <link rel="stylesheet" type="text/css" href="../aquarium-css/employee-page.css">
    <title>Employees</title>

</head>

<?php
ini_set('session.save_path', getcwd() . "/../../../public_html_sessions");
$start = session_start();

include './aquarium_dbmanager.php';
$manager = DataManager::Instance();

function printEmployeeResults($result) {
    echo "<table>";
    echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Phone #</th><th>Email</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row['ID'] . "</td><td>" . $row['FIRST_NAME'] . "</td><td>" . $row['LAST_NAME'] . "</td><td>" . $row['PHONE#'] . "</td><td>" . $row['EMAIL'] . "</td></tr>";
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
        <div id="employee-container">
            <div id="search-employee">
                <h2>Search Employees</h2>
                <form method="get" action="employee-page.php">
                    <label for="field1">Enter search criteria:</label><br>
                    <select name="field1" id="field1">
                        <option disabled selected value>--Select--</option>
                        <option value="ID">ID</option>
                        <option value="first_name">First Name</option>
                        <option value="last_name">Last Name</option>
                        <option value="phone#">Phone #</option>
                        <option value="email">Email</option>
                    </select>
                    <label for="val1"></label>
                    <input type="text" id="val1" name="val1"><br><br>
                    <label for="field2"></label>
                    <select name="field2" id="field2">
                        <option disabled selected value>--Select--</option>
                        <option value="first_name">First Name</option>
                        <option value="last_name">Last Name</option>
                        <option value="phone#">Phone #</option>
                        <option value="email">Email</option>
                    </select>
                    <label for="val2"></label>
                    <input type="text" id="val2" name="val2"><br><br>
                    <input type="submit" value="Search">
                </form>
                <form>
                    <?php
                    if (isset($_GET['field1'])) {
                        $field1 = $_GET['field1'];
                        $val1 = $_GET['val1'];
                        if ($field1 == 'first_name' || $field1 == 'last_name' || $field1 == 'email') {
                            $val1 = "'".$val1."'";
                        }
                        $field2 = $_GET['field2'];
                        $val2 = $_GET['val2'];
                        if ($field2 == 'first_name' || $field2 == 'last_name' || $field2 == 'email') {
                            $val2 = "'".$val2."'";
                        }
                        $result = $manager->executePlainSQL("SELECT * FROM Employee WHERE $field1 = $val1 AND $field2 = $val2");
                        printEmployeeResults($result);
                    }
                    ?>
                </form>
            </div>

            <hr/>

            <div id="employee-division">
                <h2>Find Employees Who Are Leading All Events</h2>
                <form method="get" action="employee-page.php">
                    <input type="hidden" id="findAll" name="findAll" value="findAll">
                    <input type="submit" value="Search">
                </form>
                <form>
                    <?php
                    if (isset($_GET['findAll'])) {
                        $result = $manager->executePlainSQL("SELECT *
                                                                    FROM Employee e
                                                                    WHERE NOT EXISTS ((SELECT ev.ID as event_id
                                                                                        FROM Event ev)
                                                                                        MINUS
                                                                                        (SELECT l.event_id as event_id
                                                                                        FROM Leads l
                                                                                        WHERE l.employee_id = e.ID))");
                        printEmployeeResults($result);
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>

</body>

</html>

