<script>
var text = <?php

$text = 'En este punto estamos a mitad de camino a través de la discusión de los principios SOLID.
 Ahora, como he señalado en los videos anteriores.
 Usted encontrará a medida que aprende de estas cosas es que todas estas diversas técnicas y principios, están todos entrelazados.
 Así que usted escuchará las mismas frases que aparezcan una y otra vez.
 Y una de esas frases es codificar por una interfaz.
 Esperemos que ahora que hemos establecido que se trata de una buena práctica y nos permite garantizar las entradas.
 Así, por ejemplo cuando se tiene una función que acepta una interfaz.
 Alguna interfaz.
 Podemos estar seguros de que cualquier aplicación que se pasa a esta función se sumará aquí, con este contrato.
 Pero ahora llegamos a la L de SOLID.
 Y la postura para el principio de sustitución Liskov.
 Liskov ser el nombre de la creadora Barbara, Liskov.
 Ahora bien, si usted fuera a echar un vistazo a la definición matemática de este principio.
 Sería muy confuso y probablemente sólo le haria volar sobre su cabeza.
 Así como lo hizo con la mía.
 Vamos a reducir todo eso a algo mucho más consumible.
 Piense en ello como esto: cada vez que prepare una subclase esa subclase debe ser sustituible, en todos los lugares donde se aceptó la clase original.
 O para retocar esa definición sólo un poco.
 Cualquier implementación de una abstracción o una interfaz debe ser sustituible en cualquier lugar.
 donde se acepta la abstracción.
 Asi que.
 Para ilustrar déjame mostrarte tres ejemplos diferentes.
 En primer lugar vamos a empezar con lo básico.
 Así que este principio establece que si tengo una clase A.
 Y vamos a imaginar que tiene una función tal vez algo como "fire".
 Entonces, si tuviéramos que crear una subclase B que extiende A.
 De acuerdo con este principio.
 Debemos ser capaces de utilizar B en cualquier lugar en que A es aceptada.
 Así que si yo fuera a copiar esto aquí.
 Imagine que tiene alguna función.
 Hacer algo.
 Y eso debería aceptar una instancia de A.
 Y luego vamos a hacer algo con eso, los detalles no importan realmente.
