<?php

//Kelvin Brucelee

class ConsultaPresencaEspecialList extends TStandardList
{

    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;

    public function __construct()
    {

        parent::__construct();

        $this->form = new BootstrapFormBuilder('list_consultapresencaespecial');
        $this->form->setFormTitle('<font color=blue><b>Consulta Presença</b></font>');

        parent::setDatabase('database');            // defines the database
        parent::setActiveRecord('EspecialRecord');   // defines the active record
        parent::setDefaultOrder('id', 'desc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('datapresenca', '=', 'datapresenca'); // filterField, operator, formField
        $criteria = new TCriteria();
        $criteria->add(new TFilter('system_unit_id', '=', TSession::getValue('userunitid'))); 
        $criteria->add(new TFilter('matricula','=',TSession::getValue('login')));
        parent::setCriteria($criteria);

        $datapresenca = new TDate('datapresenca');
        
        $this->form->addFields( [new TLabel('Data:')], [$datapresenca] );

        $datapresenca->setSize('50%');
        
        $this->form->setData( TSession::getValue('list_consultapontoadminlist') );

        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('Clear'), new TAction(array($this, 'onClear')), 'fa:eraser red');

        //DATAGRID ------------------------------------------------------------------------------------------
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        
        $dgdatapresenca = new TDataGridColumn('datapresenca', 'Data',  'left');
        $dgdatapresenca->setTransformer(array($this, 'formatDate'));
        $dguser = new TDataGridColumn('user', 'Nome', 'left');
        $dgmatricula = new TDataGridColumn('matricula', 'Matricula', 'left');
        $dghora = new TDataGridColumn('hora', 'Hora', 'left');
          
        $this->datagrid->addColumn($dgdatapresenca);
        $this->datagrid->addColumn($dguser);
        $this->datagrid->addColumn($dgmatricula);
        $this->datagrid->addColumn($dghora);
  
        $this->datagrid->createModel();
        //FIM DATAGRID -----------------------------------------------------------------------------------------

        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));

        parent::add( $container );

    }
    public function onClear($param)
    {
        $fields = $this->form->getFields();
        foreach($fields as $field) {
            TSession::setValue($this->activeRecord.'_filter_'.$field->getName(), NULL);
            TSession::setValue($this->activeRecord.'_filter_data', NULL);
        }
        $this->form->clear();
        $this->onReload();
    }

    public function formatDate($date, $object)
    {
        $dt = new DateTime($date);
        return $dt->format('d/m/Y');
    }
}

?>