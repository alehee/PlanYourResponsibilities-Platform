@echo off
set url="localhost/plandeca/TFRS2P6VB6/save_server.php"
start chrome.exe %url%
timeout 120
taskkill /f /im chrome.exe