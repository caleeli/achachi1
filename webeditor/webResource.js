  function get_xmlhttp() {
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp = false;
      }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
  }
  function ajax_call(uri,parameters,method,responseText)
  {
    var request;
    request = get_xmlhttp();
    var response;
    try
    {
    	if (!method ) method ="POST";                                                           
    	data = parameters;
    	var qmark=(uri.split('?').length==1)?'?':'&';
    	request.open( method, uri + ((method==='GET' && parameters)?(qmark+data): '') , false);
      if (method==='POST') request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      request.send(((method==='GET')? null : data));
  	}catch(ss)
  	{
  		alert("error: "+ss.message);
  	}
  	if(responseText) return request.responseText
    return request.responseXML;
  }

