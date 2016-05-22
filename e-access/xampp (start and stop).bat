@echo off

C:\xampp\xampp_start.exe

start "Localhost" "http://localhost/e-access/pages/index.php"

:start
cls
echo Xampp is working properly...
echo Close this window only to stop the xampp...

set /p x=Enter `exit` to close the xampp console :
if "%x%"=="exit" (
C:\xampp\xampp_stop.exe ) else (
goto start )
