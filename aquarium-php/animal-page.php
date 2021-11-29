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
    echo "<tr><th>Animal ID</th><th>Name</th><th>Enclosure ID</th><th>Group</th><th>Species</th><th>Health</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] ."</td><td>". $row[3]."</td><td>". $row[4]."</td><td>". $row[5]."</td></tr>"; //or just use "echo $row[0]"
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


            <div id="select-animals">


                <h2>Search For an Animal</h2><br/>
                <form method="GET" action="animal-page.php"> <!--refresh page when submitted-->
                    <input type="hidden" id="AnimalSearchRequest" name="AnimalSearchRequest">
                    Search Scope:
                    <select id="animal-type" name="animal-type">
                        <option value="Animal">Animals</option>
                        <option value="Aquatic_Animal">Aquatic Animals</option>
                        <option value="Land_Animal">Land Animals</option>
                    </select>
                    <br><br/>
                    <br>
                    Primary Query Inputs
                    <select id="primary-input-type" name="primary-input-type">
                        <option value="srch-byID">Animal ID</option>
                        <option value="srch-byEncID">Enclosure ID</option>
                    </select>
                    <input type="number" id="primary-input" name="primary-input">
                    <br>
                    Secondary Query Inputs
                    <select id="secondary-input-type" name="secondary-input-type">
                        <option value="srch-byAnimalGroup">Animal Group</option>
                        <option value="srch-bySpecies">Species</option>
                        <option value="srch-byHealth">Health</option>
                        <option value="srch-byName">Name</option>
                    </select>
                    <input type="text" id="secondary-input" name="secondary-input">
                    <br>

                    <input type="submit" name="seachForAnimal" value="Seach For Animal(s)"></p>
                </form>

                <form>
                    <?php
                    if (array_key_exists('seachForAnimal', $_GET)) {
                        $scope_choice = $_GET["animal-type"];

                        $primary_in_type =  $_GET["primary-input-type"];
                        $secondary_in_type =  $_GET["secondary-input-type"];

                        $primary_in =  $_GET["primary-input"];
                        $secondary_in =  $_GET["secondary-input"];

                        $primary_in_state = (!empty($primary_in)) ? true : false;
                        $secondary_in_state = (!empty($secondary_in)) ? true : false;
                        
                        $query = NULL;

                        if ($primary_in_state) {
                            if ($primary_in_type == "srch-byID") {
                                if ($secondary_in_state) {
                                    if ($secondary_in_type == "srch-byAnimalGroup") {
                                        if ($scope_choice == "Land_Animal") {
                                            $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Land_Animal la WHERE a.ID = la.animal_id AND a.animal_group = '$secondary_in' AND a.ID = $primary_in");
                                        } else if ($scope_choice == "Aquatic_Animal") {
                                            $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Aquatic_Animal aa WHERE a.ID = aa.animal_id AND a.animal_group = '$secondary_in' AND a.ID = $primary_in");
                                        } else {
                                            $query = $manager->executePlainSQL("SELECT * FROM $scope_choice WHERE ID = $primary_in AND animal_group = '$secondary_in'");
                                        }
                                    }
                                    else if ($secondary_in_type == "srch-bySpecies") {
                                        if ($scope_choice == "Land_Animal") {
                                            $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Land_Animal la WHERE a.ID = la.animal_id AND a.species = '$secondary_in' AND a.ID = $primary_in");
                                        } else if ($scope_choice == "Aquatic_Animal") {
                                            $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Aquatic_Animal aa WHERE a.ID = aa.animal_id AND a.species = '$secondary_in' AND a.ID = $primary_in");
                                        } else {
                                            $query = $manager->executePlainSQL("SELECT * FROM $scope_choice WHERE ID = $primary_in AND species = '$secondary_in'");
                                        }
                                    }
                                    else if ($secondary_in_type == "srch-byHealth") {
                                        if ($scope_choice == "Land_Animal") {
                                            $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Land_Animal la WHERE a.ID = la.animal_id AND a.health = '$secondary_in' AND a.ID = $primary_in");
                                        } else if ($scope_choice == "Aquatic_Animal") {
                                            $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Aquatic_Animal aa WHERE a.ID = aa.animal_id AND a.health = '$secondary_in' AND a.ID = $primary_in");
                                        } else {
                                            $query = $manager->executePlainSQL("SELECT * FROM $scope_choice WHERE ID = $primary_in AND health = '$secondary_in'");
                                        }
                                    }
                                    else if ($secondary_in_type == "srch-byName") {
                                        if ($scope_choice == "Land_Animal") {
                                            $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Land_Animal la WHERE a.ID = la.animal_id AND a.name = '$secondary_in' AND a.ID = $primary_in");
                                        } else if ($scope_choice == "Aquatic_Animal") {
                                            $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Aquatic_Animal aa WHERE a.ID = aa.animal_id AND a.name = '$secondary_in' AND a.ID = $primary_in");
                                        } else {
                                            $query = $manager->executePlainSQL("SELECT * FROM $scope_choice WHERE ID = $primary_in AND name = '$secondary_in'");
                                        }
                                    }
                                } else {
                                    $id_alias = get_animal_id_alias($scope_choice);
                                    $query = $manager->executePlainSQL("SELECT * FROM $scope_choice WHERE $id_alias = $primary_in");
                                }
                            } 
                            else if ($primary_in_type == "srch-byEncID") {
                                if ($secondary_in_state) {
                                    if ($secondary_in_type == "srch-byAnimalGroup") {
                                        if ($scope_choice == "Land_Animal") {
                                            $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Land_Animal la WHERE a.ID = la.animal_id AND a.animal_group = '$secondary_in' AND a.enclosure_id = $primary_in");             
                                        } else if ($scope_choice == "Aquatic_Animal") {
                                            $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Aquatic_Animal aa WHERE a.ID = aa.animal_id AND a.animal_group = '$secondary_in' AND a.enclosure_id = $primary_in");      
                                        } else {
                                            $query = $manager->executePlainSQL("SELECT * FROM Animal WHERE enclosure_id = $primary_in AND animal_group = '$secondary_in'");
                                        }
                                    }
                                    else if ($secondary_in_type == "srch-bySpecies") {
                                        if ($scope_choice == "Land_Animal") {
                                            $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Land_Animal la WHERE a.ID = la.animal_id AND a.species = '$secondary_in' AND a.enclosure_id = $primary_in");             
                                        } else if ($scope_choice == "Aquatic_Animal") {
                                            $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Aquatic_Animal aa WHERE a.ID = aa.animal_id AND a.species = '$secondary_in' AND a.enclosure_id = $primary_in");      
                                        } else {
                                            $query = $manager->executePlainSQL("SELECT * FROM Animal WHERE enclosure_id = $primary_in AND species = '$secondary_in'");
                                        }
                                    }
                                    else if ($secondary_in_type == "srch-byHealth") {
                                        if ($scope_choice == "Land_Animal") {
                                            $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Land_Animal la WHERE a.ID = la.animal_id AND a.health = '$secondary_in' AND a.enclosure_id = $primary_in");             
                                        } else if ($scope_choice == "Aquatic_Animal") {
                                            $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Aquatic_Animal aa WHERE a.ID = aa.animal_id AND a.health = '$secondary_in' AND a.enclosure_id = $primary_in");      
                                        } else {
                                            $query = $manager->executePlainSQL("SELECT * FROM Animal WHERE enclosure_id = $primary_in AND health = '$secondary_in'");
                                        }
                                    }
                                    else if ($secondary_in_type == "srch-byName") {
                                        if ($scope_choice == "Land_Animal") {
                                            $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Land_Animal la WHERE a.ID = la.animal_id AND a.name = '$secondary_in' AND a.enclosure_id = $primary_in");             
                                        } else if ($scope_choice == "Aquatic_Animal") {
                                            $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Aquatic_Animal aa WHERE a.ID = aa.animal_id AND a.name = '$secondary_in' AND a.enclosure_id = $primary_in");      
                                        } else {
                                            $query = $manager->executePlainSQL("SELECT * FROM Animal WHERE enclosure_id = $primary_in AND name = '$secondary_in'");
                                        }
                                    }
                                } else {
                                    if ($scope_choice == "Land_Animal") {
                                        $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Land_Animal la WHERE a.enclosure_id = $primary_in AND a.ID = la.animal_id");
                                    } else if ($scope_choice == "Aquatic_Animal") {
                                        $query = $manager->executePlainSQL("SELECT a.ID, a.name, a.enclosure_id, a.animal_group, a.species, a.health FROM Animal a, Aquatic_Animal aa WHERE a.enclosure_id = $primary_in AND a.ID = aa.animal_id");
                                    } else {
                                        $query = $manager->executePlainSQL("SELECT * FROM Animal WHERE enclosure_id = $primary_in");
                                    }
                                }

                            }
                        } else {
                            echo "Primary Input can not be empty.";
                        }

                        if ($query != NULL) {
                            printResult($query);
                        }

                    }

                    function get_animal_id_alias($in) {
                        if ($in == "Animal") {
                            return "ID";
                        } else {
                            return "animal_id";
                        }


                    }
                    ?>
                </form>


            </div>

            <hr/>
        </div>
    </div>



</body>

</html>

