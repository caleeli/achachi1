$(function(){
    $("[ajaxUrl]").each(function(){
        var $this=$(this);
        $.ajax({
            url:$this.attr("ajaxUrl"),
            success:function(res){
                eval(res);
            }
        });
    });
	$("#diagramCode").keyup(function(){
		if(diagramCodeTimeout) clearTimeout(diagramCodeTimeout);
		var me=this;
		diagramCodeTimeout=setTimeout(function(){
			if(diagramCodeValue==me.value) return;
			diagramCodeValue=me.value;
			$.fn.exDiagram.draw("diagram", diagramCodeValue);
		},500);
	});
});
$.fn.getClasses = function(){
    var ca = this.attr('class');
    var rval = [];
    if(ca && ca.length && ca.split){
        ca = jQuery.trim(ca); /* strip leading and trailing spaces */
        ca = ca.replace(/\s+/g,' '); /* remove doube spaces */
        rval = ca.split(' ');
    }
    return rval;
}
$.fn.doClasses = function(data){
    var classes= this.getClasses();
    for(var i= 0,l=classes.length;i<l;i++){
        if(eval("typeof($.fn."+classes[i]+")")=="function"){
            console.log("this."+classes[i]+"(data)");
            eval("this."+classes[i]+"(data)");
        }
    }
}
$.fn.exTree=function(data){
    var html=[];
    $.fn.exTree.draw(html, data);
    this.append(html.join(""));
}
$.fn.exTree.draw=function(html, data){
    html.push("<ul>");
    for(var o in data){
        html.push("<li>"+o+"</li>");
    }
    html.push("</ul>");
}
var diagramCodeTimeout;
var diagramCodeValue;
$.fn.exDiagram=function(data){
	$("#diagramCode").val(data);
	diagramCodeValue=data;
	$.fn.exDiagram.draw("diagram", diagramCodeValue);
}
$.fn.exDiagram.draw=function(id, code){
	$("#"+id).empty();
	//var diagram = Diagram.parse(code);
	//diagram.drawSVG(id, {theme: 'simple'});
}
function run(){
	$.ajax({
		url:"exploreRun.php",
		method:"post",
		data:{code:$("#diagramCode").val()},
		success:function(res){
		var pre=document.createElement("pre");
		pre.appendChild(document.createTextNode(res));
			$("#diagram").html(pre);
		}
	});
}
