
cd C:\Program Files (x86)\Zend\Apache2\htdocs\achachi\output\bet
IF EXIST www rmdir www /S /Q
call C:\Users\david\AppData\Roaming\npm\cordova.cmd create www com.caleeli.pollabrasil PollBrasil
cd www
call C:\Users\david\AppData\Roaming\npm\cordova.cmd platform add android
call C:\Users\david\AppData\Roaming\npm\cordova.cmd platforms ls
call C:\Users\david\AppData\Roaming\npm\cordova.cmd build
call C:\Users\david\AppData\Roaming\npm\cordova.cmd run android

exit
