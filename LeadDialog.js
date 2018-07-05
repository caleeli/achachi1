GO.leads.LeadDialog = function(config){

  if(!config)
  {
    config = {};
  }

  this.buildForm();

  config.layout='fit';
  config.title=GO.leads.lang.lead;
  config.modal=false;
  config.border=false;
  config.width=800;
  config.autoHeight=true;
  config.resizable=false;
  config.plain=true;
  config.shadow=false,
  config.closeAction='hide';
  config.items=this.formPanel;

  config.buttons=[
    {
      text:GO.lang['cmdSave'],
      handler: function()
      {
        this.submitForm(true)
      },
      scope: this
    },
    {
      text:GO.lang['cmdCancel'],
      handler: function()
      {
        this.hide()
      },
      scope: this
    }
  ];

    
  GO.leads.LeadDialog.superclass.constructor.call(this,config);
  
  this.addEvents({'save' : true});
}

Ext.extend(GO.leads.LeadDialog, Ext.Window, {
  show : function(config) {console.log(config);
    if (config.link_config) {
      this.link_config = config.link_config;
      if (config.link_config.modelNameAndId) {
        this.selectLinkField.setValue(config.link_config.modelNameAndId);
        this.selectLinkField.setRemoteText(config.link_config.text);
        this.phone_store.load({params:{link:config.link_config.modelNameAndId}});
      }
    }
    GO.calllog.CallDialog.superclass.show.call(this);
  },
  submitForm : function(hide)
  {
    this.formPanel.form.submit(
    {    
      url:GO.settings.modules.calllog.url+'json.php',
      waitMsg:GO.lang['waitMsgSave'],
      scope:this,
      params: {
        task:'save_call',
        id:this.call_id
      },
      waitMsg:GO.lang['waitMsgSave'],
      success:function(form, action)
      {
        if(action.result.id)
        {
          this.call_id=action.result.id;
        }
      
        this.fireEvent('save');
        
        if(hide)
        {
          this.hide();
        }
      },
      failure: function(form, action) 
      {
        var error = '';
        if(action.failureType=='client')
        {
          error = GO.lang['strErrorsInForm'];
        }else
        {
          error = action.result.feedback;
        }
        Ext.MessageBox.alert(GO.lang['strError'], error);
      },

    });    
  },

  buildForm : function () 
  {
    this.selectLinkField = new GO.form.SelectLink({});
    this.date = new Ext.form.DateField({
      name : 'date',
      format : GO.settings['date_format'],
      fieldLabel: GO.lang.strDate,
      allowBlank : false
    });
    this.time = new Ext.form.TimeField({
      name : 'time',
      format : GO.settings['time_format'],
      fieldLabel: GO.lang.strTime,
      allowBlank : false
    });
    this.phone_store = new GO.data.JsonStore({
//        url: GO.url('calllog/tasklist/store'),
//        baseParams:{permissionLevel: GO.permissionLevels.create},
        url: GO.settings.modules.calllog.url+'json.php',
        baseParams: {'task': 'phone', 'auth_type':'write'},
        root: 'results',
        totalProperty: 'total',
        id: 'phone',
        fields:['value'],
        remoteSort: true,
//        autoLoad: true,
    });
    this.phone = new Ext.form.ComboBox({
      name : 'phone',
      store : this.phone_store,
      fieldLabel: GO.lang.strPhone,
      allowBlank : false
    });
    this.TypeCallCombo = new GO.form.ComboBox({
      fieldLabel: GO.calllog.lang.callType,
      hiddenName:'callType',
      store: new Ext.data.SimpleStore({
        fields: ['value', 'text'],
        data : [
        ['I', GO.calllog.lang.inbound],
        ['O', GO.calllog.lang.outbound]
        ]
        
      }),
      value:'I',
      valueField:'value',
      displayField:'text',
      mode: 'local',
      triggerAction: 'all',
      editable: false,
      selectOnFocus:true,
      forceSelection: true
    });
    
    var fieldsetIO = new Ext.form.FieldSet({
      cls:'go-form-panel',
      collapsed:false,
      items:this.TypeCallCombo,  
    });
    var fieldset = new Ext.form.FieldSet({
      //title:GO.lang.strProperties,
      cls:'go-form-panel',
      anchor:'100% 100%',      
      defaults:{
        anchor:'-20',
        labelWidth:140
      },
      defaultType:'textfield',
      collapsed:false,
      items:[this.selectLinkField,this.date,this.time,this.phone,
      {
        //fieldLabel: 'Subject',
        //name: 'subject'
        xtype : 'compositefield',
                        anchor: '-20',
                        msgTarget: 'side',
                        fieldLabel: GO.calllog.lang.subject,
                        items : [
                            {
                                /* cargar datos de la base */
                                width:          330,
                                xtype:          'combo',
                                mode:           'local',
                                value:          '',
                                triggerAction:  'all',
                                forceSelection: true,
                                editable:       false,
                                fieldLabel:     'Subject',
                                name:           GO.calllog.lang.subject,
                                hiddenName:     'subject',
                                displayField:   'value',
                                valueField:     'value',
                                store:          new Ext.data.JsonStore({
                                    fields : ['name', 'value'],
                                    data   : [
                                        {name : '0',   value: ''},
                                        {name : '1',   value: 'Greetings'},
                                        {name : '2',   value: 'Important'},
                    {name : '3',   value: 'Sending Notification'}
                                    ]
                                })
                            },
                            {
                                xtype: 'button',
                                name : 'addSubject',
                                text : GO.calllog.lang.addSubject
                            }
                        ]
        
        
      },{
        fieldLabel: GO.lang.strDescription,
        name: 'description',
        xtype:'textarea'
      },]
    });

    var items_left_col=[];
    var items_right_col=[];
    items_left_col.push(fieldset);
    items_right_col.push(fieldsetIO);

    this.formPanel = new Ext.FormPanel({
      cls:'go-form-panel',
      labelWidth: 75,
      autoHeight:true,
      border:false,
      width: 500,
      items: [
      {
        border:false,
        items:items_left_col
      }]
    });
  }  
});