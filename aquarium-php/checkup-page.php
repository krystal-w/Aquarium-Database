<html lang="en">

<head>

    <link rel="stylesheet" type="text/css" href="../aquarium-css/checkup-page.css">
    <title>Checkups</title>

</head>

<?php
ini_set('session.save_path', getcwd() . "/../../../public_html_sessions");
$start = session_start();

include './aquarium_dbmanager.php';
$manager = DataManager::Instance();

function printPriorityResult($result) { //prints results from a select statement
    echo "<table>";
    echo "<tr><th>First Name</th><th>Last Name</th><th>Specialty</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row['FIRST_NAME'] . "</td><td>" . $row['LAST_NAME'] . "</td><td>" . $row['SPECIALTY'] . "</td></tr>"; //or just use "echo $row[0]"
//        echo $row[0];
    }

    echo "</table>";
}

function printProjectionResult($result, $attributes) { //prints results from a select statement
    echo "<table>";
    $cols = "<tr>";
    if (in_array("type", $attributes)) {
        $cols .= "<th>Type</th>";
    }
    if (in_array("checkup_date", $attributes)) {
        $cols .= "<th>Date</th>";
    }
    if (in_array("checkup_time", $attributes)) {
        $cols .= "<th>Time</th>";
    }
    $cols .= "</tr>";
    echo "" . $cols . "";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        $rows = "<tr>";
        if (in_array("type", $attributes)) {
            $rows .= "<td>" . $row['TYPE'] . "</td>";
        }
        if (in_array("checkup_date", $attributes)) {
            $rows .= "<td>" . $row['CHECKUP_DATE'] . "</td>";
        }
        if (in_array("checkup_time", $attributes)) {
            $rows .= "<td>" . $row['CHECKUP_TIME'] . "</td>";
        }
        $rows.= "</tr>";
        echo "" . $rows . "";
    }
    echo "</table>";
}

function printSelectResult($result) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Biologist ID</th><th>Animal ID</th><th>Type</th><th>Priority</th><th>Date</th><th>Time</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row['ID'] . "</td><td>" . $row['BIOLOGIST_ID'] . "</td><td>" . $row['ANIMAL_ID'] . "</td><td>" . $row['TYPE'] . "</td>
        <td>" . $row['PRIORITY'] . "</td><td>" . $row['CHECKUP_DATE'] . "</td><td>" . $row['CHECKUP_TIME'] . "</td></tr>";
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
        <div id="checkup-container">
            <div id="checkup-projection">
                <h2>Search Checkups</h2>
                <form method="get" action="checkup-page.php">
                    <label>Search specific:</label><br><br>
                    <label for="field1">First search criteria:</label>
                    <select name="field1" id="field1">
                        <option disabled selected value>--Select--</option>
                        <option value="biologist_id">Biologist ID</option>
                        <option value="animal_id">Animal ID</option>
                        <option value="type">Type</option>
                    </select>
                    <label for="val1"></label>
                    <input type="text" id="val1" name="val1"><br><br>
                    <label for="field2">Second search criteria:</label>
                    <select name="field2" id="field2">
                        <option disabled selected value>--Select--</option>
                        <option value="checkup_date">Date</option>
                        <option value="checkup_time">Time</option>
                        <option value="priority">Priority</option>
                    </select>
                    <label for="val2"></label>
                    <input type="text" id="val2" name="val2"><br><br>
                    <script>
                        document.getElementById('field2').addEventListener("change", function (e) {
                            if (e.target.value === 'checkup_date') {
                                document.getElementById('val2').type = 'date';
                            } else if (e.target.value === 'checkup_time'){
                                document.getElementById('val2').type = 'time';
                                document.getElementById('val2').step = '1';
                            } else {
                                document.getElementById('val2').type = 'text'
                            }
                        });
                    </script>
                    <input type="submit" value="Search">
                </form>
                <h4>OR</h4>
                <form method="get" action="checkup-page.php">
                    <label>Find all checkup:</label>
                    <input type="checkbox" id="type" name="checkupAttributes[]" value="type">
                    <label for="type">Type</label>
                    <input type="checkbox" id="date" name="checkupAttributes[]" value="checkup_date">
                    <label for="date">Date</label>
                    <input type="checkbox" id="time" name="checkupAttributes[]" value="checkup_time">
                    <label for="time">Time</label>
                    <div class="divider"></div>
                    <input type="submit" value="Search">
                </form>
                <form>
                    <?php
                    if (isset($_GET)) {
                        if (array_key_exists('field1', $_GET)) {
                            $field1 = $_GET['field1'];
                            if ($field1 == 'type') {
                                $field1 = "c.type";
                            }
                            $val1 = $_GET['val1'];
                            $field2 = $_GET['field2'];
                            if ($field2 == 'priority') {
                                $field2 = "cp.priority";
                            }
                            $val2 = $_GET['val2'];
                            if ($field2 == 'checkup_date') {
                                $val2 = "TO_DATE('".$val2."', 'YYYY-MM-DD')";
                            } else {
                                $val2 = "'".$val2."'";
                            }
                            $result = $manager->executePlainSQL("SELECT * FROM Checkup c, Checkup_Priority cp
                                                                        WHERE $field1 = '" .$val1. "' AND $field2 = " .$val2. " AND c.type = cp.type");
                            printSelectResult($result);
                        } else if (!empty($_GET['checkupAttributes'])) {
                            $attributes = $_GET['checkupAttributes'];
                            $attr_string = "";
                            $last_el = end($attributes);
                            foreach ($attributes as $selected) {
                                if ($selected == $last_el) {
                                    $attr_string .= $selected;
                                } else {
                                    $attr_string .= $selected.",";
                                }
                            }
                            $result = $manager->executePlainSQL("SELECT $attr_string FROM Checkup");
                            printProjectionResult($result, $attributes);
                        }
                    }
                    ?>
                </form>
            </div>

            <hr/>

            <div id="priority-search">
                <h2>Find Biologists Conducting Checkups by Priority</h2>
                <form method="get" action="checkup-page.php">
                    <input type="radio" id="Low" name="checkupPriority" value="Low">
                    <label for="Low">Low</label>
                    <input type="radio" id="Medium" name="checkupPriority" value="Medium">
                    <label for="Medium">Medium</label>
                    <input type="radio" id="High" name="checkupPriority" value="High">
                    <label for="High">High</label>
                    <div class="divider"></div>
                    <input type="submit" value="Search">
                </form>
                <form>
                    <?php
                    if (isset($_GET)) {
                        if (array_key_exists('checkupPriority', $_GET)) {
                            $priority = $_GET['checkupPriority'];
                            $tuple = array(
                                ":priority" => $priority
                            );
                            $all_tuples = array(
                                $tuple
                            );
                            echo "Biologist(s) conducting $priority priority checkups:<br>";
                            $result = $manager->executeBoundSQL("SELECT e.first_name, e.last_name, b.specialty
                                                FROM Biologist b, Checkup c, Checkup_Priority cp, Employee e
                                                WHERE b.employee_id = e.id AND c.biologist_id = b.employee_id AND c.type = cp.type AND cp.priority = :priority", $all_tuples);
                            printPriorityResult($result);
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



