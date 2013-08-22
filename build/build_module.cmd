echo.
echo Building Modules
echo.
SETLOCAL ENABLEDELAYEDEXPANSION

set modulebasefolder=%tmppath%\modules

for /f "delims=" %%i in ('dir /ad/b %modulebasefolder%') do (
	echo Building module %%i
	
	set zipfilename=%targetpath%\%%i_%fileversion%.zip
	set modulefolder=%modulebasefolder%\%%i

	del /Q !zipfilename!
	PUSHD !modulefolder!
		%buildpath%\zip.exe -q -r !zipfilename! *
	POPD
)

ENDLOCAL