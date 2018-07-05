var OB={
  grilla:function(objeto,callback){
    $(function(){
      var url="http://172.16.5.195/openbank1/public/index.php/grilla/data?_zdata="+objeto+"%2C1&_dc=1424543998227&page=1&start=0&limit=25&sort=%5B%7B%22property%22%3A%22adusrnomb%22%2C%22direction%22%3A%22ASC%22%7D%5D";
      $.ajax({
        url:url,
        dataType:"json",
        success:function(res){
          callback(res);
        },
        error:function(res){
          console.log(res);
        }
      });
    });
  },
  grafico:function(objeto,fieldX,fieldY,callback){
      var url="http://172.16.5.195/openbank1/public/index.php/grilla/data?_zdata="+objeto+"%2C1&_dc=1424543998227&page=1&start=0&limit=25&sort=%5B%7B%22property%22%3A%22adusrnomb%22%2C%22direction%22%3A%22ASC%22%7D%5D";
      $.ajax({
        url:url,
        dataType:"json",
        success:function(res){
          var d=[];
          for(var i=0,l=res.data.length;i<l;i++){
            d.push({x:res.data[i][fieldX],y:parseFloat(res.data[i][fieldY])});
          }
          var data = {
            "xScale": "ordinal",
            "yScale": "linear",
//            "type": "bar",
            "main": [
              {
                "className": ".pizza",
                "data": d,
              }
            ]
          };

	var opts = {
		paddingLeft : 50,
		paddingTop : 20,
		paddingRight : 10,
		axisPaddingLeft : 25,
		tickHintX: 9, // How many ticks to show horizontally
	};

          var chart = new xChart('bar', data, '#chart');
        },
        error:function(res){
          console.log(res);
        }
      });
  }
}
$(function(){
  var cacheObjetos={};
  $("[ob-objeto]").each(function(){
    var me=this;
      OB.grilla(this.getAttribute("ob-objeto"), function(res){
        me.innerHTML=res.data[0][me.getAttribute("ob-dato")];
      });
  })
  $("[ob-grafico]").each(function(){
    var me=this;
      OB.grafico(this.getAttribute("ob-grafico"),this.getAttribute("ob-grafico-x"),this.getAttribute("ob-grafico-y"), function(res){
      });
  })
});
