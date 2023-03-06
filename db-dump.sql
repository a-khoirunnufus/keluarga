CREATE TABLE person (
	id serial PRIMARY KEY,
	nama VARCHAR(50) NOT NULL,
	jenis_kelamin VARCHAR(9) NOT NULL,
	parent_id INT NULL
);

ALTER TABLE person ADD CONSTRAINT person_parent_id_fk 
	FOREIGN KEY (parent_id) REFERENCES person(id) ON DELETE RESTRICT ON UPDATE CASCADE;

INSERT INTO person(id, nama, jenis_kelamin, parent_id) VALUES
	(1, 'Budi', 'laki-laki', NULL),
	(2, 'Dedi', 'laki-laki', 1),
	(3, 'Dodi', 'laki-laki', 1),
	(4, 'Dede', 'laki-laki', 1),
	(5, 'Dewi', 'perempuan', 1),
	(6, 'Feri', 'laki-laki', 2),
	(7, 'Farah', 'perempuan', 2),
	(8, 'Gugus', 'laki-laki', 3),
	(9, 'Gandi', 'laki-laki', 3),
	(10, 'Hani', 'perempuan', 4),
	(11, 'Hana', 'perempuan', 4);

SELECT setval('person_id_seq', 11);