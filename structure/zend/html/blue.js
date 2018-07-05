
/**
 *
 *  Achachi XML Builder - Framework and applications builder tool
 *  Copyright (C) 2010-2011, Llankay Achachi
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
var blue={
  "afterAction":function(a,b){
    if(b.result.afterAction) b.result.afterAction();
  },
  "afterSubmit":function(base){
    var s=base.success;
    if(!base.params)base.params={};
    base.params.__ajax=true;
    base.success=function(a,b){if(s)s(a,b);blue.afterAction(a,b);};
    return base;
  },
  "submit":function(form,base){
    if(typeof(form)=="string") form = Ext.getCmp(form).getForm();
    form.submit(blue.afterSubmit(base));
  },
  "request":function(base){
    base.success=function(a,b){
      try {
        eval("a.responseJSON="+a.responseText);
      } catch(e) {
      }
      if(a.responseJSON && a.responseJSON.afterAction) 
        a.responseJSON.afterAction();
    }
    Ext.Ajax.request(base);
  },
  "redirect":function(base){
    window.location.href=base.url;
  }
}
