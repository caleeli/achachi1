
cd C:\Program Files (x86)\Zend\Apache2\htdocs\achachi\output\bet
IF EXIST cordova rmdir cordova /S /Q
call C:\Users\david\AppData\Roaming\npm\cordova.cmd create cordova com.caleeli.pollabrasil PollaBrasil
cd cordova
call C:\Users\david\AppData\Roaming\npm\cordova.cmd platform add android
call C:\Users\david\AppData\Roaming\npm\cordova.cmd platforms ls
xcopy ../mobile/* www/ /s /e
call C:\Users\david\AppData\Roaming\npm\cordova.cmd build
call C:\Users\david\AppData\Roaming\npm\cordova.cmd run android

exit
