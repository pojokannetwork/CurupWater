@echo off
echo ========================================
echo  Starting Curup Water Development Server
echo ========================================
echo.

REM Check if XAMPP is installed
if not exist "C:\xampp\mysql\bin\mysqld.exe" (
    echo [ERROR] XAMPP MySQL not found!
    echo Please install XAMPP first or adjust the path.
    pause
    exit /b 1
)

echo [1/4] Starting MySQL Server...
cd /d C:\xampp\mysql\bin
start "" mysqld.exe --console
timeout /t 3 /nobreak >nul

echo [2/4] Starting Apache Server (for phpMyAdmin)...
cd /d C:\xampp\apache\bin
start "" httpd.exe
timeout /t 3 /nobreak >nul

echo [3/4] Starting PHP Development Server (Port 8000)...
cd /d C:\xampp\htdocs\CurupWater
start "Curup Water Server" C:\xampp\php\php.exe -S localhost:8000
timeout /t 2 /nobreak >nul

echo [4/4] Opening Application...
timeout /t 2 /nobreak >nul
start http://localhost:8000
start http://localhost/phpmyadmin

echo.
echo ========================================
echo  All Services Started Successfully!
echo ========================================
echo.
echo  Application:  http://localhost:8000
echo  phpMyAdmin:   http://localhost/phpmyadmin
echo  Admin Panel:  http://localhost:8000/admin/
echo.
echo Press any key to stop all services...
pause >nul

echo.
echo Stopping all services...
taskkill /F /IM mysqld.exe >nul 2>&1
taskkill /F /IM httpd.exe >nul 2>&1
taskkill /F /IM php.exe >nul 2>&1

echo All services stopped.
timeout /t 2 /nobreak >nul
