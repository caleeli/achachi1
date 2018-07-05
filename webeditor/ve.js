var components;
var htmlBase;
var jsBase="";
var qid=0;
$(function(){
  $("#codeEditorTabs").tabs({selected:0});
  $.ajax({url:"vecomponents.php",dataType:"html",
    success:function(res){
      components = $(res);
      ve.onload();
    }
  });
  $("body").keyup(function(event){
    var keyCode = window.event ? window.event.keyCode : event.which;
    if(keyCode == 113) ve.save();
    ve.editingCode = $("#codeJsBaseEditor").dialog("isOpen")===true || $("#codeEditor").dialog("isOpen")===true;
    if(!ve.editingCode) if((keyCode == 46) && (ve.selected)) ve.remove(ve.selected);
  });
  $("body").append('<div id="vaFile" style="padding:0px;"/>');
  window.accordionVaFileFilter="*.xml";
  $("#vaFile").dialog({
    autoOpen: false
    ,title:"Open file"
    ,width:600
    ,height:350
    ,buttons: {
      "Ok": function() { 
       $(this).dialog("close"); 
      }, 
      "Cancel": function() { 
       $(this).dialog("close"); 
      }
    }
    ,open:function(event, ui){
      $(this).loadFrom({url:"va_files.php",data:{f:window.accordionVaFileFilter}});
    }
  });
});
$.fn.visualElement=function(e){
  this.append('<div class="qN"></div>');
  this.draggable({grid:[8,8]});
  this.dblclick(ve.editCode);
  this.click(ve.selectElement);
};
$.fn.visualElementUpdate=function(){
  this.each(function(){
    htmlBase.find("[qid="+$(this).attr("qid")+"]").attr("style", $(this).attr("style"));
  });
};
var ve = {
  editingCode:false,
  /**
   * Captura de opciones y eventos de la documentacion
   * var res=[];$(".option-name").each(function(){res.push(this.textContent+":"+$(this.parentNode).find(".option-default").text())});JSON.stringify(res);
   * var res=[];$(".event-name").each(function(){res.push(this.textContent+":function(event, ui){}")});res
   */
  defComponents:{
    "tabs_options":["disabled:false","ajaxOptions:null","cache:false","collapsible:false","cookie:null","deselectable:false","disabled:[]","event:\"click\"","fx:null","idPrefix:\"ui-tabs-\"","panelTemplate:\"<div></div>\"","selected:0","spinner:\"<em>Loading&#8230;</em>\"","tabTemplate:\"<li><a href=\"#{href}\"><span>#{label}</span></a></li>\""],
    "tabs_events":["create:function(event, ui){}","select:function(event, ui){}","load:function(event, ui){}","show:function(event, ui){}","add:function(event, ui){}","remove:function(event, ui){}","enable:function(event, ui){}","disable:function(event, ui){}"],
    "accordion_options":["disabled:false","active:first child","animated:\"slide\"","autoHeight:true","clearStyle:false","collapsible:false","event:\"click\"","fillSpace:false","header:\"> li > :first-child,> :not(li):even\"","icons:{ \"header\": \"ui-icon-triangle-1-e\", \"headerSelected\": \"ui-icon-triangle-1-s\" }","navigation:false","navigationFilter: "],
    "accordion_events":["create:function(event, ui){}","change:function(event, ui){}","changestart:function(event, ui){}"],
    "dialog_options":["disabled:false","autoOpen:true","buttons:{ }","buttons:[ ]","closeOnEscape:true","closeText:\"close\"","dialogClass:\"\"","draggable:true","height:\"auto\"","hide:null","hide:null","maxHeight:false","maxWidth:false","minHeight:150","minWidth:150","modal:false","position:\"center\"","resizable:true","show:null","show:null","stack:true","title:\"\"","width:300","zIndex:1000"],
    "dialog_events":["create:function(event, ui){}", "beforeClose:function(event, ui){}", "open:function(event, ui){}", "focus:function(event, ui){}", "dragStart:function(event, ui){}", "drag:function(event, ui){}", "dragStop:function(event, ui){}", "resizeStart:function(event, ui){}", "resize:function(event, ui){}", "resizeStop:function(event, ui){}", "close:function(event, ui){}"]
  },
  checkId:function(html,code){
    if(!code) return [html,code];
    var aBase = ve.splitJsCode(jsBase);
    var ma;
    ma=code.match(/\/\/#(\w+)/);
    if(ma){
      var id0=id=ma[1],i=1;
      while((ma=code.match(/\/\/#(\w+)/)) && typeof(aBase[ma[1]])!="undefined"){
        code = code.replace(new RegExp(ma[1],"g"),ma[1].match(/[^\d]+/)[0]+i);
        html = html.replace(new RegExp(ma[1],"g"),ma[1].match(/[^\d]+/)[0]+i);
        i++;
      }
      id=ma[1];
      var newId;
      if(id!=(newId=prompt("Id",ma[1]))){
        code = code.replace(new RegExp(ma[1],"g"),newId);
        html = html.replace(new RegExp(ma[1],"g"),newId);
      }
    }
    return [html,code];
  },
  removeJsCode:function(jid,aBase){
    var local = typeof(aBase)!="undefined";
    if(!local) aBase = ve.splitJsCode(jsBase);
    for(var i=0,l=jid.length;i<l;i++){
      delete aBase[jid[i]];
    }
    if(!local) jsBase = ve.joinJsCode(aBase);
  },
  getJsCode:function(jid,aBase){
    if(typeof(aBase)=="undefined") aBase = ve.splitJsCode(jsBase);
    var script = "";
    for(var i=0,l=jid.length;i<l;i++){
      script+=aBase[jid[i]]==null?"":aBase[jid[i]];
    }
    return script;
  },
  setJsCode:function(code,aBase){
    var local = typeof(aBase)!="undefined";
    if(!local) aBase = ve.splitJsCode(jsBase);
    var aCode = ve.splitJsCode(code);
    var jid = [];
    for(var i in aCode){
      if(i!="0" && i!="1") jid.push(i);
      if(i!="0" && i!="1"){
        aBase[i] = aCode[i];
      }
    }
    var b1 = aBase["1"];
    delete aBase["1"];
    aBase["1"] = b1;
    if(!local) jsBase = ve.joinJsCode(aBase);
    return jid;
  },
  setSelected:function(e){
    ve.selected = e;
    $(".qNSelected").removeClass("qNSelected");
    $(e).find(".qN").addClass("qNSelected");
  },
  appendJsCode:function(code){
    var aBase = ve.splitJsCode(jsBase);
    var aCode = ve.splitJsCode(code);
    var jid = [];
    for(var i in aCode){
      if(i!="0" && i!="1") jid.push(i);
      if(typeof(aBase[i])=="undefined"){
        aBase[i] = aCode[i];
      }
    }
    var b1 = aBase["1"];
    delete aBase["1"];
    aBase["1"] = b1;
    jsBase = ve.joinJsCode(aBase);
    return jid;
  },
  joinJsCode:function(aBase){
    var s="";
    for(var i in aBase){
      s+=aBase[i]==null?"":aBase[i];
    }
    return s;
  },
  splitJsCode:function(code,tags){
    if(!code) return [];
    if(typeof(tags)=="undefined") tags=":|#";
    var aBase={};
    //  /\/\/(:|#)(\w+)|(\/\/qend\/\/)/
    var a = code.split(new RegExp("\\/\\/("+tags+")(\\w+)|(\\/\\/qend\\/\\/)"));
    aBase["0"] = a[0];
    for(var i=2,l=a.length;i<l;i=i+4){
      if(a[i+1]){
        aBase["1"] = a[i+1] + a[i+2];
      } else if(typeof(aBase[a[i]])=="undefined"){
        aBase[(a[i-1]==":"?":":"") + a[i]] = "//" + a[i-1] + a[i] + a[i+2];
      }
    }
    return aBase;
  },
  editJsBase:function(){
    //Para no borrar el elemento cuando se pulse Supr
    //ve.setSelected(null);
    ve.editingCode=true;
    $("#editCodeJsBase").find("textarea")[0].value=jsBase;
    $("#codeJsBaseEditor").dialog({width:600,height:400,buttons:[
      { text:"ok",
        click:function(){
          jsBase = $("#editCodeJsBase").find("textarea")[0].value;
          eval(jsBase);
          $(this).dialog("close");
        },
        close:function(){
          ve.editingCode=false;
        }
      }
    ]});
  },
  appendElement:function(elementId){
    var e = components.find(".qE."+elementId);
    var s = components.find(".qS."+elementId);
    var w = $("#workspace");
    var checked = ve.checkId(ve.outterHtml(e), s.html());
    var html=checked[0];
    var script=checked[1];
    var e1 = $(html);
    e1.attr("qid",++qid);
    w.append(e1);
    var e2=e1.clone();
    htmlBase.append(e2);
    var jid = ve.appendJsCode(script);
    e2.attr("jid", jid.join(" "));
    $(w[0].lastChild).attr("jid", jid.join(" "));
    $(w[0].lastChild).visualElement();
    window.eval(script);
    ve.refreshElementList();
  },
  append:function(html){
    var w = $("#workspace");
    w.append(html);
    var e1=$(w[0].lastChild);
    e1.attr("qid",++qid);
    htmlBase.append(e1.clone());
    e1.visualElement();
    ve.refreshElementList();
  },
  remove:function(e){
    if(e.getAttribute("jid")) ve.removeJsCode(e.getAttribute("jid").split(" "));
    htmlBase.find("[qid="+e.getAttribute("qid")+"]").remove();
    $(e).remove();
    ve.refreshElementList();
  },
  save:function(){
    $(".qE").draggable("destroy");
    $(".qN").remove();
    $(".qE").visualElementUpdate();
    var html="";
    htmlBase.children().each(function(){
      var $e=$(this);
      var qid=$e.attr("qid");
      //var jid=$e.attr("jid");
      $e.removeAttr("qid");
      //$e.removeAttr("jid");
      html+=$e.xhtml();
      $e.attr("qid", qid);
      //$e.attr("jid", jid);
    });
    $.ajax({
      url:"vesave.php",
      type:"POST",
      data:{"fileName":ve.fileName,"body":html,"jsloader":jsBase,fileContent:(parent.currentNode.firstChild?parent.currentNode.firstChild.nodeValue:"")},
      dataType:"text",
      success:function(r){
        eval(r);
      }
    });
    $(".qE").visualElement();
  },
  refreshElementList:function(){
    $("#elementList").empty();
    $("#elementList").focus();
  },
  createToolbar:function(){
    //$("#toolbar").append('<button type="button" onclick="$(\'#vaFile\').dialog(\'open\');">Open file</button>');
    $("#toolbar").append('<select id="elementList"></select>');
    $('#elementList').focus(function(){
      if(this.childNodes.length<=2) {
        var sel=$(this);
        sel.empty();
        sel.append('<option value=""></option>');
        sel.append('<option value="load">load...</option>');
        htmlBase.find(".qE").each(function(){
          var qid = $(this).attr("qid");
          var jid = $(this).attr("jid");
          sel.append('<option value="'+qid+'">'+qid+'('+jid+')</option>');
        });
      }
      return true;
    });
    $("#elementList").change(function(){
      if(this.value=="") return;
      if(this.value=="load") {$(this).empty();$(this).focus();return true;}
      var qid = this.value;
      ve.setSelected( $("#workspace").find("[qid="+qid+"]")[0] );
    });
    $("#toolbar").append('<button type="button" onclick="if(ve.selected) $(ve.selected).dblclick();">Edit</button>');
    components.find(".qE").each(function(){
      var className = this.className.match(/^\w+ (\w+)/)[1];
      $("#toolbar").append('<button type="button" onclick="ve.appendElement(\''+className+'\')">'+className+'</button>');
    });
    $("#toolbar").append('<button type="button" onclick="ve.editJsBase()">Edit JavaScript</button>');
  },
  selectElement:function(){
    ve.setSelected( this );
    if(this.getAttribute("qid")) $("#elementList")[0].value=this.getAttribute("qid");
  },
  outterHtml:function(e){
    //return $('<div />').append(e.clone()).remove().xhtml();
    return e.xhtml();
  },
  editCode:function(ev){
    //Para no borrar el elemento cuando se pulse Supr
    //ve.setSelected(null);
    ve.editingCode=true;
    $(this).visualElementUpdate();
    var html=ve.outterHtml(htmlBase.find("[qid="+this.getAttribute("qid")+"]"));
    var elem = this;
    var script = this.getAttribute("jid")!=null ? ve.getJsCode(this.getAttribute("jid").split(" ")):"";
    $("#codeEditorTabs").tabs("select",0);
    $("#editCode").find("textarea")[0].value=html;
    $("#editCodeJs").find("textarea")[0].value=script;
    $("#codeEditor").dialog({width:600,height:400,buttons:[
      { text:"ok",
        click:function(){
          var q=elem.getAttribute("qid");
          $(elem).replaceWith($("#editCode").find("textarea")[0].value);
          htmlBase.find("[qid="+q+"]").replaceWith($("#editCode").find("textarea")[0].value);
          var jid=ve.setJsCode($("#editCodeJs").find("textarea")[0].value);
          $("#workspace").find("[qid="+q+"]").attr("jid",jid.join(" "));
          htmlBase.find("[qid="+q+"]").attr("jid",jid.join(" "));
          eval(jsBase);
          $("#workspace").find("[qid="+q+"]").visualElement();
          $("#workspace").find("[qid="+q+"]").click();
          $(this).dialog("close");
        },
        close:function(){
          ve.editingCode=false;
        }
      }
    ]});
  },
  onload:function(){
    $(".qE").each(function(){
      this.setAttribute("qid",++qid);
    });
    htmlBase=$("#workspace").clone();
    ve.loadJsBase();
    $(".qE").visualElement();
    ve.createToolbar();
    ve.refreshElementList();
    $(".selectJsCode").focus(function(){
      if(this.childNodes.length<=2) {
        var sel=$(this);
        sel.empty();
        sel.append('<option value=""></option>');
        sel.append('<option value="load">load...</option>');
        var a = ve.splitJsCode($("#editCodeJs textarea")[0].value,"\\*");
        for(var id in a)if(id!="0" && id!="end" && typeof(ve.defComponents[id])!="undefined"){
          for(var i=0,l=ve.defComponents[id].length;i<l;i++){
            var ss=ve.defComponents[id][i].split(":");
            var name=ss[0] + (ss[1].substr(0,8)=="function"?"()":"");
            sel.append('<option value="'+id+'.'+i+'">'+name+'</option>');
          }
        }
      }
      return true;
    });
    $(".selectJsCode").change(function(){
      if(this.value=="") return;
      if(this.value=="load") {$(this).empty();$(this).focus();return true;}
      var v=this.value.split(".");
      var id=v[0];
      var i=v[1];
      var a = ve.splitJsCode($("#editCodeJs textarea")[0].value,"\\*");
      var emp = a[id].match(/^\/\/\*\w+(\s*(\/\*[\w\W]*?\*\/|\/\/.*)?)*\s*$/);
      var co = a[id].match(/,(\s*(\/\*[\w\W]*?\*\/|\/\/.*)?)*\s*$/);
      a[id]+=""+((!co && !emp)?",":"")+ve.defComponents[id][i]+(co?",":"")+"\n  ";
      $("#editCodeJs textarea")[0].value = ve.joinJsCode(a);
    });
  }
}
