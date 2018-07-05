<!DOCTYPE html>
<html>
<head>
  <title>Website Template</title>
  <meta charset="utf-8">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script>if(typeof $=="undefined") document.writeln("<"+"script src='jquery-1.9.1.js'"+">"+"<"+"/script>");</script>
  <link href="css/my.css" rel="stylesheet" type="text/css">
  <script>
  $(function(){
    $("#content").on("scroll", function(){
      var maxS=this.scrollHeight - $(this).innerHeight();
      var s=Math.floor(this.scrollTop/(maxS+1)*7);
      if(s>6)s=6;
      $("body").attr("class", "page state_"+s);
    });
  });
  </script>
</head>
<body class="page">
<div id="content" style="text-align:center">
  <img src="images/seedling.png">
  <div class="row"><div class="block"><h1>Soy Cristiano<div class="box"></div></h1></div></div>

  <div class="row"><div class="block-left"><h2>Lo que nos gusta hacer</h2><pre class="box bgcloud">Estudiar, compartir y vivir la 
palabra del Señor</pre></div></div>

  <div class="row"><div class="block-right"><h2>Contactanos</h2><pre class="box bgcloud">Somos la célula 1.5.3.
Escribenos a soycristiano@gmail.com
Nuestro <a href="https://www.facebook.com/groups/348273825237921/?fref=ts">Facebook</a></pre></div></div>

  <div class="row"><div class="block-left"><h2>La palabra de la semana</h2><pre class="box bgcloud">El Señor es mi pastor nada me faltará.
Nuestro <a href="#">Twitter</a></pre></div></div>

  <div class="row"><div class="block-right"><h2>Soy Cristiano el Juego</h2><pre class="box bgcloud">Pon a prueba tu conocimiento,
responde a preguntas de la Biblia,
hechos historicos, personajes biblicos,
por niveles y temáticas variadas.</pre></div></div>

  <div class="row"><div class="block-left"><h2>Descargala</h2><pre class="box bgcloud">Para dispositivos móviles.</pre></div></div>

  <div class="row"><div class="block-right"><h2>Colaboranos</h2><pre class="box bgcloud">Compartenos tus preguntas para el juego.
Compartenos tus ideas
Comparte tus experiencias de
  vida cristiana.</pre></div></div>

</div>
</div>
<div id="plant" class="state_0" state="0"></div>
<style>
.green-link {
    background-color: rgba(0, 200, 0, 0.4);
    border-radius: 8px;
    padding: 2px 4px;
}
.state_3 .about{
  position:absolute;
  bottom:106px;
  left:15%;
}
.state_4 .about{
  position:absolute;
  bottom:144px;
  left:15%;
}
.state_5 .about{
  position:absolute;
  bottom:138px;
  left:15%;
}
.state_6 .about{
  position:absolute;
  bottom:155px;
  left:15%;
  margin-left:-40px;
}
</style>
<div class="about green-link leaf_3 leaf_4 leaf_5 leaf_6"><a href="about.php">Acerca de</a></div>
<div id="footer"></div>
</body>
</html>