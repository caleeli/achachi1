GO.leads.MainPanel = function(config)
{
  if(!config)
  {
    config = {};
  }

  config.id='cl_leads_grid';





  GO.leads.MainPanel.superclass.constructor.call(this,config);

  this.on("rowdblclick",this.rowDoubleClick, this);
  this.on('rowcontextmenu', this.onContextClick, this);
  
};

Ext.extend(GO.leads.MainPanel, GO.grid.GridPanel,{

  afterRender : function(){
    GO.leads.MainPanel.superclass.afterRender.call(this);
    
    this.store.load();

    if(!this.LeadDialog)
    {
      this.leadDialog = new GO.leads.LeadDialog();
      this.leadDialog.on('save', function()
      {
        this.store.reload();
      },this)
    }
  },

  rowDoubleClick : function (grid)
  {
    var selectionModel = grid.getSelectionModel();
    var record = selectionModel.getSelected();

    this.leadDialog.show(record.data);
  },

  onContextClick : function(grid, index, e)
  {
    if(!this.menu)
    {
      this.menu = new Ext.menu.Menu({
        id:'cf-calls-grid-ctx',
        items: [
        {
          text:GO.leads.lang.saveAsDialog,
          scope:this,
          handler: function()
          {
            var record = grid.selModel.getSelected();

            GO.leads.showDialogDialog();
            GO.leads.DialogDialog.personalPanel.setValues(record.data);
          }
        }]
      });
    }

    e.stopEvent();

    if(GO.leads && GO.settings.has_admin_permission)
    {
      this.ctxRecord = this.store.getAt(index);
      this.menu.showAt(e.getXY());
    }
  }
  
});

GO.moduleManager.addModule('leads', GO.leads.MainPanel, {
  title : GO.leads.lang.leads,
  iconCls : 'go-tab-icon-leads'
});
