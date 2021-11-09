CREATE TABLE Event (
                       ID: INT AUTO_INCREMENT,
                       name: VARCHAR(255),
                       date: DATE,
                       start_time: TIME,
                       end_time: TIME,
                       PRIMARY KEY (ID)
);

CREATE TABLE Aquarium_Event (
                                event_id: INT,
                                type: VARCHAR(255),
                                location: VARCHAR(255),
                                PRIMARY KEY (event_id),
                                FOREIGN KEY (event_id) REFERENCES Event(ID)
                                    ON UPDATE CASCADE
                                    ON DELETE CASCADE,
                                FOREIGN KEY (type) REFERENCES Event_Ticket_Rates(type)
                                    ON UPDATE CASCADE
                                    ON DELETE NO ACTION,
                                FOREIGN KEY (location) REFERENCES Location_Size(location)
                                    ON UPDATE CASCADE
                                    ON DELETE NO ACTION
);

CREATE TABLE Location_Size (
                               location: VARCHAR(255),
                               capacity: INT,
                               PRIMARY KEY (location)
);




CREATE TABLE Event_Ticket_Rates (
                                    type:VARCHAR(255),
                                    ticket_price: INT,
                                    PRIMARY KEY (type)
);

CREATE TABLE Customer_Event (
                                event_id: INT,
                                group_size: INT,
                                customer_id: INT NOT NULL,
                                PRIMARY KEY (event_id),
                                FOREIGN KEY (event_id) REFERENCES Event(ID)
                                    ON UPDATE CASCADE
                                    ON DELETE CASCADE,
                                FOREIGN KEY (group_size) REFERENCES Event_Booking_Rates(group_size)
                                    ON UPDATE CASCADE
                                    ON DELETE NO ACTION,
                                FOREIGN KEY (customer_id) REFERENCES Customer(ID)
                                    ON UPDATE CASCADE
                                    ON DELETE NO ACTION
);

CREATE TABLE Event_Booking_Rates (
                                     group_size: INT,
                                     cost: INT
                                         PRIMARY KEY (group_size)
);

CREATE TABLE Customer (
                          ID: INT AUTO_INCREMENT,
                          phone#: VARCHAR(255) UNIQUE,
                          email: VARCHAR(255) UNIQUE,
                          last_name: VARCHAR(255),
                          first_name: VARCHAR(255),
                          PRIMARY KEY (ID),
);

CREATE TABLE Employee (
                          ID: INT AUTO_INCREMENT,
                          phone#: VARCHAR(255) UNIQUE,
                          first_name: VARCHAR(255),
                          email: VARCHAR(255) UNIQUE,
                          last_name: CHAR(50),
                          PRIMARY KEY (ID)
);
CREATE TABLE Biologist (
                           employee_id: INT,
                           specialty: VARCHAR(255),
                           department: VARCHAR(255),
                           PRIMARY KEY (employee_id),
                           FOREIGN KEY (employee_id) REFERENCES Employee(ID)
                               ON UPDATE CASCADE
                               ON DELETE CASCADE,
                           FOREIGN KEY (department) REFERENCES Department_Details(department)
                               ON UPDATE CASCADE
                               ON DELETE NO ACTION
);

CREATE TABLE Department_Location (
                                     department: VARCHAR(255),
                                     office_location: VARCHAR(255)
                                         PRIMARY KEY (department)
);

CREATE TABLE Checkup (
                         ID: INT AUTO_INCREMENT,
                         type: VARCHAR(255),
                         date: DATE,
                         time: TIME,
                         biologist_id: INT NOT NULL,
                         animal_id: INT NOT NULL,
                         PRIMARY KEY (ID),
                         FOREIGN KEY (type) REFERENCES Checkup_Priority(type)
                             ON UPDATE CASCADE
                             ON DELETE NO ACTION,
                         FOREIGN KEY (biologist_id) REFERENCES Biologist(ID)
                             ON UPDATE CASCADE
                             ON DELETE CASCADE,
                         FOREIGN KEY (animal_id) REFERENCES Animal(ID)
                             ON UPDATE CASCADE
                             ON DELETE CASCADE
);

CREATE TABLE Checkup_Priority (
                                  type: VARCHAR(255),
                                  priority: INT,
                                  PRIMARY KEY (type)
);


