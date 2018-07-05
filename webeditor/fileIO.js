if (window.ActiveXObject) {
  function SaveToFile (fileName, text)
  {
     var fso  = new ActiveXObject("Scripting.FileSystemObject");
     var fh = fso.CreateTextFile(fileName, true);
     fh.Write(text);
     fh.Close();
  }
} else {
  function SaveToFile (fileName, text) {
    try {
    netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
    } catch (e) {
      alert("Permission to write file denied.");
      return 0;
    }
    var file =Components.classes["@mozilla.org/file/local;1"].createInstance(Components.interfaces.nsILocalFile );
    try {
      file.initWithPath(fileName);
    } catch(e) {
      fileName=fileName.split("/").join("\\");
      file.initWithPath(fileName);
    }
    if (!file.exists()) file.create(0x00, 0644);
    var outputStream =
    Components.classes["@mozilla.org/network/file-output-stream;1"].createInstance(Components.interfaces.nsIFileOutputStream);
    outputStream.init(file,0x20 | 0x02,00004,null);
    outputStream.write(text, text.length);
    outputStream.flush();
    outputStream.close();
  }
  function readFile(myfile) {
    try {
      netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
    } catch (e) {
      alert("Permission to read file denied."); return '';
    }
    var file = Components.classes["@mozilla.org/file/local;1"].createInstance(Components.interfaces.nsILocalFile );
    file.initWithPath(myfile);
    if (!file.exists()) {alert("File not found."); return '';}
    var is = Components.classes["@mozilla.org/network/file-input-stream;1"].createInstance(
    Components.interfaces.nsIFileInputStream );
    is.init(file,0x01, 00004, null);
    var sis = Components.classes["@mozilla.org/scriptableinputstream;1"].createInstance(
    Components.interfaces.nsIScriptableInputStream );
    sis.init(is);
    var output = sis.read(sis.available());
    return output;
  }
}
/**/
function setOpenFileDialog(button) {
  var d=document.createElement("div");
  var e=document.createElement("input");
  d.style.width="32px";
  d.style.height="32px";
  d.style.position="absolute";
  d.style.overflow="hidden";
  d.style.display="inline";
  d.style.cursor="pointer";

  e.style.position="absolute";
  e.style.width="32px";
  e.style.height="32px";
  e.style.left="-180px";
  e.style.opacity="0";
  e.style.filter="alpha(opacity = 0)";
  e.style.msfilter="progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";

  d.style.cursor="pointer";
  e.type="file";
  d.appendChild(e);
  button.parentNode.insertBefore(d,button);
  e.onchange=function(){
    if(window.ActiveXObject) {
      window.selectedFile=this.value;
    } else {
      netscape.security.PrivilegeManager.enablePrivilege('UniversalFileRead');
      window.selectedFile=this.value;
    }
    if(document.createEventObject)
    {
      button.fireEvent('onclick');
      return false;
    }
    else if(document.createEvent)
    {
      var evObj = document.createEvent('MouseEvents');
      evObj.initEvent('click',true,true);
      button.dispatchEvent(evObj);
      return false;
    }
  }
}