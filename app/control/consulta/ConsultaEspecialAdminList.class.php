<?php

//Kelvin Brucelee

class ConsultaEspecialAdminList extends TStandardList
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
        parent::addFilterField('matricula', '=', 'matricula');
        parent::addFilterField('anocurso', '=', 'anocurso');
        $criteria = new TCriteria; 
        $criteria->add(new TFilter('system_unit_id', '=', TSession::getValue('userunitid'))); 
        parent::setCriteria($criteria);

        $datapresenca = new TDate('datapresenca');
        $matricula = new TEntry('matricula');
        $anocurso = new TEntry('anocurso');
        
        $this->form->addFields( [new TLabel('Matrícula:')], [$matricula] );
        $this->form->addFields( [new TLabel('Ano:')], [$anocurso] );
        $this->form->addFields( [new TLabel('Data:')], [$datapresenca] );
        
        $matricula->setSize('70%');
        $datapresenca->setSize('50%');
        $anocurso->setSize('70%');
        
        $this->form->setData( TSession::getValue('list_consultapontoadminlist') );

        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('Clear'), new TAction(array($this, 'onClear')), 'fa:eraser red');

        //DATAGRID ------------------------------------------------------------------------------------------
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $dgdatapresenca = new TDataGridColumn('datapresenca', 'Data',  'left', 100);
        $dgdatapresenca->setTransformer(array($this, 'formatDate'));
        $dguser = new TDataGridColumn('user', 'Nome', 'left');
        $dgmatricula = new TDataGridColumn('matricula', 'Matrícula', 'left');
        $dgcurso = new TDataGridColumn('curso', 'Curso', 'left');
        $dganocurso = new TDataGridColumn('anocurso', 'Ano Curso', 'left');
        $dghora = new TDataGridColumn('hora', 'Hora', 'left');
           
        $this->datagrid->addColumn($dgdatapresenca);
        $this->datagrid->addColumn($dguser);
        $this->datagrid->addColumn($dgmatricula);
        $this->datagrid->addColumn($dgcurso);
        $this->datagrid->addColumn($dganocurso);
        $this->datagrid->addColumn($dghora);

        $action_consultar = new TDataGridAction(array($this, 'onView'));
        $action_consultar->setButtonClass('btn btn-default');
        $action_consultar->setLabel('Verificar IP');
        $action_consultar->setImage('fa:eye fa-lg');
        $action_consultar->setField('atual_ip');

        $this->datagrid->addAction($action_consultar);
  
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

    function onView($param)
    {
        $atual_ip = $param['atual_ip'];
        new TMessage('info', " Ponto IP : $atual_ip");
    }

    public function formatDate($date, $object)
    {
        $dt = new DateTime($date);
        return $dt->format('d/m/Y');
    }
}

?>