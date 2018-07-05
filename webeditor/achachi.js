var achachi={
  components:{},
  join:function(glue,arr){var res=[];for(var _ in arr){var a=arr[_];if(a[1]!=null && a[1]!="")res.push(a[1]);}return res.join(glue)},
  content:function(arr,glue){if(!glue)glue="";return this.join(glue,arr)},
  evalString:function(__str,__values0){
    /*Add $ to values*/
    var __values={};
    for(var __ in __values0){
      __values['$'+__]=__values0[__];
    }
    /**/
    var node={
      join:this.join,
      content:this.content,
      _nodes:function(childNodes,nodeName){
      //used in ext.grid
        var $arr=[];
        for(var i=0,l=childNodes.length;i<l;i++)
        {
          if(nodeName=='' || childNodes[i].nodeName==nodeName){
            $arr.push([childNodes[i].nodeName, achachi.getValues(childNodes[i],false)]);
          }
        }
        return $arr;
      }
    };
    var var_dump=console && console.debug ? console.debug : Z.console;
    __str=__str.split("node::").join("node.");
    __str=__str.split("->").join(".");
    __str=__str.split("isset(").join("('undefined'!=typeof ");
    __str=__str.split("array()").join("[]");
    //try{
      __str=__str.replace(/@{([^\}]+)\}/g,function(m0,m1){m1=m1.split("@").join("");with(__values){return eval(m1)}});
      __str=__str.replace(/\${([^\}]+)\}/g,function(m0,m1){m1=m1.split("@").join("");with(__values){return JSON.stringify(eval(m1))}});
      __str=__str.replace(/#{([^\}]+)\}/g,function(m0,m1){m1=m1.split("@").join("");with(__values){return eval("(function(){"+m1+"})()")}});
    /*}catch(e){
      if(console && console.debug) {
        console.debug("Error Evaluating:");
        console.debug(e);
        console.debug(__str);
        console.debug(__values);
      }
      else {Z.console(e);Z.console(__str);}
    }*/
    return this.autoCast(__str);
  },
  evalNode:function(node,values,evalChildNodes){
    if(node.attributes) for(var i=0,l=node.attributes.length;i<l;i++){
      node.attributes[i].nodeValue=this.evalString(node.attributes[i].nodeValue,values);
    }
    if(node.nodeValue){
      node.nodeValue=this.evalString(node.nodeValue,values);
    }
    if(!evalChildNodes){
      if(node.nodeName=="foreach") return node;
      if(node.nodeName=="component") return node;
      if(node.attributes && node.getAttribute("_hasContext")==="true") return node;
    }
    if(node.childNodes) for(var i=0,l=node.childNodes.length;i<l;i++){
      this.evalNode(node.childNodes[i],values);
    }
    return node;
  },
  getValues:function(node, evalChildNodes){
    var values={"_attributes":{},"_":[],"e":node,"_e":node,"_nodes":{},"_values":values},ch;
    if(node.attributes)for(var i=0,l=node.attributes.length;i<l;i++) {
      values[""+node.attributes[i].nodeName]=node.attributes[i].nodeValue;
      values["_attributes"][""+node.attributes[i].nodeName]=node.attributes[i].nodeValue;
    }
    if(evalChildNodes) if(node.childNodes)for(var i=0,l=node.childNodes.length;i<l;i++) ch=this.create(node.childNodes[i]),values["_"]=ch?values["_"].concat(ch):values["_"];
    for(var i=0,l=values["_"].length;i<l;i++){
      if(!values["_nodes"][values["_"][i][0]])values["_nodes"][values["_"][i][0]]=[];
      values["_nodes"][values["_"][i][0]].push(values["_"][i]);
    }
    return values;
  },
  create:function(node){
    if(typeof(achachi.components[node.nodeName])!="undefined"){
      return achachi.components[node.nodeName](node);
    } else {
      return [];
    }
  },
  autoCast:function(e){
    if(e==parseFloat(e))return parseFloat(e);
    if(e=="true") return true;
    if(e=="false") return false;
    return e;
  },
  findParent:function(node,nodeName){
    while(node && node.parentNode && node.parentNode.nodeName!=nodeName) {
      node=node.parentNode;
    }
    if(!node) return null;
    return node.parentNode;
  }
}
