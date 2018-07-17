#!/bin/bash
rm -rf acptemplates.tar
7z a -ttar -mx=9 acptemplates.tar ./acptemplates/*
rm -rf files.tar
7z a -ttar -mx=9 files.tar ./files/*
rm -rf templates.tar
7z a -ttar -mx=9 templates.tar ./templates/*
rm -rf eu.hanashi.packages.tar
7z a -ttar -mx=9 eu.hanashi.packages.tar ./* -x!acptemplates -x!files -x!templates -x!eu.hanashi.packages.tar -x!.git -x!.gitignore -x!make.bat -x!make.sh