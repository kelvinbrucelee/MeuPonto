<?php

//Kelvin Brucelee

class AgendamentoList extends TStandardList
{
    protected $form;
    protected $datagrid; 
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;

    public function __construct()
    {
        parent::__construct();

        parent::setDatabase('database');   
        parent::setActiveRecord('CalendarioEventoRecord');
        parent::setDefaultOrder('id', 'asc');     
        parent::addFilterField('id', '=', 'id');
        parent::addFilterField('start_time', '=', 'start_time');
        parent::addFilterField('title', 'like', 'title');

        $this->form = new BootstrapFormBuilder('list_agendamento');
        $this->form->setFormTitle('<font color=blue><b>Listagem de Agendamentos</b></font>');

        $title = new TEntry('title');
        $start_time = new TDateTime('start_time');

        $this->form->addFields([new TLabel('Titulo:')], [$title]);
        $this->form->addFields([new TLabel('Data Inicio:')], [$start_time]);

        $title->setSize('50%');
        $start_time->setSize('50%');

        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';

        $this->form->addAction(('Agendamento'), new TAction(array('AgendamentoForm', 'onEdit')), 'fa:calendar blue');
        $this->form->addAction(_t('Clear'), new TAction(array($this, 'onClear')), 'fa:eraser red');

        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', 'Id', 'center', 50);
        $date = new TDataGridColumn('start_time', 'Data Inicio',  'left', 100);
        $date->setTransformer(array($this, 'formatDate'));
        $column_end_time = new TDataGridColumn('start_time', 'Data Inicio',  'left', 100);
        $column_end_time->setTransformer(array($this, 'formatDate2'));
        $column_color = new TDataGridColumn('color', 'Cor', 'left');
        $column_title = new TDataGridColumn('title', 'Titulo', 'left');
        $column_description = new TDataGridColumn('description', 'Descrição', 'left');
        $column_situacao = new TDataGridColumn('situacao', 'Situação', 'left');

        $this->datagrid->addColumn($date);
        $this->datagrid->addColumn($column_end_time);
        $this->datagrid->addColumn($column_color);
        $this->datagrid->addColumn($column_title);
        $this->datagrid->addColumn($column_description);
        $this->datagrid->addColumn($column_situacao);

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $action_edit = new TDataGridAction(array('AgendamentoForm', 'onEdit'));
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);

        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);

        $this->datagrid->createModel();

        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup;
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);
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

    public function formatDate2($date, $object)
    {
        $dt = new DateTime($date);
        return $dt->format('d/m/Y h:i:s');
    }
    public function formatDate($date, $object)
    {
        $dt = new DateTime($date);
        return $dt->format('d/m/Y h:i:s');
    }
}

?>