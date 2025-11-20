@echo off
echo ========================================
echo  Stopping All Services
echo ========================================
echo.

echo Stopping MySQL Server...
taskkill /F /IM mysqld.exe >nul 2>&1
if %ERRORLEVEL%==0 (
    echo [OK] MySQL stopped
) else (
    echo [INFO] MySQL was not running
)

echo Stopping Apache Server...
taskkill /F /IM httpd.exe >nul 2>&1
if %ERRORLEVEL%==0 (
    echo [OK] Apache stopped
) else (
    echo [INFO] Apache was not running
)

echo Stopping PHP Development Server...
taskkill /F /IM php.exe >nul 2>&1
if %ERRORLEVEL%==0 (
    echo [OK] PHP Dev Server stopped
) else (
    echo [INFO] PHP Dev Server was not running
)

echo.
echo ========================================
echo  All Services Stopped
echo ========================================
timeout /t 2 /nobreak >nul