CREATE TABLE Animal (
                        ID: INT AUTO_INCREMENT,
                        enclosure_id: INT NOT NULL,
                        species: VARCHAR(255),
                        health: VARCHAR(255),
                        PRIMARY KEY (ID),
                        FOREIGN KEY (enclosure_id) REFERENCES Enclosure (ID)
                            ON UPDATE CASCADE
                            ON DELETE NO ACTION
);

CREATE TABLE Aquatic_Animal (
                                animal_id: INT,
                                water_temp: INT,
                                water_type: VARCHAR(255),
                                PRIMARY KEY (animal_id),
                                FOREIGN KEY (animal_id) REFERENCES Animal(ID)
                                    ON UPDATE CASCADE
                                    ON DELETE CASCADE
);

CREATE TABLE Land_Animal (
                             animal_id: INT,
                             environment: VARCHAR(255),
                             PRIMARY KEY (animal_id),
                             FOREIGN KEY (animal_id) REFERENCES Animal(ID)
                                 ON UPDATE CASCADE
                                 ON DELETE CASCADE
);

CREATE TABLE Schedule (
                          ID: INT AUTO_INCREMENT,
                          frequency: INT,
                          time: TIME,
                          PRIMARY KEY (ID)
);

CREATE TABLE Feeding_Schedule (
                                  schedule_id: INT,
                                  food_type: VARCHAR(255),
                                  PRIMARY KEY (schedule_id),
                                  FOREIGN KEY (schedule_id) REFERENCES Schedule(ID)
                                      ON UPDATE CASCADE
                                      ON DELETE CASCADE
);
CREATE TABLE Cleaning_Schedule (
                                   schedule_id: INT,
                                   enclosure_id: VARCHAR(255) UNIQUE NOT NULL,
                                   PRIMARY KEY (schedule_id),
                                   FOREIGN KEY (schedule_id) REFERENCES Schedule(ID)
                                       ON UPDATE CASCADE
                                       ON DELETE CASCADE,
                                   FOREIGN KEY (enclosure_id) REFERENCES Enclosure(ID)
                                       ON UPDATE CASCADE
                                       ON DELETE CASCADE
);

CREATE TABLE Enclosure (
                           ID: INT AUTO_INCREMENT,
                           type: VARCHAR(255),
                           temperature: INT,
                           PRIMARY KEY (ID)
);

CREATE TABLE Leads (
                       employee_id: INT,
                       event_id: INT,
                       PRIMARY KEY (employee_id, event_id),
                       FOREIGN KEY (employee_id) REFERENCES Employee(ID)
                           ON UPDATE CASCADE
                           ON DELETE CASCADE,
                       FOREIGN KEY (event_id) REFERENCES Event(ID)
                           ON UPDATE CASCADE
                           ON DELETE CASCADE
);

CREATE TABLE Cares_For (
                           employee_id: INT,
                           animal_id: INT,
                           PRIMARY KEY (employee_id, animal_id),
                           FOREIGN KEY (employee_id) REFERENCES Employee(ID)
                               ON UPDATE CASCADE
                               ON DELETE CASCADE,
                           FOREIGN KEY (animal_id) REFERENCES Animal(ID)
                               ON UPDATE CASCADE
                               ON DELETE CASCADE
);



CREATE TABLE To (
                    animal_id: INT,
                    feeding_schedule_id: INT,
                    PRIMARY KEY (animal_id, feeding_schedule_id),
                    FOREIGN KEY (animal_id) REFERENCES Animal(ID)
                        ON UPDATE CASCADE
                        ON DELETE CASCADE,
                    FOREIGN KEY (feeding_schedule_id) REFERENCES Feeding_Schedule(schedule_id)
                        ON UPDATE CASCADE
                        ON DELETE CASCADE
);

CREATE TABLE Is_Assigned (
                             employee_id: INT,
                             schedule_id: INT,
                             PRIMARY KEY (employee_id, schedule_id),
                             FOREIGN KEY (employee_id) REFERENCES Employee(ID)
                                 ON UPDATE CASCADE
                                 ON DELETE CASCADE,
                             FOREIGN KEY (schedule_id) REFERENCES Schedule(ID)
                                 ON UPDATE CASCADE
                                 ON DELETE CASCADE
);

