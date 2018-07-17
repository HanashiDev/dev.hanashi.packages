@ECHO OFF
del acptemplates.tar
7z a -ttar -mx=9 acptemplates.tar .\acptemplates\*
del files.tar
7z a -ttar -mx=9 files.tar .\files\*
del templates.tar
7z a -ttar -mx=9 templates.tar .\templates\*
del eu.hanashi.packages.tar
7z a -ttar -mx=9 eu.hanashi.packages.tar .\* -x!acptemplates -x!files -x!templates -x!eu.hanashi.packages.tar -x!.git -x!.gitignore -x!make.bat -x!make.sh