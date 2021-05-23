<?php

//Kelvin Brucelee

class TipoJustificativaList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;

    public function __construct()
    {

        parent::__construct();

        $this->form = new BootstrapFormBuilder('list_tipojustificativa');
        $this->form->setFormTitle('<font color=blue><b>Lista de Justificativas</b></font>');

        parent::setDatabase('database');            // defines the database
        parent::setActiveRecord('TipoJustificativaRecord');   // defines the active record
        parent::setDefaultOrder('nome', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('nome', 'like', 'nome'); // filterField, operator, formField

        $nome  = new TEntry('nome');

        $this->form->addFields( [new TLabel(('Nome:'))], [$nome] );

        $nome->setSize('70%');

        $find_button = $this->form->addAction(('Buscar'), new TAction(array($this, 'onSearch')), 'fa:search');
        $find_button->class = 'btn btn-sm btn-primary';

        $new_button = $this->form->addAction(('Novo') , new TAction(array('TipoJustificativaForm', 'onEdit')), 'bs:plus-sign blue');

        $this->form->addAction( 'Limpar Busca' , new TAction(array($this, 'onClear')), 'fa:eraser red');

        //DATAGRID ------------------------------------------------------------------------------------------
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $dgnome = new TDataGridColumn('nome', 'Nome', 'left');


        $this->datagrid->addColumn($dgnome);

        // creates the datagrid column actions
        $order_nome = new TAction(array($this, 'onReload'));
        $order_nome->setParameter('order', 'nome');
        $dgnome->setAction($order_nome);

        // create DOWNLOAD action
        $actionEdit = new TDataGridAction(array('TipoJustificativaForm', 'onEdit'));
        $actionEdit->setButtonClass('btn btn-default');
        $actionEdit->setLabel('Editar');
        $actionEdit->setImage( "fa:pencil-square-o blue fa-lg" );
        $actionEdit->setField('id');

        $actionDelete = new TDataGridAction(array($this, 'onDelete'));
        $actionDelete->setButtonClass('btn btn-default');
        $actionDelete->setLabel('Deletar');
        $actionDelete->setImage( "fa:trash-o red fa-lg" );
        $actionDelete->setField('id');

        $this->datagrid->addAction($actionEdit);
        $this->datagrid->addAction($actionDelete);

        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup;
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);

        $container = new TVBox();
        $container->style = 'width: 100%';
        $container->add($this->form);
        $container->add($panel);

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

}

?>