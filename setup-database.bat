@echo off
echo ========================================
echo  Curup Water - Auto Database Setup
echo ========================================
echo.

REM Path to MySQL
set MYSQL_PATH=C:\xampp\mysql\bin
set DB_NAME=curupwater
set SQL_FILE=%~dp0setup.sql

REM Check if MySQL is installed
if not exist "%MYSQL_PATH%\mysql.exe" (
    echo [ERROR] MySQL not found at %MYSQL_PATH%
    echo Please check your XAMPP installation.
    pause
    exit /b 1
)

echo [1/3] Checking if MySQL is running...
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo   MySQL is running
) else (
    echo   MySQL is not running. Starting MySQL...
    cd /d C:\xampp\mysql\bin
    start "MySQL Server" mysqld.exe --console
    timeout /t 5 /nobreak >nul
)

echo.
echo [2/3] Creating database if not exists...
"%MYSQL_PATH%\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS %DB_NAME%; SHOW DATABASES LIKE '%DB_NAME%';"

if %ERRORLEVEL% EQU 0 (
    echo   Database '%DB_NAME%' is ready
) else (
    echo   [ERROR] Failed to create database
    pause
    exit /b 1
)

echo.
echo [3/3] Importing database structure...
echo   File: %SQL_FILE%

if not exist "%SQL_FILE%" (
    echo   [ERROR] setup.sql not found!
    echo   Please make sure setup.sql is in the same folder.
    pause
    exit /b 1
)

"%MYSQL_PATH%\mysql.exe" -u root %DB_NAME% < "%SQL_FILE%"

if %ERRORLEVEL% EQU 0 (
    echo   [SUCCESS] Database imported successfully!
) else (
    echo   [ERROR] Failed to import database
    pause
    exit /b 1
)

echo.
echo ========================================
echo  Database Setup Complete!
echo ========================================
echo.
echo Default Admin Login:
echo   Username: admin
echo   Password: admin123
echo.
echo Next step: Run start-all.bat to start the application
echo.
pause
