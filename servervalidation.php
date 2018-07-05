<html><head><style>html{
  background-color:SlateGray;
}
body{
  text-align:center;
}
button{
  border: 2px groove menu;
  border-radius:8px;
  -moz-border-radius:8px;
  background-color:LightGrey;
}
.box{
  border:1px inset SlateGray;
  border-radius:8px;
  -moz-border-radius:8px;
  background-color:SlateBlue;
  width:500px;
  display:inline-block;
  margin:8px;
}
.boxTitle{
  border:1px outset SlateGray;
  border-radius:8px 8px 0px 0px;
  -moz-border-radius:8px 8px 0px 0px;
  background-color:SteelBlue;
  color:snow;
  font-weight:bold;
}
.boxContent{
  border:1px outset SlateGray;
  border-radius:0px 0px 8px 8px;
  -moz-border-radius:0px 0px 8px 8px;
  background-color:LightSteelBlue;
  padding-bottom:8px;
}
.listTable{
  empty-cells:show;
}
.listTable th{
  border-bottom:3px double SlateBlue;
  padding-top:8px;
  font-weight:normal;
  text-align:left;
}
.listTable td{
  padding-top:8px;
}
.ok{
  color:green;
  font-weight:bold;
  cursor:help;
}
.nook{
  color:red;
  font-weight:bold;
  cursor:help;
}
.note{
  font-size:small;
  color:DarkRed;
}
</style></head><body>
<div>
<span class="box" style="width:300"><p></p></span><span class="box"><div class="boxTitle">WEB SERVER LOGIN</div><div class="boxContent">
<center><table class="listTable" width="80%" cellpadding="0" cellspacing="0">
  <tr><td>
    <form method="POST">
      Password: <input name="password" type="password"/>
      <button type="submit">Login</button>
    </form>
  </td></tr>
</table></center></div></span>
</div>
<br />

</body></html><?php

