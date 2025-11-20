@echo off
title Curup Water - Control Panel
color 0B

:MENU
cls
echo.
echo  ========================================
echo     CURUP WATER - CONTROL PANEL
echo  ========================================
echo.
echo  [1] Start All Services (MySQL + Apache + PHP)
echo  [2] Stop All Services
echo  [3] Check Services Status
echo  [4] Setup Database (First Time)
echo.
echo  [5] Start MySQL Only
echo  [6] Start phpMyAdmin Only
echo  [7] Start PHP Server Only
echo.
echo  [8] Open Application
echo  [9] Open Admin Panel
echo  [0] Open phpMyAdmin
echo.
echo  [Q] Quit
echo.
echo  ========================================
echo.
set /p choice="  Select option: "

if /i "%choice%"=="1" goto START_ALL
if /i "%choice%"=="2" goto STOP_ALL
if /i "%choice%"=="3" goto CHECK_STATUS
if /i "%choice%"=="4" goto SETUP_DB
if /i "%choice%"=="5" goto START_MYSQL
if /i "%choice%"=="6" goto START_PHPMYADMIN
if /i "%choice%"=="7" goto START_PHP
if /i "%choice%"=="8" goto OPEN_APP
if /i "%choice%"=="9" goto OPEN_ADMIN
if /i "%choice%"=="0" goto OPEN_PHPMYADMIN
if /i "%choice%"=="Q" goto QUIT
goto MENU

:START_ALL
cls
echo Starting all services...
call start-all.bat
pause
goto MENU

:STOP_ALL
cls
echo Stopping all services...
call stop-all.bat
pause
goto MENU

:CHECK_STATUS
cls
call check-services.bat
pause
goto MENU

:SETUP_DB
cls
echo Setting up database...
call setup-database.bat
pause
goto MENU

:START_MYSQL
cls
echo Starting MySQL...
call start-mysql.bat
pause
goto MENU

:START_PHPMYADMIN
cls
echo Starting phpMyAdmin...
call start-phpmyadmin.bat
pause
goto MENU

:START_PHP
cls
echo Starting PHP Server...
call start-server.bat
pause
goto MENU

:OPEN_APP
start http://localhost:8000
echo Opening Application...
timeout /t 2 /nobreak >nul
goto MENU

:OPEN_ADMIN
start http://localhost:8000/admin/
echo Opening Admin Panel...
timeout /t 2 /nobreak >nul
goto MENU

:OPEN_PHPMYADMIN
start http://localhost/phpmyadmin
echo Opening phpMyAdmin...
timeout /t 2 /nobreak >nul
goto MENU

:QUIT
cls
echo.
echo Thank you for using Curup Water Management System!
echo.
timeout /t 2 /nobreak >nul
exit
