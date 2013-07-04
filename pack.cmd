@echo off

setlocal EnableDelayedExpansion

for /F %%a in ('findstr "<version>" eventgallery.xml') do set versionStr="%%a"
set version=%versionStr:<version>=%
set version=%version:</version>=%
set version=%version:.=_%
set zipfilename=com_eventgallery_%version%.zip

del %zipfilename%
zip -r ../%zipfilename% * -i "admin/*" "site/*" "eventgallery.xml" "index.html" "LICENSE.txt" "script.php"