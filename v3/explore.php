<script src="jquery-1.10.2.min.js"></script>
<script src="explore.js"></script>
<link href="explore.css" type="text/css" rel="stylesheet" >
<script src="jsSequence/raphael-min.js"></script>
<script src="jsSequence/underscore-min.js"></script>
<script src="jsSequence/sequence-diagram-min.js"></script>
<script src="jsSequence/jquery-plugin.js"></script>
<div id="explore" class="exTree" ajaxUrl="exploreTree.php" ></div><div id="diagram" class="exDiagram" ajaxUrl="exploreDiagram.php"></div>
<textarea id="diagramCode" rows="25"></textarea><button onclick="run()">run</button>