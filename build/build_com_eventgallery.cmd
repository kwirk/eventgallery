echo Building com_eventgallery

set zipfilename=%targetpath%\com_eventgallery_%fileversion%.zip
del %zipfilename%
PUSHD %tmppath%
	%buildpath%\zip.exe -q -r %zipfilename% * -i "admin\*" "site\*" "eventgallery.xml" "index.html" "LICENSE.txt" "script.php"
POPD