Pero los principios establecen que si esta espera A.
Luego también debe ser capaz de cambiar en o sustituido por B.
 y todo debe seguir trabajando perfectamente.
 Ahora se puede ver esto y pensar espera un minuto esto es de sentido común cierto.
 Bueno, vamos a profundizar un poco más profundo.
 He aquí otro ejemplo.
 Un magine que tiene algún tipo de reproductor de vídeo.
 Tal vez esta clase debe tener un método de reproducción.
 En que debe hacer todo lo necesario para reproducir el vídeo.
 Pero ahora.
 Imaginemos que si estamos reproduciendo por ejemplo.
 Vídeos AVI.
 Eso es un poco diferente.
 Así que tal vez haces algo como esto, reproductor de vídeo avi extiende reproductor de vídeo.
 Ahora.
 Sobreescribimos el método de reproducción.
 Sin embargo esta vez hacemos algo un poco diferente.
 Tal vez, comprobamos la extensión del archivo.
 Así por ejemplo, cuando se llama al método play.
 Usted proporciona el nombre del archivo que desea reproducir.
 Así que tal vez cuando sobreescribamos este.
 Decimos algo como esto.
 Si, y vamos a usar pathinfo para agarrar la extensión.
 Así que digamos ruta y perfil.
 Y queremos agarrar la extensi´on de pathinfo.
 Así que decir si la extensión no es un avi.
 Luego, en ese caso, tal vez vamos a lanzar algún tipo de excepción.
 Ahora, a primera vista se podría pensar.
 Muy bien ¿Este es probablemente aceptable.
 Nos subclase ella.
 Nos reemplazar el método de juego sólo para hacer una comprobación rápida para ver si se trata de un AVI presentar y.
 Si no es en este caso vamos a lanzar una excepción.
 Sin embargo el código aquí.
 Viola el principio de sustitución Liskov.
 Ahora ¿por qué?.
 ¿Dónde está la violación aquí?
 Bien.
 Una de las reglas es que las condiciones previas para la subclase no puede ser mayor.
 Así que en este caso ya no.
 ¿Puede sustituimos en otro sitio porque la producción podría potencialmente ser diferente si utilizamos esta clase y que llamamos juego.
 Luego habrá que esperar algún tipo de respuesta.
 Sin embargo, el L.S.P. establece que debemos ser capaces de sustituir esto.
 En cualquier lugar que.
 Esto se acepta.
 Por desgracia, eso no es el caso.
 Porque si tuviéramos que usar esto en la extensión no coincide.
 Entonces lanzamos una excepción.
 Y eso es lo que crea la violación.
 Así que como es de esperar puede ver este principio nos ayuda a proteger contra las situaciones en que una especie de descendiente expone un comportamiento.
 Eso es muy diferente de la clase padre original o la abstracción inicial.
 O interfaz.
 Ahora bien, si no te importa.
 Vamos a seguir con esta idea un poco más.
 Lo que demostrada aquí un ejemplo de las condiciones previas que son demasiado grandes.
 Sin embargo eso no es la única cosa que usted necesita preocuparse.
 Hasta aquí.
 Si has visto las dos lecciones anteriores sobre el Principio de Responsabilidad Individual.
 Y el Abierto de Principio cerrado.
 Usted debe ser bastante cómodo con la idea de la codificación.
 De acuerdo con un contrato.
 Sin embargo, no es sólo un problema de ese contrato valida la entrada.
 Pero no dice nada sobre la salida, te voy a dar una capa de un ejemplo de esta.
 Si usted mira la lección que nos echamos en repositorios.
 Entonces sabes que pueden proporcionar beneficios significativos.
 Imaginemos que hemos creado una interfaz aquí.
 Tal vez algo en la --- como el Lección Repositorio Interfaz.
 Este es el contrato.
 Es un contrato vinculante.
 Así que tal vez necesitamos proporcionar alguna forma de obtener todos los registros.
 Así que se podría decir Obtén todos.
 Al igual que lo que ahora tenemos nuestro contrato establecido.
 Así que tenemos nuestra primera implementacion.
 Tal vez de un principio.
 Sólo almacenamos todo dentro de un archivo.
 Y estamos ir.
 En tienen que poner en práctica.
 Lección Interfaz de repositorio.
 Siguiente como he señalado muchas veces.
 Debido a que implementar la interfaz.
 Tenemos que agregar aquí para el contrato.
 Y ahora.
 Dentro aquí.
 Hacemos lo que sea necesario para consultar el sistema de archivos y devolver los resultados.
 Por ahora vamos a representar a esa como una matriz.
 Así que en este punto.
 Todo está muy bien, pero ahora.
 Vamos a hacer otro y este será nuestro repositorio de base de datos específica.
 Bien ahora.
 Tal vez usted tiene un modelo "elocuente".
 Es probable que hagas, algo así que acaba de regresar algo como obtener Todas.
 Ahora.
 Aunque esto se ve bien.
 Estamos reduciendo a una interfaz.
 El problema es que los valores de retorno son diferentes en que se rompe el principio de sustitución Liskov en este caso tenemos una matriz.
 Y en este caso.
 Tenemos una colección.
 Eso significa que el consumidor de cualquiera de estas implementaciones.
 No funciona de forma idéntica.
 En un caso tenemos un objeto que tiene una serie de métodos.
 Y en otro caso, simplemente obtenemos una matriz.
 Esta verdad.
 Rompe el LSP.
 Ahora voy a ser el primero en decir que específicamente para este ejemplo en el que estamos usando repositorios.
 A veces me rompo esta regla.
 Pero, no obstante, que es muy importante que usted entienda que realmente estamos rompiendo una regla.
 Y podría haber ramificaciones para eso.
 Ahora realmente si queremos añadir aquí para que lo que se podría usar algo como bloques de documentacion.
 Así que se podría decir.
 obtener todos los registros.
 Y entonces podemos comentar que se debe devolver una matriz.
 Sólo recuerde que PHP.
 realmente no se va a hacer nada con esto.
 Es simplemente unos CONSEJOS.
 Sin embargo a veces esto puede ser suficiente.
 Si usted tiene cuidado.
 Desafortunadamente no tenemos muchos opciones en PHP.
 Sólo recuerda sin embargo.
 Si usted ve una interfaz que dice devolver una matriz.
 Asegúrese de que todas sus implementaciones.
 En aquí para ese contrato.
 Incluso si el idioma no te obliga.
 Así que en nuestro caso lo que se quiere hacer algo como Lección::todo.
 toArray.
 De esa manera.
 No importa cuál sea la implementación que utilizamos ambos ofrecen un método de obtener todos.
 Y ambos devolver una matriz de resultados.
 Ahora, aunque imaginamos que no hicimos aquello, en los que es el escollo aquí.
 Bien.
 Si tuviéramos algún otro código que consuma este repositorio.
 Usaremos pseudocódigo aquí.
 Dirá que tenemos algún tipo de función foo.
 Esa excepción alguna implementación de la interfaz de repositorio lección.
 Debido a que tipo entregamos una interfaz en lugar de una clase concreta.
 Nos liberamos a nosotros mismos de muchas maneras.
 Ahora.
 Siempre y cuando lo pasamos a esta función.
 Conforme a ese contrato o se comporta.
 De acuerdo con los términos de dicho contrato.
 Podemos usarlo o como usted pueden haber oído alrededor de la web.
 "Entonces, debe ser un pato".
 Ahora sin embargo para continuar imagine que tenemos nuestras lecciones y llamamos.
 Lección Recibe todo, al que tenemos acceso a ese método.
 Ahora.
 Si los tipos devueltos son diferentes, que si son diferentes en este caso.
 Usted puede encontrar situaciones en las que tienes que hacer algo así.
 Si "es un".
 Y luego comparar el valor contra el tipo.
 Y al igual que aprendimos con el Principio Abierto Cerrado.
 Cada vez que usted se encuentra haciendo una comprobación apretada.
 Esa es la alerta.
 Que usted está rompiendo uno de estos principios o de hecho.
 Como señalé.
 Debido a que las enseñanzas de estos principios están vinculados de muchas maneras.
 Si usted está violando un principio.
 Entonces es probable que, en efecto, también se está rompiendo el otro principio.
 Así como resultado.
 Si alguna vez te encuentras haciendo chequeos de instancia, lo más probable es que no solo este rompiendo el Principio Abierto Cerrado.
 Pero también se está violando el principio Liskov Sustitución.
 Ahora, piensa, antes de aprender acerca de este principio, podría haber hecho algo muy parecido a esto.
 Bueno no sabemos exactamente lo que tenemos entonces vamos a hacer un rápido.
 "Si esto".
 Y si tenemos una colección, entonces queremos responder de esta manera.
 Pero si en vez obtenemos una matriz.
 Entonces debemos responder de ese modo.
 Lo que rompe este principio.
 En su lugar, asegúrese siempre de que la salida de sus implementaciones coincida con lo que se especifica en el contrato.
 Así que para terminar voy a dejar para ustedes una lista rápida de maneras de adherirse al principio Sustitución Liskov.