CREATE TABLE Participates (
                              aquarium_event_id: INT,
                              animal_id: INT,
                              PRIMARY KEY (aquarium_event_id, animal_id),
                              FOREIGN KEY (aquareium_event_id) REFERENCES Aquarium_Event(event_id)
                                  ON UPDATE CASCADE
                                  ON DELETE CASCADE,
                              FOREIGN KEY (animal_id) REFERENCES Animal(ID)
                                  ON UPDATE CASCADE
                                  ON DELETE CASCADE
);

INSERT INTO Event
VALUES (1, "Penguin Encounter", 2021-10-31, 16:00:00, 17:59:59),
       (2, "Dolphin Show", 2021-11-06, 13:30:00, 13:59:59),
       (3, "Vancouver Elementary Outreach", 2022-01-03, 13:00:00, 14:59:59),
       (4, "UBC Biology 100 Field Trip", 2022-01-17, 10:30:00, 13:29:59),
       (5, "Krystal's Birthday", 2021-12-14, 13:00:00, 14:59:59),
       (6, "Mea's Birthday", 2022-01-29, 15:00:00, 16:59:59),
       (7, "Volunteer Cleanup", 2021-11-15, 11:30:00, 13:59:59),
       (8, "Marine Mammal Rescue Exhibit", 2021-11-15, 14:00:00, 15:59:59),
       (9, "UBC Elementary Field Trip", 2022-02-02, 09:30:00, 14:59:59),
       (10, "Virtual Aquarium Tour", 2021-12-28, 12:30:00, 13:59:59);

INSERT INTO Aquarium_Event
VALUES (1, "Interactive", "Penguin Encounter Room"),
       (2, "Entertainment", "Dolphin Tank"),
       (7, "Volunteer", "Stanley Park"),
       (8, "Educational", "Seal Rescue Enclosure"),
       (10, "Virtual", "Zoom");

INSERT INTO Location_Size
VALUES ("Penguin Encounter Room", 20),
       ("Dolphin Tank", 150),
       ("Stanley Park", 200),
       ("Seal Rescue Enclosure", 40),
       ("Zoom", 20);

INSERT INTO Event_Ticket_Rates
VALUES ("Interactive", 40),
       ("Entertainment", 35),
       ("Volunteer", 0),
       ("Educational", 20),
       ("Virtual", 25);

INSERT INTO Customer_Event
VALUES (3, 30, 2),
       (4, 30, 1),
       (5, 10, 5),
       (6, 5, 4),
       (9, 50, 3);

INSERT INTO Event_Booking_Rates
VALUES (20, 300),
       (120, 1500),
       (10, 100),
       (5. 50),
       (50, 500);

INSERT INTO Customer
VALUES (1, "778-888-8888", "mea_ubc@gmail.com", "Mea", "Srisan"),
       (2, "604-345-6789", "jsmith@vancouverelementary.ca", "John", "Smith"),
       (3, "778-333-3333", "janelee@gmail.com", "Jane", "Lee"),
       (4, "604-333-3333", "l_wang@hotmail.com", "Linda", "Wang"),
       (5, "778-999-9999", "e_perry@hotmail.com", "Emily", "Perry");

INSERT INTO Employee
vALUES (1, "778-984-9384", "kurt.kaufer@aquarium.ubc.ca", "Kurt", "Kaufer"),
       (2, "604-444-4444", "alan.li@aquarium.ubc.ca", "Alan", "Li"),
       (3, "604-555-5555", "dorothy.kim@aquarium.ubc.ca", "Dorothy", "Kim"),
       (4, "778-111-1111", "linda.poon@aquarium.ubc.ca", "Linda", "Poon"),
       (5, "604-000-0000", "samuel.ericson@aquarium.ubc.ca", "Samuel", "Ericson"),
       (6, "778-661-6633", "xiao.wang@aquarium.ubc.ca", "Xiao", "Wang"),
       (7, "604-000-0001", "taylor.swift@aquarium.ubc.ca", "Taylor", "Swift");

