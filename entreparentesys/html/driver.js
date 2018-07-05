<?php header('Content-type: text/javascript'); ?>
$.fn.ajaxSubmit=function(callback){
    this.each(function(index){
      var multipart=this.enctype=="multipart/form-data";
      var formData=new FormData(this);
      if(!multipart){
        formData=$(this).serialize();
      }
      $.ajax({
          url:this.action ,
          method:this.method?this.method:'get',
          contentType:multipart?false:'application/x-www-form-urlencoded; charset=UTF-8',
          processData: false,
          data:formData,
          success:function(res){
              if(typeof callback=="function") callback(res);
          }
      });
    });
}
