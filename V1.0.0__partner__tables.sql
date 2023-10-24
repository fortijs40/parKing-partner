USE partners;

DROP TABLE IF EXISTS partner_types;

CREATE TABLE partner_types (
                               type_id tinyint NOT NULL AUTO_INCREMENT,
                               type_description varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                               PRIMARY KEY (type_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS partners_id;

CREATE TABLE partners_id (
                             partner_id bigint NOT NULL AUTO_INCREMENT,
                             type_id tinyint NOT NULL,
                             email varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                             hashed_password varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                             PRIMARY KEY (partner_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS persons;

CREATE TABLE persons (
                         person_id bigint NOT NULL AUTO_INCREMENT,
                         partner_id bigint NOT NULL,
                         first_name varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                         last_name varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                         email varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                         phone_number varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                         bank_account varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                         billing_address varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                         PRIMARY KEY (person_id),
                         FOREIGN KEY (partner_id) REFERENCES partners_id (partner_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS companies;

CREATE TABLE companies (
                           company_id bigint NOT NULL AUTO_INCREMENT,
                           partner_id bigint NOT NULL,
                           company_name varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                           reg_no varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                           email varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                           phone_number varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                           second_phone_no varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                           bank_account varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                           billing_address varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                           PRIMARY KEY (company_id),
                           FOREIGN KEY (partner_id) REFERENCES partners_id (partner_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS parkingspots;

CREATE TABLE parkingspots (
                              spot_id bigint NOT NULL AUTO_INCREMENT,
                              partner_id bigint NOT NULL,
                              spot_name varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              spot_address varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              start_time time,
                              end_time time,
                              price decimal NOT NULL,
                              max_spot_count int,
                              is_premium tinyint,
                              is_disabled tinyint,
							  add_info varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
							  rating decimal,
                              FOREIGN KEY (partner_id) REFERENCES partners_id (partner_id),
                              PRIMARY KEY (spot_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS reservations;

CREATE TABLE reservations (
                              reserv_id bigint NOT NULL AUTO_INCREMENT,
                              partner_id bigint NOT NULL,
                              client_id bigint NOT NULL,
                              spot_id bigint,
                              start_date date,
                              end_date date,
                              start_time time,
                              end_time time,
                              parkingspot varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              payment_sum int,
                              is_read tinyint,
                              PRIMARY KEY (reserv_id),
                              FOREIGN KEY (partner_id) REFERENCES partners_id (partner_id),
                              FOREIGN KEY (spot_id) REFERENCES parkingspots (spot_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS reviews;

CREATE TABLE reviews (
                         rev_id bigint NOT NULL AUTO_INCREMENT,
                         partner_id bigint NOT NULL,
                         client_id bigint NOT NULL,
                         spot_id bigint,
                         rev_description text,
                         posted_time datetime(6) DEFAULT NULL,
                         rating double NOT NULL,
                         title varchar(255) DEFAULT NULL,
                         is_read tinyint,
                         PRIMARY KEY (rev_id),
                         FOREIGN KEY (partner_id) REFERENCES partners_id (partner_id),
                         FOREIGN KEY (spot_id) REFERENCES parkingspots (spot_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS reports;

CREATE TABLE reports (
                         rep_id bigint NOT NULL AUTO_INCREMENT,
                         partner_id bigint NOT NULL,
                         client_id bigint NOT NULL,
                         spot_id bigint,
                         rep_description text,
                         is_read tinyint,
                         PRIMARY KEY (rep_id),
                         FOREIGN KEY (partner_id) REFERENCES partners_id (partner_id),
                         FOREIGN KEY (spot_id) REFERENCES parkingspots (spot_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


