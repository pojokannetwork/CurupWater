@echo off
echo ========================================
echo  Service Status Check
echo ========================================
echo.

echo Checking MySQL...
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo [RUNNING] MySQL Server
) else (
    echo [STOPPED] MySQL Server
)

echo.
echo Checking Apache...
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo [RUNNING] Apache Server ^(phpMyAdmin^)
) else (
    echo [STOPPED] Apache Server ^(phpMyAdmin^)
)

echo.
echo Checking PHP Dev Server...
tasklist /FI "IMAGENAME eq php.exe" 2>NUL | find /I /N "php.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo [RUNNING] PHP Development Server
) else (
    echo [STOPPED] PHP Development Server
)

echo.
echo ========================================
pause
