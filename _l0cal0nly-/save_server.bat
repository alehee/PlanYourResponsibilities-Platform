@echo off
set url="riverlakestudios.pl/pyr/_l0cal0nly-/save_server.php"
start chrome.exe %url%
timeout 120
taskkill /f /im chrome.exe