<?php

use wcf\system\database\table\column\IntDatabaseTableColumn;
use wcf\system\database\table\column\NotNullInt10DatabaseTableColumn;
use wcf\system\database\table\column\ObjectIdDatabaseTableColumn;
use wcf\system\database\table\column\TinyintDatabaseTableColumn;
use wcf\system\database\table\column\VarcharDatabaseTableColumn;
use wcf\system\database\table\DatabaseTable;
use wcf\system\database\table\index\DatabaseTableForeignKey;
use wcf\system\database\table\index\DatabaseTablePrimaryIndex;
use wcf\system\database\table\PartialDatabaseTable;

return [
    DatabaseTable::create('packages1_repository')
        ->columns([
            ObjectIdDatabaseTableColumn::create('repositoryID'),
            VarcharDatabaseTableColumn::create('name')
                ->length(20)
                ->notNull(),
            NotNullInt10DatabaseTableColumn::create('packesCount')
                ->defaultValue(0),
            IntDatabaseTableColumn::create('lastUpdateTime')
                ->length(10),
        ])
        ->indices([
            DatabaseTablePrimaryIndex::create()
                ->columns(['repositoryID']),
        ]),
    PartialDatabaseTable::create('wcf1_category')
        ->columns([
            TinyintDatabaseTableColumn::create('isPackageServer')
                ->length(1)
                ->notNull()
                ->defaultValue(0),
            IntDatabaseTableColumn::create('repositoryID')
                ->length(10),
        ])
        ->foreignKeys([
            DatabaseTableForeignKey::create()
                ->columns(['repositoryID'])
                ->referencedTable('packages1_repository')
                ->referencedColumns(['repositoryID'])
                ->onDelete('SET NULL'),
        ]),
    PartialDatabaseTable::create('filebase1_file_version')
        ->columns([
            VarcharDatabaseTableColumn::create('packageName')
                ->length(100),
            VarcharDatabaseTableColumn::create('packageVersion')
                ->length(80),
            IntDatabaseTableColumn::create('repositoryID')
                ->length(10),
        ])
        ->foreignKeys([
            DatabaseTableForeignKey::create()
                ->columns(['repositoryID'])
                ->referencedTable('packages1_repository')
                ->referencedColumns(['repositoryID'])
                ->onDelete('SET NULL'),
        ]),
];
