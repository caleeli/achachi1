function __bind3(__obj,__white,__black,__repeatWhite,__repeatBlack){
  //Object binding and scope
  var __;
  for(__a in __obj) {
    var __type=draw3.getType(__obj[__a]);
    if(__type==="Array"){
      observeArray(__obj[__a]);
    }
    eval("var "+__a+"=this."+__a+"=__obj[__a]");
    var def;
    eval("def={"+
      "get:function(){return "+__a+";},"+
      "set:function(__newValue){"+(__type==="Array"?"observeArray(__newValue);":"")+__a+"=__newValue;refresh('"+__a+"');},"+
      "enumerable: true,"+
      "configurable: true"+
    "};");
    Object.defineProperty(__obj, __a, def);
  }
  function observeArray(array){
    array.indexOf = function (){
      var $res=Array.prototype.indexOf.apply(this,arguments);
      refresh();
      return $res;
    }
    array.slice = function (){
      var $res=Array.prototype.slice.apply(this,arguments);
      refresh();
      return $res;
    }
    array.push = function (){
      var $res=Array.prototype.push.apply(this,arguments);
      refresh();
      return $res;
    }
    array.pop = function (){
      var $res=Array.prototype.pop.apply(this,arguments);
      refresh();
      return $res;
    }
    array.shift = function (){
      var $res=Array.prototype.shift.apply(this,arguments);
      refresh();
      return $res;
    }
    array.unshift = function (){
      var $res=Array.prototype.unshift.apply(this,arguments);
      refresh();
      return $res;
    }
    array.reverse = function (){
      var $res=Array.prototype.reverse.apply(this,arguments);
      refresh();
      return $res;
    }
    array.sort = function (){
      var $res=Array.prototype.sort.apply(this,arguments);
      refresh();
      return $res;
    }
    array.splice = function (){
      var $res=Array.prototype.splice.apply(this,arguments);
      refresh();
      return $res;
    }
  }
  var $data=__obj;
  //get repeat template
  var __repeat3base={};
  for(var __i=0,__l=__repeatWhite.length;__i<__l;__i++){
    if(__repeatBlack===null || Array.prototype.indexOf.call(__repeatBlack, __repeatWhite[__i])===-1){
    __repeat3base[__repeatWhite[__i]]=__repeatWhite[__i].innerHTML;//draw3.reverse(__repeatWhite[__i].childNodes);
    }
  }
  var __whiteValues=[];
  function refresh(__attribute){
    //white-black
    for(var __i=0,__l=__white.length;__i<__l;__i++){
      if(__black===null || Array.prototype.indexOf.call(__black, __white[__i])===-1){
        var __current;
        eval("__current="+__white[__i].getAttribute("bind3")+";");
        var __currentJSON=JSON.stringify(__current);
        if(__currentJSON!==__whiteValues[__i]){
          bind3.config3(__white[__i],__current);
          __whiteValues[__i]=__currentJSON;
        }
      }
    }
    //repeat
    for(var __i=0,__l=__repeatWhite.length;__i<__l;__i++){
      if(__repeatBlack===null || Array.prototype.indexOf.call(__repeatBlack, __repeatWhite[__i])===-1){
        var __bind;
        eval("__bind="+__repeatWhite[__i].getAttribute("repeat3"));
        __repeatWhite[__i].innerHTML='';
        for(var __j=0,__k=__bind.length;__j<__k;__j++){
          var __dom3=bind3.draw3(__repeatWhite[__i],__repeat3base[__repeatWhite[__i]]);
          __bind[__j].$root=__obj;
          bind3(__bind[__j], __dom3);
        }
      }
    }
  }
  refresh();
}
function bind3(__obj,dom){
  var type=draw3.getType(dom);
  if(type==="Node"){
    var __white=dom.querySelectorAll("[bind3]");
    var __black=dom.querySelectorAll("[repeat3] [bind3]");
    var __repeatWhite=dom.querySelectorAll("[repeat3]");
    var __repeatBlack=dom.querySelectorAll("[repeat3] [repeat3]");
    __bind3(__obj,__white,__black,__repeatWhite,__repeatBlack);
  } else if(type==="NodeList" || type==="Array"){
    var __white=[];
    for(var __i=0,__l=dom.length;__i<__l;__i++){
      if(typeof dom[__i].getAttribute==="function" && dom[__i].getAttribute("bind3")) {
        __white.push(dom[__i]);
      }
      if(typeof dom[__i].querySelectorAll==="function") {
        bind3.concat(__white,dom[__i].querySelectorAll("[bind3]"));
      }
    }
    var __black=[];
    for(var __i=0,__l=dom.length;__i<__l;__i++){
      if(typeof dom[__i].querySelectorAll==="function") {
        bind3.concat(__black,dom[__i].querySelectorAll(":scope[repeat3] [bind3]"));
      }
    }
    var __repeatWhite=[];
    for(var __i=0,__l=dom.length;__i<__l;__i++){
      if(typeof dom[__i].getAttribute==="function" && dom[__i].getAttribute("repeat3")){
        __repeatWhite.push(dom[__i]);
      }
      if(typeof dom[__i].querySelectorAll==="function") {
        bind3.concat(__repeatWhite,dom[__i].querySelectorAll("[repeat3]"));
      }
    }
    var __repeatBlack=[];
    for(var __i=0,__l=dom.length;__i<__l;__i++){
      if(typeof dom[__i].querySelectorAll==="function") {
        bind3.concat(__repeatBlack,dom[__i].querySelectorAll(":scope[repeat3] [repeat3]"));
      }
    }
    __bind3(__obj,__white,__black,__repeatWhite,__repeatBlack);
  }
}
/**
 * array1=array1 concat array2
 * @var Array array1
 * @var Array|NodeList array2
 */
bind3.concat=function(array1, array2){
  for(var i=0,l=array2.length;i<l;i++){
    array1.push(array2[i]);
  }
}
bind3.reverse=function(nodeList){
  var html=[];
  for(var i=0,l=nodeList.length;i<l;i++){
    html.push();
  }
}
bind3.draw3=function(dom,innerHTML){
  var div=dom.ownerDocument.createElement("div");
  div.innerHTML=innerHTML;
  var res=[];
  while(div.childNodes.length>0){
    res.push(div.firstChild);
    dom.appendChild(div.firstChild);
  }
  return res;
}
bind3.config3=function(dom,attributes){
  draw3(dom, attributes);
}