INSERT INTO Biologist
VALUES (1, "Dolphin Physiology", "Dolphin"),
       (2, "Penguin Physiology", "Penguin"),
       (4, "Fish Physiology", "Fish"),
       (6, "Coastline Conservation", "Conservation"),
       (7, "Reproduction", "Marine Genetics");

INSERT INTO Department_Location
VALUES ("Dolphin", "Office Annex A"),
       ("Penguin", "Office Annex B"),
       ("Fish", "Office Annex B"),
       ("Conservation", "Office Annex C"),
       ("Marine Genetics", "Office Annex A");

INSERT INTO Checkup
VALUES (1, "Regular Checkup", 2021-11-29, 9:30:00, 4, 5),
       (2, "Heart Surgery", 2021-12-01, 11:30:00, 2, 1),
       (3, "Swim Rehab", 2022-01-23, 14:00:00, 1, 3),
       (4, "Pregnancy Checkup", 2022-02-11, 13:15:00, 2, 1),
       (5, "Artificial Fin Attachment Surgery", 2022-01-23, 9:30:00, 1, 3);

INSERT INTO Checkup_Priority
VALUES ("Regular Checkup", 0),
       ("Heart Surgery", 2),
       ("Swim Rehab", 1),
       ("Pregnancy Checkup", 1),
       ("Artificial Fin Attachment Surgery", 2);

INSERT INTO Animal
VALUES (1, 5, "Eudyptula minor", "Poor"),
       (2, 5, "Eudyptula minor", "Pregnant"),
       (3, 3, "Lagenorhynchus obliquidens", "In Rehab"),
       (4, 3, "Lagenorhynchus obliquidens", "Poor"),
       (5, 1, "Amphiprion percula", "Healthy"),
       (6, 1, "Paracanthurus hepatus", "Healthy"),
       (7, 2, "Enhydra lutris", "Healthy"),
       (8, 2, "Enhydra lutris", "Pregnant"),
       (9, 2, "Enhydra lutris", "Healthy"),
       (10, 4, "Octopus vulgaris", "Healthy");

INSERT INTO Aquatic_Animal
VALUES (3, "Salt water", 15),
       (4, "Salt water", 15),
       (5, "Salt water", 22),
       (6, "Salt water", 24),
       (10, "Salt water", 26);

INSERT INTO Land_Animal
VALUES (1, "Inshore waters around the coast and breeding islands"),
       (2, "Inshore waters around the coast and breeding islands"),
       (7, "Seas and rocky shores"),
       (8, "Seas and rocky shores"),
       (9, "Seas and rocky shores");

INSERT INTO Schedule
VALUES (1, "Weekly", 14:00:00),
       (2, "Daiy", 05:30:00),
       (3, "Daiy", 05:30:00),
       (4, "Daiy", 06:00:00),
       (5, "Daiy", 06:30:00),
       (6, "Daiy", 06:30:00),
       (7, "Weekly", 20:00:00),
       (8, "Daily", 21:00:00),
       (9, "Daily", 21:30:00),
       (10, "Daily", 22:00:00);

INSERT INTO Feeding_Schedule
VALUES (1, "Clams"),
       (2, "Feeder fish"),
       (3, "Flake food"),
       (4, "Feeder fish"),
       (5, "Crabs");

INSERT INTO Cleaning_Schedule
VALUES (6, 1),
       (7, 2),
       (8, 3),
       (9, 4),
       (10, 5);

INSERT INTO Enclosure
VALUES (1, "Tank", 22),
       (2, "Open Air", 10),
       (3, "Tank", 15),
       (4, "Tank", 25),
       (5, "Open Air");

INSERT INTO Leads
VALUES (1, 3),
       (1, 5),
       (2, 5),
       (4, 4),
       (4, 9);

INSERT INTO Cares_For
VALUES (2, 1),
       (6, 2),
       (4, 3),
       (3, 4),
       (7, 5);

INSERT INTO To
VALUES (9, 1),
       (3, 2),
       (5, 3),
       (7, 4),
       (10, 5);

INSERT INTO Is_Assigned
VALUES (1, 10),
       (2, 8),
       (3, 7),
       (4, 6),
       (5, 5);

INSERT INTO Participates
VALUES (1, 1),
       (1, 2),
       (2, 3),
       (2, 4),
       (8, 7);

