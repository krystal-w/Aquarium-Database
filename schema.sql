DROP TABLE Event CASCADE CONSTRAINTS;
DROP TABLE Aquarium_Event CASCADE CONSTRAINTS;
DROP TABLE Location_Size CASCADE CONSTRAINTS;
DROP TABLE Event_Ticket_Rates CASCADE CONSTRAINTS;
DROP TABLE Customer_Event CASCADE CONSTRAINTS;
DROP TABLE Event_Booking_Rates CASCADE CONSTRAINTS;
DROP TABLE Customer CASCADE CONSTRAINTS;
DROP TABLE Employee CASCADE CONSTRAINTS;
DROP TABLE Biologist CASCADE CONSTRAINTS;
DROP TABLE Department_Location CASCADE CONSTRAINTS;
DROP TABLE Checkup CASCADE CONSTRAINTS;
DROP TABLE Checkup_Priority CASCADE CONSTRAINTS;
DROP TABLE Animal CASCADE CONSTRAINTS;
DROP TABLE Aquatic_Animal CASCADE CONSTRAINTS;
DROP TABLE Land_Animal CASCADE CONSTRAINTS;
DROP TABLE Schedule CASCADE CONSTRAINTS;
DROP TABLE Feeding_Schedule CASCADE CONSTRAINTS;
DROP TABLE Cleaning_Schedule CASCADE CONSTRAINTS;
DROP TABLE Enclosure CASCADE CONSTRAINTS;
DROP TABLE Leads CASCADE CONSTRAINTS;
DROP TABLE Cares_For CASCADE CONSTRAINTS;
DROP TABLE Is_For CASCADE CONSTRAINTS;
DROP TABLE Is_Assigned CASCADE CONSTRAINTS;
DROP TABLE Participates CASCADE CONSTRAINTS;

CREATE TABLE Event
(
    ID INT, -- AUTO_INCREMENT,
    name VARCHAR(255),
    event_date DATE,
    start_time VARCHAR(255),
    end_time VARCHAR(255),
    PRIMARY KEY (ID)
);

CREATE TABLE Location_Size
(
    location VARCHAR(255),
    capacity INT,
    PRIMARY KEY (location)
);

CREATE TABLE Event_Ticket_Rates
(
    type VARCHAR(255),
    ticket_price INT,
    PRIMARY KEY (type)
);

CREATE TABLE Event_Booking_Rates
(
    group_size INT,
    cost INT,
    PRIMARY KEY (group_size)
);

