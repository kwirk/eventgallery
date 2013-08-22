echo.
echo Building Plugins
echo.
SETLOCAL ENABLEDELAYEDEXPANSION

set pluginbasefolder=%tmppath%\plugins

for /f "delims=" %%i in ('dir /ad/b %pluginbasefolder%') do (
	echo Building plugin plg_%%i
	
	set zipfilename=%targetpath%\plg_%%i_%fileversion%.zip
	set pluginfolder=%pluginbasefolder%\%%i

	del /Q !zipfilename!
	PUSHD !pluginfolder!
		%buildpath%\zip.exe -q -r !zipfilename! *
	POPD
)

ENDLOCAL