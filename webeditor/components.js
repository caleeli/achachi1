components=[
  {"name":"root","attributes":[],"image":"images/16/root.gif"},
  {"name":"definition","attributes":[{"name":"version","value":"1.0","domain":"string"}]},
  {"name":"def","attributes":[{"name":"name","value":"newDefinition","domain":"string"}],"image":"images/16/default.gif"},
  {"name":"function","attributes":[{"name":"name","value":"newFunction","domain":"string"}],"image":"images/16/function.gif"},
  {"name":"application","attributes":[{"name":"name","value":"newapplication","domain":"string"}],"image":"images/16/application.gif"},
  {"name":"component","attributes":[{"name":"name","value":"newComponent","domain":"string"}],"image":"images/16/component.gif","saver":function(n){structure_node_component_add(n);return currentNode;}},
  {"name":"bcomponent","attributes":[{"name":"name","value":"newBComponent","domain":"string"}],"image":"images/16/default.gif"},
  {"name":"def","attributes":[{"name":"name","value":"newDef","domain":"string"}],"image":"images/16/default.gif"},
  {"name":"clone","attributes":[{"name":"value","value":"${$_nodes['nodeName']}","domain":"string"}],"image":"images/16/clone.jpg"},
  {"name":"view","attributes":[{"name":"name","value":"newview","domain":"string"}],"image":"images/16/view.gif"},
  {"name":"action","attributes":[{"name":"name","value":"newaction","domain":"string"},{"name":"noRender","value":"false","domain":"_boolean"}],"image":"images/16/action.gif"},
  {"name":"controller","attributes":[{"name":"name","value":"newcontroller","domain":"string"}],"image":"images/16/controller.gif"},
  {"name":"php","attributes":[],"image":"images/16/php.gif"},
  {"name":"connection","attributes":[],"image":"images/16/connection.png"},
  {"name":"table","attributes":[{"name":"name","value":"newTable","domain":"string"}],"image":"images/16/table.gif"},
  {"name":"resource","attributes":[{"name":"path","value":"path","domain":"string"},{"name":"folder","value":"resources","domain":"string"}],"image":"images/16/default.gif"},
  {"name":"include","attributes":[{"name":"src","value":"filepath.xml","domain":"string"}],"image":"images/16/library.png"},
  {"name":"file","attributes":[{"name":"name","value":"filename","domain":"string"}],"image":"images/16/view.gif"},
  {"name":"jqueryui","attributes":[{"name":"name","value":"filename","domain":"string"}],"image":"images/16/view.gif"},
  {"name":"#text","image":"images/16/_text.gif"}
];
var _boolean=["true","false"];