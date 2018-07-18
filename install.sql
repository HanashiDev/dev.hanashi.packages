ALTER TABLE wcf1_category ADD isPackageServer TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_category ADD repositoryID INT(10);
ALTER TABLE filebase1_file_version ADD packageName VARCHAR(100);
ALTER TABLE filebase1_file_version ADD packageVersion VARCHAR(80);
ALTER TABLE filebase1_file_version ADD repositoryID INT(10);

DROP TABLE IF EXISTS packages1_repository;
CREATE TABLE packages1_repository (
	repositoryID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(20) NOT NULL,
	packesCount INT(10) NOT NULL DEFAULT 0,
	lastUpdateTime INT(10)
);
ALTER TABLE wcf1_category ADD FOREIGN KEY (repositoryID) REFERENCES packages1_repository (repositoryID) ON DELETE SET NULL;
ALTER TABLE filebase1_file_version ADD FOREIGN KEY (repositoryID) REFERENCES packages1_repository (repositoryID) ON DELETE SET NULL;