1 La firma debe coincidir.
2 Condiciones previas no pueden ser mayores.
3 Condiciones posteriores al menos iguales.
4 Los tipos de excepción deben coincidir.';
echo json_encode(explode("\n", $text));
?>;

var result=[];
var t0=0,t=0;
var index=0;
function addsubtitle(){
  if(index<text.length){
    console.log("> "+text[index+1]);
  }
  t=new Date().getTime();
  if(t0==0){
    t0=t;
  }
  console.log(t, t-t0);
  if(index>0){
    result[result.length-1].t2=t-t0;
  }
  index++;
  result.push({
    index:index,
    t1:t-t0,
    text: text[index-1]
  });
}
function printFormated(){
  var res=[];
  for(var i=0,l=result.length;i<l;i++) if(typeof result[i].t2!="undefined"){
    res.push(result[i].index);
    res.push(secondsToString(result[i].t1) + ' --> '+secondsToString(result[i].t2));
    res.push(result[i].text);
    res.push("");
  }
document.getElementById('resultado').value=res.join("\n");
}

function secondsToString(seconds)
{

var numdays = Math.floor(seconds / 86400);

var numhours = Math.floor((seconds % 86400) / 3600);

var numminutes = Math.floor(((seconds % 86400) % 3600) / 60);

var numseconds = ((seconds % 86400) % 3600) % 60;

return "%02d:%02d:%05.2f".$(numhours, numminutes, numseconds);

}

String.form = function(str, arr) {
    var i = -1;
    function callback(exp, p0, p1, p2, p3, p4) {
        if (exp=='%%') return '%';
        if (arr[++i]===undefined) return undefined;
        var exp  = p2 ? parseInt(p2.substr(1)) : undefined;
        var base = p3 ? parseInt(p3.substr(1)) : undefined;
        var val;
        switch (p4) {
            case 's': val = arr[i]; break;
            case 'c': val = arr[i][0]; break;
            case 'f': val = parseFloat(arr[i]).toFixed(exp); break;
            case 'p': val = parseFloat(arr[i]).toPrecision(exp); break;
            case 'e': val = parseFloat(arr[i]).toExponential(exp); break;
            case 'x': val = parseInt(arr[i]).toString(base?base:16); break;
            case 'd': val = parseFloat(parseInt(arr[i], base?base:10).toPrecision(exp)).toFixed(0); break;
        }
        val = typeof(val)=='object' ? JSON.stringify(val) : val.toString(base);
        var sz = parseInt(p1); /* padding size */
        var ch = p1 && p1[0]=='0' ? '0' : ' '; /* isnull? */
        while (val.length<sz) val = p0 !== undefined ? val+ch : ch+val; /* isminus? */
       return val;
    }
    var regex = /%(-)?(0?[0-9]+)?([.][0-9]+)?([#][0-9]+)?([scfpexd])/g;
    return str.replace(regex, callback);
}

String.prototype.$ = function() {
    return String.form(this, Array.prototype.slice.call(arguments));
}

</script>

<button type="button" onclick="addsubtitle()">INSERT<button>
<button type="button" onclick="printFormated()">FORMAT<button>
<textarea id="resultado"></textarea>
