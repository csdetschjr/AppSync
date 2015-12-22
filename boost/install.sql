BEGIN;

CREATE TABLE appsync_umbrella (
    id INT NOT NULL,
    name VARCHAR NOT NULL UNIQUE,
    orgsync_id INT NOT NULL UNIQUE,
    PRIMARY KEY(id)
);

CREATE SEQUENCE appsync_umbrella_seq;

INSERT INTO appsync_umbrella (id, name, orgsync_id) VALUES (nextval('appsync_umbrella_seq'), 'CSIL', 87895);
INSERT INTO appsync_umbrella (id, name, orgsync_id) VALUES (nextval('appsync_umbrella_seq'), 'Orientation', 112682);
INSERT INTO appsync_umbrella (id, name, orgsync_id) VALUES (nextval('appsync_umbrella_seq'), 'Student Development', 101226);
INSERT INTO appsync_umbrella (id, name, orgsync_id) VALUES (nextval('appsync_umbrella_seq'), 'University Housing', 107843);

CREATE TABLE appsync_portal (
    orgsync_id INT NOT NULL UNIQUE,
    name VARCHAR NOT NULL UNIQUE,
    umbrella_id INT REFERENCES appsync_umbrella(orgsync_id),
    PRIMARY KEY(orgsync_id)
);

CREATE TABLE appsync_event (
    id INT NOT NULL,
    description VARCHAR NOT NULL,
    PRIMARY KEY(id)
);

//fake table for use with testing
CREATE TABLE sdr_member (
    id INT NOT NULL,
    username VARCHAR NOT NULL,
    PRIMARY KEY(id)
);

INSERT INTO sdr_member (id, username) VALUES (900325987, 'cd62936');

CREATE TABLE appsync_log (
    id INT NOT NULL,
    type INT NOT NULL REFERENCES appsync_event(id),
    response VARCHAR NOT NULL,
    description VARCHAR NOT NULL,
    username VARCHAR NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE appsync_settings (
    setting VARCHAR NOT NULL,
    value VARCHAR NOT NULL,
    PRIMARY KEY(setting)
);

INSERT INTO appsync_settings (setting, value) VALUES ('ORGSYNC_KEY', 'GbBHsBinbv5lWyK7Lnejrg');
INSERT INTO appsync_settings (setting, value) VALUES ('BASE_URL', 'https://api.orgsync.com/api/v2/');


COMMIT;
