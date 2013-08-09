@echo off

setlocal EnableDelayedExpansion

for /F %%a in ('findstr "<version>" eventgallery.xml') do set versionStr="%%a"

set version=%versionStr:<version>=%
set version=%version:</version>=%
set version=%version:.=_%
set version=%version:"=%

set zipfilename=com_eventgallery_%version%.zip

rem com_eventgallery
del ..\%zipfilename%
zip -r ../%zipfilename% * -i "admin/*" "site/*" "eventgallery.xml" "index.html" "LICENSE.txt" "script.php"

rem mod_eventgallery_cart
set zipfilename=mod_eventgallery_cart_%version%.zip
cd modules
cd mod_eventgallery_cart
del ..\..\..\%zipfilename%
zip -r ../../../%zipfilename% *

cd..
cd..

rem plg_eventgallery_help
set zipfilename=plg_eventgallery_help_%version%.zip
cd plugins
cd eventgallery_help
del ..\..\..\%zipfilename%
zip -r ../../../%zipfilename% *

cd..
cd..

rem plg_eventgallery_help_toc
set zipfilename=plg_eventgallery_help_toc_%version%.zip
cd plugins
cd eventgallery_help_toc
del ..\..\..\%zipfilename%
zip -r ../../../%zipfilename% *

cd..
cd..