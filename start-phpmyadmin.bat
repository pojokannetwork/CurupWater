@echo off
echo Starting phpMyAdmin...

REM Check if Apache is already running
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo Apache is already running!
    start http://localhost/phpmyadmin
    pause
    exit /b 0
)

REM Start Apache for phpMyAdmin
cd /d C:\xampp\apache\bin
start "Apache Server" httpd.exe

echo Apache Server started!
timeout /t 3 /nobreak >nul

echo Opening phpMyAdmin...
start http://localhost/phpmyadmin

pause
