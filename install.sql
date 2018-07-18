ALTER TABLE wcf1_category ADD isPackageServer TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_category ADD respositoryID INT(10);

DROP TABLE IF EXISTS packages1_repository;
CREATE TABLE packages1_repository (
	repositoryID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(20) NOT NULL
);
ALTER TABLE wcf1_category ADD FOREIGN KEY (respositoryID) REFERENCES packages1_repository (repositoryID) ON DELETE SET NULL;
