<div class="components">
<span class="qE input" style="width:80px"><input /></span>
<div id="accordion1" class="qE accordion" style="width:240px">
  <div>
  <h3><a href="#">First</a></h3>
  <div>Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.</div>
  </div>
  <div>
  <h3><a href="#">Second</a></h3>
  <div>Phasellus mattis tincidunt nibh.</div>
  </div>
  <div>
  <h3><a href="#">Third</a></h3>
  <div>Nam dui erat, auctor a, dignissim quis.</div>
  </div>
</div>
<div class="qS accordion">
//#accordion1
$("#accordion1").accordion({ 
  //*accordion_options
   header: "h3"
  ,collapsible: true
  ,active:false
  //*accordion_events
  ,change:function(event, ui){
  }
  //*end
});
</div>
<div id="tabs1" class="qE tabs" style="width:480px">
  <ul>
  <li><a href="#tabs1-1">First</a></li>
  <li><a href="#tabs1-2">Second</a></li>
  <li><a href="#tabs1-3">Third</a></li>
  </ul>
  <div id="tabs1-1">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
  <div id="tabs1-2">Phasellus mattis tincidunt nibh. Cras orci urna, blandit id, pretium vel, aliquet ornare, felis. Maecenas scelerisque sem non nisl. Fusce sed lorem in enim dictum bibendum.</div>
  <div id="tabs1-3">Nam dui erat, auctor a, dignissim quis, sollicitudin eu, felis. Pellentesque nisi urna, interdum eget, sagittis et, consequat vestibulum, lacus. Mauris porttitor ullamcorper augue.</div>
</div>
<div class="qS tabs">
//#tabs1
$("#tabs1").tabs();
</div>
<div class="qE dialog">
<div id="dialog1" title="Dialog Title">
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
</div>
</div>
<div class="qS dialog">
//#dialog1
$('#dialog1').dialog({
  //*dialog_options
  autoOpen: false,
  width: 600,
  buttons: {
    "Ok": function() { 
     $(this).dialog("close"); 
    }, 
    "Cancel": function() { 
     $(this).dialog("close"); 
    } 
  }
  //*dialog_events
});
</div>
<div class="qE verticalContainer" style="width:200px;"></div>
<div class="qE horizontalContainer" style="height:100px;"></div>
<div class="qE div" style="width:80px;height:80px;background-color:lightgreen;"></div>
<div class="qE casilla"></div>
<div class="qE button"><button id="button1">A button element</button></div>
<div class="qS button">
//#button1
$("#button1").button();
</div>
<div class="qE vaEditor" id="editor1"><textarea>a</textarea></div>
<div class="qS vaEditor">
//#editor1
$("#editor1").vaEditor();
</div>
<div class="qE pageflip"><div id='pageflip1'>
	<div> Page 1 </div>
	<div> Page 2 </div>
	<div> Page 3 </div>
</div></div>
<div class="qS pageflip">
//#pageflip1
$("#pageflip1").turn();
</div>
</div>