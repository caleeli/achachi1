
cd C:\Program Files (x86)\Zend\Apache2\htdocs\achachi\output\pacman
IF EXIST cordova (
  cd cordova
  GOTO Construir
)
REM rmdir cordova /S /Q
call C:\Users\david\AppData\Roaming\npm\cordova.cmd create cordova com.caleeli.suarezman SuarezPacman
cd cordova
call C:\Users\david\AppData\Roaming\npm\cordova.cmd platform add android
call C:\Users\david\AppData\Roaming\npm\cordova.cmd platforms ls
:Construir
xcopy "../mobile" "www" /s /e
call C:\Users\david\AppData\Roaming\npm\cordova.cmd build
call C:\Users\david\AppData\Roaming\npm\cordova.cmd run android

exit
