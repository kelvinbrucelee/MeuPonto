<?php

//Kelvin Brucelee

class ConsultaPontoAdminList extends TStandardList
{

    protected $form;
    protected $datagrid;
    protected $pageNavigation;

    public function __construct()
    {

        parent::__construct();

        $this->form = new BootstrapFormBuilder('list_consultapontoadminlist');
        $this->form->setFormTitle('<font color=blue><b>Consulta de Ponto</b></font>');

        parent::setDatabase('database');            // defines the database
        parent::setActiveRecord('PontoRecord');   // defines the active record
        parent::setDefaultOrder('dataponto', 'desc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('dataponto', '=', 'dataponto'); // filterField, operator, formField
        parent::addFilterField('username', 'like', 'username'); // filterField, operator, formField
        $criteria = new TCriteria();
        $criteria->add(new TFilter('system_user_unit_id','=',TSession::getValue('userunitid')));
        parent::setCriteria($criteria);

        $dataponto = new TDate('dataponto');
        $username = new TEntry('username');
        
        $this->form->addFields( [new TLabel('Nome:')], [$username] );
        $this->form->addFields( [new TLabel('Data Ponto:')], [$dataponto] );

        $dataponto->setSize('50%');
        $username->setSize('70%');
        
        $this->form->setData( TSession::getValue('list_consultapontoadminlist') );

        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('Clear'), new TAction(array($this, 'onClear')), 'fa:eraser red');

        //DATAGRID ------------------------------------------------------------------------------------------
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $dgusername = new TDataGridColumn('username', 'Nome', 'left');
        $dglogin = new TDataGridColumn('login', 'Matrícula', 'left');
        $dgcurso = new TDataGridColumn('curso', 'Curso', 'left');
        $dgdataponto = new TDataGridColumn('dataponto', 'Data Ponto',  'left');
        $dgdataponto->setTransformer(array($this, 'formatDate'));
        $dgsemana = new TDataGridColumn('semana', 'Semana', 'left');
        $dghoraponto = new TDataGridColumn('horaponto', 'Horario', 'left');
        $dgtipo = new TDataGridColumn('justificado', 'Tipo', 'left');
        $dgsituacao = new TDataGridColumn('situacao', 'Situação', 'left');
           
        $this->datagrid->addColumn($dgusername);
        $this->datagrid->addColumn($dglogin);
        $this->datagrid->addColumn($dgcurso);
        $this->datagrid->addColumn($dgdataponto);
        $this->datagrid->addColumn($dgsemana);
        $this->datagrid->addColumn($dghoraponto);
        $this->datagrid->addColumn($dgtipo);
        $this->datagrid->addColumn($dgsituacao);
       
        $dgsituacao->setTransformer( function($value, $object, $row) {
            $class = ($value== 1) ? 'danger' : 'primary';
            $label = ($value== 1) ? ('Saida') : ('Entrada');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });

        
        $action_consultar = new TDataGridAction(array($this, 'onView'));
        $action_consultar->setButtonClass('btn btn-default');
        $action_consultar->setLabel('Verificar IP');
        $action_consultar->setImage('fa:eye fa-lg');
        $action_consultar->setFields(['computador_ip', 'ponto_ip']);

        $this->datagrid->addAction($action_consultar);
          
        $this->datagrid->createModel();
        //FIM DATAGRID -----------------------------------------------------------------------------------------

        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // creates the page structure using a table
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
        $ponto_ip = $param['ponto_ip'];
        new TMessage('info', " Ponto IP : $ponto_ip");
    }
    
    public function formatDate($date, $object)
    {
        $dt = new DateTime($date);
        return $dt->format('d/m/Y');
    }
}

?>