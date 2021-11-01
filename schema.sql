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
