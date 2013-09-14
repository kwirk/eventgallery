@ECHO OFF

rem CREATE Path variable

	echo Create the necessary path variables
	echo.

	set sourcepath=%cd%
	cd ..
	set targetpath=%cd%
	cd %sourcepath%
	set buildpath=%sourcepath%\build
	set tmppath=%targetpath%\tmpBuild
	set fartpath=%buildpath%\fart.exe


	echo Source Path is %sourcepath% 
	echo Target Path is %targetpath%
	echo Tmp Path is    %tmppath%
	echo FART Path is   %fartpath%
	echo.


	set /p version= <%buildpath%\version.txt
	set fileversion=%version:.=_%
	echo Building %version% (%fileversion%)
	echo.

rem COPY to temp folder

	echo.
	echo Copy everything into a temp folder
	echo.
 	rmdir /Q /S %tmppath%
 	mkdir %tmppath%
 	xcopy /Q /E %sourcepath% %tmppath%

	echo.
	echo Replace the placeholders
	echo.
	%fartpath% -r  %tmppath%\*.* "$$version$$" "%version%"
	%fartpath% -r  %tmppath%\*.* "$$fileversion$$" "%fileversion%"
	echo.
	echo.
	echo.

rem pack the items

 	call %buildpath%\build_com_eventgallery.cmd

	call %buildpath%\build_module.cmd
	
	call %buildpath%\build_plugin.cmd

rem create the package by using package files

	call %buildpath%\build_package.cmd