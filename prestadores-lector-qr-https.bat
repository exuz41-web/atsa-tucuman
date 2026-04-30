@echo off
cd /d "%~dp0"
echo ========================================
echo  ATSA Tucuman - Portal prestadores HTTPS
echo ========================================
echo.
echo Este modo es para probar el lector QR en vivo desde celular.
echo Chrome Android bloquea la camara en HTTP, por eso se usa un tunel HTTPS.
echo.
echo 1) Deja corriendo Laravel en http://127.0.0.1:8000
echo 2) Este script abrira una URL https://... para el celular
echo.
npx --yes localtunnel --port 8000 --local-host 127.0.0.1
pause
