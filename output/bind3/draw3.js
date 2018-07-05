/** DRAW3 */
/**
 * @param dest HTMLElement Target Object 
 * @param content Object (Array | String) Elements to be inserted 
 * @param beforeOf HTMLElement 
 */
function draw3(dest,content,beforeOf){
  var d=draw3.create([content],dest);
  for(var i=0,l=d.length;i<l;i++){
    if(beforeOf) dest.insertBefore(d[i],beforeOf);
    else dest.appendChild(d[i]);
  }
  return d;
}
/**
 * Returns an Array of DOMNodes
 */
draw3.create=function(arr,dest0){
  var dest=dest0;
  var document=dest && dest.ownerDocument?dest.ownerDocument:window.document;
  var t,d=[];
  var modeCreate=!(arr.length==1 && this.getType(arr[0])=="String");
  for(var i=0,l=arr.length;i<l;i++){
    t=this.getType(arr[i]);
    if(t=="String" && modeCreate){
      if(arr[i]=="#text") {
        if(this.getType(arr[i+1])=="String") dest=document.createTextNode(arr[i=i+1]);
        if(this.getType(arr[i+2])=="String") dest=document.createTextNode(arr[i=i+2]);
        d.push(dest);
      }
      else if(arr[i]=="#comment") {
        if(this.getType(arr[i+1])=="String") dest=document.createComment(arr[i=i+1]);
        if(this.getType(arr[i+2])=="String") dest=document.createComment(arr[i=i+2]);
        d.push(dest);
      }
      else {
        //The next String will be a TextNode
        modeCreate=false;
        var dest=document.createElement(arr[i]);
        if(arr[i]=="style") dest.setAttribute("type", "text/css");
        d.push(dest);
      }
    }
    else if((t=="String" || t=="Number") && !modeCreate) {
      //dest=dest.appendChild(document.createTextNode(arr[i]));
      dest.textContent=arr[i];
      modeCreate=true;
    }
    else if(t=="Node") {
      //If Node is owned by a different Document it will be imported.
      if(document===arr[i].ownerDocument) o=arr[i]; 
      else o=this.create(this.reverse(arr[i]),dest);
      if(modeCreate) d.push(o); else dest.appendChild(o);
      dest=o;
      modeCreate=true;
    }
    else if(t=="NodeList") {
      var dest1=[];
      for(var j=0,k=arr[i].length;j<k;j++) {
        //If Node is owned by a different Document it will be imported.
        if(document===arr[i].item(j).ownerDocument) o=arr[i].item(j); 
        else o=this.create(this.reverse(arr[i].item(j)),dest);
        if(modeCreate) d.push(o); else dest.appendChild(o);
        dest1.push(o);
      }
      dest=dest1;
      dest1=null;
      modeCreate=true;
    }
    else if(t=="Object") {
      this.setAttributes(dest,arr[i]);
      modeCreate=false;
    }
    else if(t=="Array") {
      if(modeCreate) d=d.concat(dest=this.create(arr[i],dest0));
      else dest=draw3(dest,arr[i]);
      modeCreate=true;
    }
  }
  return d;
}
/**
 * Set attributes of a DOMElement(o)
 */
draw3.setAttributes=function(o,atts){
  var type=this.getType(o);
  if(type=="Array" || type=="NodeList") {
    console.debug(o);
    for(var i=0,l=o.length;i<l;i++) {
      console.debug(o[i]);
      this.setAttributes(o[i],atts);
    }
    return;
  }
  for(a in atts){
    if(a=="class") o.className=atts[a];
    else if(typeof(atts[a])=="function") o[a]=atts[a];
    else if(a=="style") {
      if(typeof atts[a]==="string"){
        var splitted = atts[a].split(';');
        for (var i=0, len=splitted.length; i<len; i++) {
          var s=splitted[i].split(':');
          if(s.length>0){
            for(var exp=/-([a-z])/; exp.test(s[0]); 
               s[0]=s[0].replace(exp,RegExp.$1.toUpperCase())){};
            if(s[0])eval("o.style."+s[0]+"='"+s[1]+"'");
          }
        }
      } else if(typeof atts[a]==="object"){
        for (var s in atts[a]) {
          for(var exp=/-([a-z])/; exp.test(s); 
             s=s.replace(exp,RegExp.$1.toUpperCase())){};
          if(s)eval("o.style."+s+"='"+atts[a][s]+"'");
        }
      }
    }
    else {
      if(typeof(o.setAttribute)!="undefined") o.setAttribute(a,atts[a]);
    }
  }
}
/**
 * Return the class of an Object(o)
 */
draw3.getType=function(o){
  if(typeof(o)=="string") return "String";
  if((typeof jQuery != "undefined") && (o instanceof jQuery)) return "Array";
  if ( typeof Node === "object" ? o instanceof Node : 
    typeof o === "object" && o!==null  && typeof o.nodeType === "number" && typeof o.nodeName==="string"
  ) return "Node";
  if (typeof o === "object" && o!==null 
    && typeof o.length == 'number' 
    && typeof o.item == 'function'
    && typeof o.nextNode == 'function'  
    && typeof o.reset == 'function'
  ) return "NodeList";
  return Object.prototype.toString.call(o).split(" ")[1].split("]")[0];
}
/**
 * Convert DOM Node to a draw3 Array
 */
draw3.reverse=function(dom){
  var type=this.getType(dom);
  var r=[];
  if(type=="Node"){
    var atts={},chs=[];
    if(dom.attributes){
      for(var i=0,l=dom.attributes.length;i<l;i++){
        atts[dom.attributes[i].nodeName]=dom.attributes[i].value;//nodeValue warning
      }
    }
    if(dom instanceof Text){
      chs=dom.textContent;
    } else if(dom.childNodes){
      chs=chs.concat(this.reverse(dom.childNodes));
    }
    r.push(dom.nodeName,atts,chs);
  } else if(type=="NodeList"){
    for(var j=0,k=dom.length;j<k;j++) r=r.concat(this.reverse(dom.item(j)));
  }
  return r;
}
