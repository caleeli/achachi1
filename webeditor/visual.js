$.fn.loadFrom=function loadHTML(options) {
  var target=$(this);
  options.dataType="text";
  var __success= options.success;
  options.success=function(data){
    data=data.split("<html").join("<root");
    data=data.split("html>").join("root>");
    data=data.split("<head").join("<adhe");
    data=data.split("head>").join("adhe>");
    data=data.split("<link").join("<inkl");
    data=data.split("link>").join("inkl>");
    data=data.split("<body").join("<doby");
    data=data.split("body>").join("doby>");
    data=data.split("<script").join("<ecript");
    data=data.split("script>").join("ecript>");
    var html=$(data);
    var script = html.find("#jsloader");
    var body = html.find("doby");
    target.empty();
    body.children().each(function(){
      target.append(this);
    });
    eval(script.text());
    if(typeof(__success)=="function") __success();
  };
  $.ajax(options);
}

var qw = function(s){return '"' + s.replace(/"/g,'&quot;') + '"'};
$.fn.xhtml = function () {return $.xhtml(this[0])};
$.xhtml = function(obj) { // dump the dom back to xhtml text
  if (!obj) return "(null)";
  var ee;
  var res = "";
  var tag = obj.nodeName.toLowerCase();
  var tagShow = tag.charAt(0) != "#";
  if (tagShow) res += '<' + tag;
  if (obj.attributes) 
    res += $.map(obj.attributes,function(attr){
      if (attr.specified && attr.name.charAt(0) != '$') 
        return ' '+attr.name.toLowerCase() + '=' + qw(htmlentities(attr.value)) 
    }).join('');
  if (tagShow && obj.nodeValue == null && !obj.hasChildNodes())
    return res+" />";
  if (tagShow)
    res+= ">";
  if (obj.nodeType == 8)
    res += "<!--" + obj.nodeValue + "-->";
  else if (obj.nodeValue != null) {
    if(!ee) ee = document.createElement("div");
    ee.appendChild(obj.cloneNode(true));
    res +=  ee.innerHTML;
    ee.removeChild(ee.firstChild);
  }
  if (obj.hasChildNodes && obj.hasChildNodes())
    res += $.map(obj.childNodes,function(child){return $.xhtml(child)}).join('');
  if (tagShow)  res += '</' + tag + '>';
  return res
};
var htmlentities=function(code){
  if(!htmlentities.ee) {
    htmlentities.ee=document.createElement("div");
    htmlentities.ee.appendChild(document.createTextNode("code"));
  }
  htmlentities.ee.firstChild.nodeValue=code;
  return htmlentities.ee.innerHTML;
}
var $vaEditor={
  resize:[],
  dic:["class","function"],
  idx:0
};
setInterval(function(){
  if($vaEditor.resize.length)$($vaEditor.resize).vaEditor("resize");
  $vaEditor.resize.length=0;
},200);
$.vaEditor_keydown=function(){
  if($vaEditor.resize.indexOf(this.parentNode)==-1) {
    $vaEditor.resize.push(this.parentNode);
  }
};
$.vaEditor_keypress=function(e){
  var $e=$(this);
  var sel=$e.getSelection();
  var prev=this.value.substr(0,sel.start);
  var post=this.value.substr(sel.start);
  var ma0 = prev.match(/\b\w*$/);
  var ma1 = post.match(/^\w*\b/);
  ma0 = ma0?ma0[0]:"";
  ma1 = ma1?ma1[0]:"";
  var w = ma0+ma1;
  var ops = [];
  if(w){
    for(var i=0,l=$vaEditor.dic.length;i<l;i++){
      if($vaEditor.dic[i].substr(0,w.length)==w) ops.push($vaEditor.dic[i]);
    }
  }
  console.log("===============");
  console.log(ops);
  if(e.keyCode==9){
    $vaEditor.idx++;
  }
  if(isNaN($vaEditor.idx)) $vaEditor.idx=0;
  $vaEditor.idx = $vaEditor.idx % ops.length;
  console.log("------------------");
  if(!isNaN($vaEditor.idx))console.log(ops[$vaEditor.idx],$vaEditor.idx);
};
$.fn.vaEditor=function(data){
  if(data==="resize"){
    var pre=$('<pre style="position:absolute;"></pre>');
    var txta=this.find("textarea");
    pre.append(document.createTextNode(txta[0].value));
    this.append(pre);
    var w=pre.width() + 33 + 8 *3;
    var h=pre.height() + 13 + 16*2;
    pre.remove();
    txta.width(w);
    txta.height(h);
  } else {
    var txta=this.find("textarea")
    txta.attr("wrap","OFF");
    this.addClass("vaEditor");
    this.vaEditor("resize");
    txta.keydown($.vaEditor_keydown);
    txta.keyup($.vaEditor_keypress);
    this.scroll(function(){
      var $e=$(this);
      var left=$e.scrollLeft();
      if(left<=33) $e.scrollLeft(0); //else $e.scrollLeft(left+8);
    });
  }
}