CREATE TABLE Aquarium_Event
(
    event_id INT,
    type VARCHAR(255),
    location VARCHAR(255),
    PRIMARY KEY (event_id),
    FOREIGN KEY (event_id) REFERENCES Event(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (type) REFERENCES Event_Ticket_Rates(type),
--         ON UPDATE CASCADE
--         ON DELETE NO ACTION,
    FOREIGN KEY (location) REFERENCES Location_Size(location)
--         ON UPDATE CASCADE
--         ON DELETE NO ACTION
);

CREATE TABLE Customer
(
    ID INT, -- AUTO_INCREMENT,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    phone# VARCHAR(255) UNIQUE,
    email VARCHAR(255) UNIQUE,
    PRIMARY KEY (ID)
);

CREATE TABLE Customer_Event
(
    event_id INT,
    group_size INT,
    customer_id INT NOT NULL,
    PRIMARY KEY (event_id),
    FOREIGN KEY (event_id) REFERENCES Event(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (group_size) REFERENCES Event_Booking_Rates(group_size),
--         ON UPDATE CASCADE
--         ON DELETE NO ACTION,
    FOREIGN KEY (customer_id) REFERENCES Customer(ID)
--         ON UPDATE CASCADE
--         ON DELETE NO ACTION
);

CREATE TABLE Employee
(
    ID INT, -- AUTO_INCREMENT,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    phone# VARCHAR(255) UNIQUE,
    email VARCHAR(255) UNIQUE,
    PRIMARY KEY (ID)
);

CREATE TABLE Department_Location
(
    department VARCHAR(255),
    office_location VARCHAR(255),
    PRIMARY KEY (department)
);

CREATE TABLE Biologist
(
    employee_id INT,
    specialty VARCHAR(255),
    department VARCHAR(255),
    PRIMARY KEY (employee_id),
    FOREIGN KEY (employee_id) REFERENCES Employee(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (department) REFERENCES Department_Location(department)
--         ON UPDATE CASCADE
--         ON DELETE NO ACTION
);

CREATE TABLE Enclosure
(
    ID INT, -- AUTO_INCREMENT,
    type VARCHAR(255),
    temperature INT,
    PRIMARY KEY (ID)
);
--
CREATE TABLE Animal
(
    ID INT, -- AUTO_INCREMENT,
    name VARCHAR(255),
    enclosure_id INT NOT NULL,
    animal_group VARCHAR(255),
    species VARCHAR(255),
    health VARCHAR(255),
    PRIMARY KEY (ID),
    FOREIGN KEY (enclosure_id) REFERENCES Enclosure(ID)
--         ON UPDATE CASCADE
--         ON DELETE NO ACTION
);

CREATE TABLE Aquatic_Animal
(
    animal_id INT,
    water_type VARCHAR(255),
    water_temp INT,
    PRIMARY KEY (animal_id),
    FOREIGN KEY (animal_id) REFERENCES Animal(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE Land_Animal
(
    animal_id INT,
    environment VARCHAR(255),
    PRIMARY KEY (animal_id),
    FOREIGN KEY (animal_id) REFERENCES Animal(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE Checkup_Priority
(
    type VARCHAR(255),
    priority VARCHAR(255),
    PRIMARY KEY (type)
);

CREATE TABLE Checkup
(
    ID INT, -- AUTO_INCREMENT,
    type VARCHAR(255),
    checkup_date DATE,
    checkup_time VARCHAR(255),
    biologist_id INT NOT NULL,
    animal_id INT NOT NULL,
    PRIMARY KEY (ID),
    FOREIGN KEY (type) REFERENCES Checkup_Priority(type),
--         ON UPDATE CASCADE
--         ON DELETE NO ACTION,
    FOREIGN KEY (biologist_id) REFERENCES Biologist(employee_id)
--         ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (animal_id) REFERENCES Animal(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE Schedule
(
    ID INT, -- AUTO_INCREMENT,
    frequency VARCHAR(255),
    schedule_time VARCHAR(255),
    PRIMARY KEY (ID)
);

CREATE TABLE Feeding_Schedule
(
    schedule_id INT,
    food_type VARCHAR(255),
    PRIMARY KEY (schedule_id),
    FOREIGN KEY (schedule_id) REFERENCES Schedule(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE Cleaning_Schedule
(
    schedule_id INT,
    enclosure_id INT UNIQUE NOT NULL,
    PRIMARY KEY (schedule_id),
    FOREIGN KEY (schedule_id) REFERENCES Schedule(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (enclosure_id) REFERENCES Enclosure(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE Leads
(
    employee_id INT,
    event_id INT,
    PRIMARY KEY (employee_id, event_id),
    FOREIGN KEY (employee_id) REFERENCES Employee(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES Event(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE Cares_For
(
    employee_id INT,
    animal_id INT,
    PRIMARY KEY (employee_id, animal_id),
    FOREIGN KEY (employee_id) REFERENCES Employee(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (animal_id) REFERENCES Animal(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE Is_For
(
    animal_id INT,
    feeding_schedule_id INT,
    PRIMARY KEY (animal_id, feeding_schedule_id),
    FOREIGN KEY (animal_id) REFERENCES Animal(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (feeding_schedule_id) REFERENCES Feeding_Schedule(schedule_id)
--         ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE Is_Assigned
(
    employee_id INT,
    schedule_id INT,
    PRIMARY KEY (employee_id, schedule_id),
    FOREIGN KEY (employee_id) REFERENCES Employee(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (schedule_id) REFERENCES Schedule(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE Participates
(
    aquarium_event_id INT,
    animal_id INT,
    PRIMARY KEY (aquarium_event_id, animal_id),
    FOREIGN KEY (aquarium_event_id) REFERENCES Aquarium_Event(event_id)
--         ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (animal_id) REFERENCES Animal(ID)
--         ON UPDATE CASCADE
        ON DELETE CASCADE
);

INSERT INTO Event VALUES (1, 'Penguin Encounter', TO_DATE('2021-12-23', 'YYYY-MM-DD'), '16:00:00', '17:59:59');
INSERT INTO Event VALUES (2, 'Dolphin Show', TO_DATE('2021-12-23', 'YYYY-MM-DD'), '13:30:00', '13:59:59');
INSERT INTO Event VALUES (3, 'Vancouver Elementary Outreach', TO_DATE('2022-01-03', 'YYYY-MM-DD'), '13:00:00', '14:59:59');
INSERT INTO Event VALUES (4, 'UBC Biology 100 Field Trip', TO_DATE('2022-01-17', 'YYYY-MM-DD'), '10:30:00', '13:29:59');
INSERT INTO Event VALUES (5, 'Krystal''s Birthday', TO_DATE('2021-12-14', 'YYYY-MM-DD'), '13:00:00', '14:59:59');
INSERT INTO Event VALUES (6, 'Mea''s Birthday', TO_DATE('2022-01-17', 'YYYY-MM-DD'), '15:00:00', '16:59:59');
INSERT INTO Event VALUES (7, 'Volunteer Cleanup', TO_DATE('2021-11-15', 'YYYY-MM-DD'), '11:30:00', '13:59:59');
INSERT INTO Event VALUES (8, 'Marine Mammal Rescue Exhibit', TO_DATE('2022-02-02', 'YYYY-MM-DD'), '14:00:00', '15:59:59');
INSERT INTO Event VALUES (9, 'UBC Elementary Field Trip', TO_DATE('2022-02-02', 'YYYY-MM-DD'), '09:30:00', '14:59:59');
INSERT INTO Event VALUES (10, 'Virtual Aquarium Tour', TO_DATE('2021-11-15', 'YYYY-MM-DD'), '12:30:00', '13:59:59');

INSERT INTO Location_Size VALUES ('Penguin Encounter Room', 20);
INSERT INTO Location_Size VALUES ('Dolphin Tank', 150);
INSERT INTO Location_Size VALUES ('Stanley Park', 200);
INSERT INTO Location_Size VALUES ('Seal Rescue Enclosure', 40);
INSERT INTO Location_Size VALUES ('Zoom', 20);

INSERT INTO Event_Ticket_Rates VALUES ('Interactive', 40);
INSERT INTO Event_Ticket_Rates VALUES ('Entertainment', 35);
INSERT INTO Event_Ticket_Rates VALUES ('Volunteer', 0);
INSERT INTO Event_Ticket_Rates VALUES ('Educational', 20);
INSERT INTO Event_Ticket_Rates VALUES ('Virtual', 25);

INSERT INTO Event_Booking_Rates VALUES (20, 300);
INSERT INTO Event_Booking_Rates VALUES (120, 1500);
INSERT INTO Event_Booking_Rates VALUES (10, 100);
INSERT INTO Event_Booking_Rates VALUES (5, 50);
INSERT INTO Event_Booking_Rates VALUES (50, 500);

INSERT INTO Aquarium_Event VALUES (1, 'Interactive', 'Penguin Encounter Room');
INSERT INTO Aquarium_Event VALUES (2, 'Entertainment', 'Dolphin Tank');
INSERT INTO Aquarium_Event VALUES (7, 'Volunteer', 'Stanley Park');
INSERT INTO Aquarium_Event VALUES (8, 'Educational', 'Seal Rescue Enclosure');
INSERT INTO Aquarium_Event VALUES (10, 'Virtual', 'Zoom');

INSERT INTO Customer VALUES (1, 'Mea', 'Srisan', '778-888-8888', 'mea_ubc@gmail.com');
INSERT INTO Customer VALUES (2, 'John', 'Smith', '604-345-6789', 'jsmith@vancouverelementary.ca');
INSERT INTO Customer VALUES (3, 'Jane', 'Lee', '778-333-3333', 'janelee@gmail.com');
INSERT INTO Customer VALUES (4, 'Linda', 'Wang', '604-333-3333', 'l_wang@hotmail.com');
INSERT INTO Customer VALUES (5, 'Emily', 'Perry', '778-999-9999', 'e_perry@hotmail.com');

INSERT INTO Customer_Event VALUES (3, 20, 2);
INSERT INTO Customer_Event VALUES (4, 20, 1);
INSERT INTO Customer_Event VALUES (5, 10, 5);
INSERT INTO Customer_Event VALUES (6, 5, 4);
INSERT INTO Customer_Event VALUES (9, 50, 3);

INSERT INTO Employee VALUES (1, 'Kurt', 'Kaufer', '778-984-9384', 'kurt.kaufer@aquarium.ubc.ca');
INSERT INTO Employee VALUES (2, 'Alan', 'Li', '604-444-4444', 'alan.li@aquarium.ubc.ca');
INSERT INTO Employee VALUES (3, 'Dorothy', 'Kim', '604-555-5555', 'dorothy.kim@aquarium.ubc.ca');
INSERT INTO Employee VALUES (4, 'Linda', 'Poon', '778-111-1111', 'linda.poon@aquarium.ubc.ca');
INSERT INTO Employee VALUES (5, 'Samuel', 'Ericson', '604-000-0000', 'samuel.ericson@aquarium.ubc.ca');
INSERT INTO Employee VALUES (6, 'Xiao', 'Wang', '778-661-6633', 'xiao.wang@aquarium.ubc.ca');
INSERT INTO Employee VALUES (7, 'Taylor', 'Swift', '604-000-0001', 'taylor.swift@aquarium.ubc.ca');

INSERT INTO Department_Location VALUES ('Dolphin', 'Office Annex A');
INSERT INTO Department_Location VALUES ('Penguin', 'Office Annex B');
INSERT INTO Department_Location VALUES ('Fish', 'Office Annex B');
INSERT INTO Department_Location VALUES ('Conservation', 'Office Annex C');
INSERT INTO Department_Location VALUES ('Marine Genetics', 'Office Annex A');

INSERT INTO Biologist VALUES (1, 'Dolphin Physiology', 'Dolphin');
INSERT INTO Biologist VALUES (2, 'Penguin Physiology', 'Penguin');
INSERT INTO Biologist VALUES (4, 'Fish Physiology', 'Fish');
INSERT INTO Biologist VALUES (6, 'Coastline Conservation', 'Conservation');
INSERT INTO Biologist VALUES (7, 'Reproduction', 'Marine Genetics');

INSERT INTO Enclosure VALUES (1, 'Tank', 22);
INSERT INTO Enclosure VALUES (2, 'Open Air', 10);
INSERT INTO Enclosure VALUES (3, 'Tank', 15);
INSERT INTO Enclosure VALUES (4, 'Tank', 25);
INSERT INTO Enclosure VALUES (5, 'Open Air', 15);

INSERT INTO Animal VALUES (1, 'Peter', 5, 'Penguin', 'Eudyptula minor', 'Poor');
INSERT INTO Animal VALUES (2, 'Penny', 5, 'Penguin', 'Eudyptula minor', 'Pregnant');
INSERT INTO Animal VALUES (3, 'Flipper', 3, 'Dolphin', 'Lagenorhynchus obliquidens', 'In Rehab');
INSERT INTO Animal VALUES (4, 'Azula', 3, 'Dolphin', 'Lagenorhynchus obliquidens', 'Poor');
INSERT INTO Animal VALUES (5, 'Nemo', 1, 'Fish', 'Amphiprion percula', 'Healthy');
INSERT INTO Animal VALUES (6, 'Dory', 1, 'Fish', 'Paracanthurus hepatus', 'Healthy');
INSERT INTO Animal VALUES (7, 'Otto', 2, 'Otter', 'Enhydra lutris', 'Healthy');
INSERT INTO Animal VALUES (8, 'Pearl', 2, 'Otter', 'Enhydra lutris', 'Pregnant');
INSERT INTO Animal VALUES (9, 'Martin', 2, 'Otter', 'Enhydra lutris', 'Poor');
INSERT INTO Animal VALUES (10, 'Octavius', 4, 'Octopus', 'Octopus vulgaris', 'Poor');

INSERT INTO Aquatic_Animal VALUES (3, 'Salt water', 15);
INSERT INTO Aquatic_Animal VALUES (4, 'Salt water', 15);
INSERT INTO Aquatic_Animal VALUES (5, 'Salt water', 22);
INSERT INTO Aquatic_Animal VALUES (6, 'Salt water', 24);
INSERT INTO Aquatic_Animal VALUES (10, 'Salt water', 26);

INSERT INTO Land_Animal VALUES (1, 'Inshore waters around the coast and breeding islands');
INSERT INTO Land_Animal VALUES (2, 'Inshore waters around the coast and breeding islands');
INSERT INTO Land_Animal VALUES (7, 'Seas and rocky shores');
INSERT INTO Land_Animal VALUES (8, 'Seas and rocky shores');
INSERT INTO Land_Animal VALUES (9, 'Seas and rocky shores');

INSERT INTO Checkup_Priority VALUES ('Regular Checkup', 'Low');
INSERT INTO Checkup_Priority VALUES ('Heart Surgery', 'High');
INSERT INTO Checkup_Priority VALUES ('Swim Rehab', 'Medium');
INSERT INTO Checkup_Priority VALUES ('Pregnancy Checkup', 'Medium');
INSERT INTO Checkup_Priority VALUES ('Artificial Fin Attachment Surgery', 'High');

INSERT INTO Checkup VALUES (1, 'Regular Checkup', TO_DATE('2021-12-01', 'YYYY-MM-DD'), '09:30:00', 4, 5);
INSERT INTO Checkup VALUES (2, 'Heart Surgery', TO_DATE('2022-01-23', 'YYYY-MM-DD'), '11:30:00', 2, 1);
INSERT INTO Checkup VALUES (3, 'Swim Rehab', TO_DATE('2022-02-11', 'YYYY-MM-DD'), '14:00:00', 1, 3);
INSERT INTO Checkup VALUES (4, 'Pregnancy Checkup', TO_DATE('2022-02-11', 'YYYY-MM-DD'), '13:15:00', 2, 2);
INSERT INTO Checkup VALUES (5, 'Artificial Fin Attachment Surgery', TO_DATE('2022-01-23', 'YYYY-MM-DD'), '09:30:00', 1, 3);
INSERT INTO Checkup VALUES (6, 'Regular Checkup', TO_DATE('2021-12-01', 'YYYY-MM-DD'), '10:30:00', 6, 6);
INSERT INTO Checkup VALUES (7, 'Pregnancy Checkup', TO_DATE('2021-12-01', 'YYYY-MM-DD'), '02:00:00', 7, 8);
INSERT INTO Checkup VALUES (8, 'Regular Checkup', TO_DATE('2022-02-11', 'YYYY-MM-DD'), '14:15:00', 2, 1);
INSERT INTO Checkup VALUES (9, 'Heart Surgery', TO_DATE('2022-01-23', 'YYYY-MM-DD'), '15:15:00', 4, 5);

INSERT INTO Schedule VALUES (1, 'Weekly', '14:00:00');
INSERT INTO Schedule VALUES (2, 'Daily', '05:30:00');
INSERT INTO Schedule VALUES (3, 'Bi-weekly', '05:30:00');
INSERT INTO Schedule VALUES (4, 'Daily', '06:30:00');
INSERT INTO Schedule VALUES (5, 'Weekly', '06:30:00');
INSERT INTO Schedule VALUES (6, 'Daily', '06:30:00');
INSERT INTO Schedule VALUES (7, 'Weekly', '20:00:00');
INSERT INTO Schedule VALUES (8, 'Weekly', '21:00:00');
INSERT INTO Schedule VALUES (9, 'Daily', '21:30:00');
INSERT INTO Schedule VALUES (10, 'Daily', '06:30:00');
INSERT INTO Schedule VALUES (11, 'Bi-weekly', '17:00:00');
INSERT INTO Schedule VALUES (12, 'Bi-weekly', '15:00:00');
INSERT INTO Schedule VALUES (13, 'Monthly', '22:30:00');
INSERT INTO Schedule VALUES (14, 'Monthly', '12:00:00');
INSERT INTO Schedule VALUES (15, 'Monthly', '13:30:00');

INSERT INTO Feeding_Schedule VALUES (1, 'Clams');
INSERT INTO Feeding_Schedule VALUES (2, 'Feeder fish');
INSERT INTO Feeding_Schedule VALUES (3, 'Flake food');
INSERT INTO Feeding_Schedule VALUES (4, 'Feeder fish');
INSERT INTO Feeding_Schedule VALUES (5, 'Crabs');
INSERT INTO Feeding_Schedule VALUES (11, 'Clams');
INSERT INTO Feeding_Schedule VALUES (12, 'Feeder fish');
INSERT INTO Feeding_Schedule VALUES (13, 'Flake food');
INSERT INTO Feeding_Schedule VALUES (14, 'Flake food');
INSERT INTO Feeding_Schedule VALUES (15, 'Crabs');

INSERT INTO Cleaning_Schedule VALUES (6, 1);
INSERT INTO Cleaning_Schedule VALUES (7, 2);
INSERT INTO Cleaning_Schedule VALUES (8, 3);
INSERT INTO Cleaning_Schedule VALUES (9, 4);
INSERT INTO Cleaning_Schedule VALUES (10, 5);

INSERT INTO Leads VALUES (1, 3);
INSERT INTO Leads VALUES (1, 5);
INSERT INTO Leads VALUES (1, 4);
INSERT INTO Leads VALUES (1, 2);
INSERT INTO Leads VALUES (1, 1);
INSERT INTO Leads VALUES (1, 6);
INSERT INTO Leads VALUES (1, 7);
INSERT INTO Leads VALUES (1, 8);
INSERT INTO Leads VALUES (1, 9);
INSERT INTO Leads VALUES (1, 10);
INSERT INTO Leads VALUES (2, 5);
INSERT INTO Leads VALUES (4, 4);
INSERT INTO Leads VALUES (4, 8);
INSERT INTO Leads VALUES (3, 6);
INSERT INTO Leads VALUES (7, 2);
INSERT INTO Leads VALUES (4, 1);
INSERT INTO Leads VALUES (3, 9);
INSERT INTO Leads VALUES (5, 3);
INSERT INTO Leads VALUES (5, 5);
INSERT INTO Leads VALUES (5, 4);
INSERT INTO Leads VALUES (5, 2);
INSERT INTO Leads VALUES (5, 1);
INSERT INTO Leads VALUES (5, 6);
INSERT INTO Leads VALUES (5, 7);
INSERT INTO Leads VALUES (5, 8);
INSERT INTO Leads VALUES (5, 9);
INSERT INTO Leads VALUES (5, 10);
INSERT INTO Leads VALUES (6, 3);
INSERT INTO Leads VALUES (6, 5);
INSERT INTO Leads VALUES (6, 4);
INSERT INTO Leads VALUES (6, 2);
INSERT INTO Leads VALUES (6, 1);
INSERT INTO Leads VALUES (6, 6);
INSERT INTO Leads VALUES (6, 7);
INSERT INTO Leads VALUES (6, 8);
INSERT INTO Leads VALUES (6, 9);
INSERT INTO Leads VALUES (6, 10);

INSERT INTO Cares_For VALUES (2, 1);
INSERT INTO Cares_For VALUES (6, 2);
INSERT INTO Cares_For VALUES (4, 3);
INSERT INTO Cares_For VALUES (3, 4);
INSERT INTO Cares_For VALUES (7, 5);

INSERT INTO Is_For VALUES (9, 1);
INSERT INTO Is_For VALUES (3, 2);
INSERT INTO Is_For VALUES (5, 3);
INSERT INTO Is_For VALUES (7, 4);
INSERT INTO Is_For VALUES (10, 5);
INSERT INTO Is_For VALUES (8, 11);
INSERT INTO Is_For VALUES (1, 12);
INSERT INTO Is_For VALUES (5, 13);
INSERT INTO Is_For VALUES (6, 14);
INSERT INTO Is_For VALUES (10, 15);

INSERT INTO Is_Assigned VALUES (1, 10);
INSERT INTO Is_Assigned VALUES (2, 8);
INSERT INTO Is_Assigned VALUES (3, 7);
INSERT INTO Is_Assigned VALUES (4, 6);
INSERT INTO Is_Assigned VALUES (5, 5);

INSERT INTO Participates VALUES (1, 1);
INSERT INTO Participates VALUES (1, 2);
INSERT INTO Participates VALUES (2, 3);
INSERT INTO Participates VALUES (2, 4);
INSERT INTO Participates VALUES (8, 7);

COMMIT WORK;