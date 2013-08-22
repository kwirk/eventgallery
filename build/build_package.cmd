echo.
echo Building Packages
echo.
SETLOCAL ENABLEDELAYEDEXPANSION

set packagebasefolder=%tmppath%\packages

for /f "delims=" %%i in ('dir /ad/b %packagebasefolder%') do (
	echo Building package pkg_%%i
	echo.
	set zipfilename=%targetpath%\pkg_%%i_%fileversion%.zip
	set packagefolder=%packagebasefolder%\%%i
	
	rem copy over the necessary files
	
	
	rem split the line and get the 3th token 1<2>3</foo>
	for /f "delims=<>, tokens=3" %%a in ('findstr "<file " !packagefolder!\%%i.xml') do (
		set filename=%%a		
		xcopy /Q /Y %targetpath%\!filename! !packagefolder!\packages
	)

	
	del /Q !zipfilename!
	PUSHD !packagefolder!
		%buildpath%\zip.exe -q -r !zipfilename! *
	POPD
	
	echo.
)

ENDLOCAL