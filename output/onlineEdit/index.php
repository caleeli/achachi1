<?php
require('binder.php');
?>
<html>
<head>
</head>
<body>
<script src="jquery-2.1.4.min.js" type="text/javascript"></script>
<div>
LAAAA PAAAZZZ!!!
<textarea <?php bind('click:holas') ?>>HOLA MUNDO</textarea>
<span >HasasdOLA MUNDO</span>
</div><script src="jquery.qtip.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="jquery.qtip.min.css">
<script type="text/javascript">
var onlineDev={
  FILE_SERVICE:"binderRest.php",
  makePreview:function(file, line, callback){
    var min=Math.min(1,line-2);
    var max=line+2;
    $.ajax({
      url: this.FILE_SERVICE,
      data: {file:file,from:min,to:max},
      dataType: "json",
      success:function(lines){
        var l=min;
        var res=[];
        for(var i=0,l=lines.length;i<l;i++){
          res.push((l+i)+"| "+lines[i]);
        }
        var $code=$("<i>Code</i>");
        $code.qtip({
          content:{
            title: file,
            text:"<pre>"+res.join("\n")+"</pre>"
          },
          hide:{
            fixed:true
          }
        });
        callback($code);
      }
    });
  }
};
$(function(){
$("[online-dev]").each(function(){
console.log(this);
  var def=this.getAttribute("online-dev");
  if(!def) return;

  var $title;
  var obj=JSON.parse(def);

  onlineDev.makePreview(obj.file, obj.line, function($codePreview){
    $title=$("<div>"+obj.function+"</div>");
    $title.append($codePreview);
    var $objProperties=$("<div class='online-dev-props'></div>");
    if(obj.function=="bind"){
      obj.args[0].split(",").forEach(function(p){
        var pa=p.split(":");
        var prop=pa[0].trim();
        var value=pa[1].trim();
        var $prop=$("<div class='online-dev-prop'><span>"+prop+"</span><input></div>");
        $prop.find("input").val(value);
        $objProperties.append($prop);
      });
    }
    $(this).qtip({
      content:{
        title: '$title',
        text: '$objProperties'
      }
    });

  });
});
});
</script>
</body>
</html>