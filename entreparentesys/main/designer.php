<?php
return array(
  'stylesheets'=>[
    ('/lib/dvEditor.css'),
    ('/lib/codemirror/lib/codemirror.css'),
  ],
  'scripts'=>[
    ('/lib/codemirror/lib/codemirror.js'),
    ('/lib/codemirror/addon/edit/matchbrackets.js'),
    ('/lib/codemirror/mode/htmlmixed/htmlmixed.js'),
    ('/lib/codemirror/mode/xml/xml.js'),
    ('/lib/codemirror/mode/javascript/javascript.js'),
    ('/lib/codemirror/mode/css/css.js'),
    ('/lib/codemirror/mode/clike/clike.js'),
    ('/lib/codemirror/mode/php/php.js'),
    ('/lib/LoadNode.js'),
    ('/lib/DvDiagram.js'),
    ('/lib/dvEditor.js'),
    ('/lib/draw3components.js'),
  ],
  [
    'class'=>'panel',
    'title'=>'Designer',
    'cssClass'=>'dg-designer',
    'tbar'=>[
      ['class'=>'button','text'=>'Load','handler'=>"Designer.load('source/test.xml')"],
      ['class'=>'button','text'=>'Save','handler'=>'Designer.save()'],
      ['class'=>'button','text'=>'Add','handler'=>"Designer.addNode()"],
    ],
    'items'=>array(
      [
        'class'=>'panel',
        'cssClass'=>'dg-sheet',
      ],
      [
        'class'=>'panel',
        'cssClass'=>'dg-console',
        'html'=>'<div id="log_output"></div><textarea></textarea>',
      ]
    )
  ],
  [
    'class'=>'window',
    'id'=>'codewindow',
    'title'=>'Code',
    'html'=>'<div id="codediv"></div>
      <script>(function(){
        window.myCodeMirror = CodeMirror(document.getElementById("codediv"),{
          lineNumbers: true,
          matchBrackets: true,
          mode: "application/x-httpd-php",
          indentUnit: 2,
          indentWithTabs: false
        });
      })()</script>'
  ]
);