function loadDefaultEditors(){
 /* text default editor */
    installEditor("default",zpad.create());

 /* component default editor */
    componentsIndex["component"].editor=
    componentsIndex["bcomponent"].editor=
    componentsIndex["def"].editor=function(node){
      changeEditor("componentEditor");
      return currentNode;
    };
    var div;
    var div=document.createElement("div");
    draw3(div,[
      "button",{onclick:function(){
        for(var i=0,l=currentNode.childNodes.length;i<l;i++)
          if(currentNode.childNodes[i].nodeName=="#comment") loadPlugin(currentNode.childNodes[i]);
      }},"Reload Plugin"
    ]);
    installEditor("componentEditor",div);
}
