<html>

<head>

    <link rel="stylesheet" type="text/css" href="../aquarium-index.css">
   <!-- <link rel="stylesheet" type="text/css" href="./aquarium-css/event-page.css"> -->



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
        <div onclick="location.href='./aquarium-php/animal-page.php'" class="nav-item">
            <p>ANIMALS</p>
        </div>
        <div onclick="location.href='./aquarium-php/schedule-page.php'" class="nav-item">
            <p>SCHEDULES</p>
        </div>
        <div onclick="location.href='./aquarium-php/event-page.php'" class="nav-item">
            <p>EVENTS</p>
        </div>
        <div onclick="location.href='./aquarium-php/employee-page.php'" class="nav-item">
            <p>EMPLOYEES</p>
        </div>
        <div onclick="location.href='./aquarium-php/checkup-page.php'" class="nav-item">
            <p>CHECKUPS</p>
        </div>
    </div>

    <!-- CONTENT -->
    <div id="content">

    <div id="events-container">

    <h2>Create Event</h2>
        <form method="POST" action="event-page.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertEvent" name="insertEvent">
            Event Name: <input type="text" name="eventName"> <br /><br />
            Date: <input type="date" name="eventDate"> <br /><br />
            Start Time: <input type="time" name="startTime"> <br /><br />
            End Time: <input type="time" name="endTime"> <br /><br />

            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />

    <h2>Update Event</h2>

        <form method="POST" action="event-page.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateEvent" name="updateEvent">
            Event ID To Update: <input type="text" name="eventID"> <br /><br />
            New Event Name: <input type="text" name="newName"> <br /><br />
            New Date: <input type="date" name="newEventDate"> <br /><br />
            New Start Time: <input type="time" name="newStartTime"> <br /><br />
            New End Time: <input type="time" name="newEndTime"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />

    <h2>Delete Event</h2>

        <form method="POST" action="event-page.php"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteEvent" name="deleteEvent">
            Event ID To Delete: <input type="text" name="eventID"> <br /><br />

            <input type="submit" value="Delete" name="deleteSubmit"></p>
        </form>

    </div>
</div>

</body>

</html>

<?php

?> 

