<?php

//Kelvin Brucelee

class PontoList extends TStandardList
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
        parent::setActiveRecord('PontoRecord');
        parent::setDefaultOrder('id', 'asc');     
        parent::addFilterField('id', '=', 'id');
        $criteria = new TCriteria();
            $valor = date('Y-m-d');
            $criteria->add(new TFilter('date(dataponto)','=', $valor));
            $criteria->add(new TFilter('system_user_unit_id', '=', TSession::getValue('userunitid')));
            $criteria->add(new TFilter('system_user_id', '=', TSession::getValue('userid')));
        parent::setCriteria($criteria);

        $this->form = new BootstrapFormBuilder('list_agendamento');
        $this->form->setFormTitle('<font color=blue><b>Meu Ponto</b></font>');

        $hora = date("H:i:s");
        $data = date("d.m.y");

        $this->form->addFields( [new TLabel('<h2>DATA</h2>')], [('<font color="blue"><b><h1>'.$data.'</h1></b></font>')],[('<font color="blue"><b><h1>'.$hora.'</h1></b></font>')] );
        
        $new_button = $this->form->addAction( 'Entrada' , new TAction(array('PontoEntradaForm', 'onEdit')), 'fa:sign-in');
        $new_button->class = 'btn btn-sm btn-primary';

        $this->form->addAction('Saida', new TAction(array('PontoSaidaForm', 'onEdit')), 'fa:power-off red');

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
        $dgsituacao = new TDataGridColumn('situacao', 'Situação', 'left');
       
        $this->datagrid->addColumn($dgusername);
        $this->datagrid->addColumn($dgdataponto);
        $this->datagrid->addColumn($dgsemana);
        $this->datagrid->addColumn($dghoraponto);
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
         
        $this->datagrid->createModel();
        //FIM DATAGRID -----------------------------------------------------------------------------------------

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

    public function formatDate($date, $object)
    {
        $dt = new DateTime($date);
        return $dt->format('d/m/Y');
    }

}

?>