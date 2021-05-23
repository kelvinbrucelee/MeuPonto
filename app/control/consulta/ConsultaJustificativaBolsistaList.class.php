<?php

//Kelvin Brucelee

class ConsultaJustificativaBolsistaList extends TStandardList
{

    protected $form;    
    protected $datagrid;
    protected $pageNavigation;

    public function __construct()
    {

        parent::__construct();

        $this->form = new BootstrapFormBuilder('list_consultapresencaespecial');
        $this->form->setFormTitle('<font color=blue><b>Consulta Justificativa</b></font>');

        parent::setDatabase('database');       
        parent::setActiveRecord('JustificativaRecord'); 
        parent::setDefaultOrder('id', 'desc');  
        parent::addFilterField('id', '=', 'id');
        parent::addFilterField('dataponto', '=', 'dataponto');
        $criteria = new TCriteria();
        $criteria->add(new TFilter('system_user_unit_id', '=', TSession::getValue('userunitid')));
        $criteria->add(new TFilter('username','=',TSession::getValue('username')));
        parent::setCriteria($criteria);

        $dataponto = new TDate('dataponto');
        
        $this->form->addFields( [new TLabel('Data Ponto:')], [$dataponto] );

        $dataponto->setSize('50%');
        
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
        $dgdataponto = new TDataGridColumn('dataponto', 'Data Ponto',  'left');
        $dgdataponto->setTransformer(array($this, 'formatDate'));
        $dgsemana = new TDataGridColumn('semana', 'Semana', 'left');
        $dghoraponto = new TDataGridColumn('horaponto', 'Horario', 'left');
        $dgjustificativa = new TDataGridColumn('datajustificativa', 'Data Justificativa', 'left');
        $dgjustificativa->setTransformer(array($this, 'formatDate'));
        $dgsituacao = new TDataGridColumn('situacao', 'Situação', 'left');
        $dgobservacao = new TDataGridColumn('observacao', 'Observação', 'left');
           
        $this->datagrid->addColumn($dgusername);
        $this->datagrid->addColumn($dgdataponto);
        $this->datagrid->addColumn($dgsemana);
        $this->datagrid->addColumn($dghoraponto);
        $this->datagrid->addColumn($dgjustificativa);
        $this->datagrid->addColumn($dgsituacao);
        $this->datagrid->addColumn($dgobservacao);

        $dgsituacao->setTransformer( function($value, $object, $row) {
            $class = ($value== 1) ? 'danger' : 'primary';
            $label = ($value== 1) ? ('Saida') : ('Entrada');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });
  
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