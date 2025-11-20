@echo off
echo Starting MySQL Server...

REM Check if MySQL is already running
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo MySQL is already running!
    pause
    exit /b 0
)

REM Start MySQL
cd /d C:\xampp\mysql\bin
start "MySQL Server" mysqld.exe --console

echo MySQL Server started!
timeout /t 2 /nobreak >nul
