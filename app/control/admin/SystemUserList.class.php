<?php
/**
 * SystemUserList
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemUserList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('permission');            // defines the database
        parent::setActiveRecord('SystemUser');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('name', 'like', 'name'); // filterField, operator, formField
        parent::addFilterField('email', 'like', 'email'); // filterField, operator, formField
        parent::addFilterField('active', '=', 'active'); // filterField, operator, formField
        parent::addFilterField('autorizacao', '=', 'autorizacao'); // filterField, operator, formField
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_SystemUser');
        $this->form->setFormTitle('<font color=blue><b>Usuários</b></font>');
        

        // create the form fields
        $id = new TEntry('id');
        $name = new TEntry('name');
        $email = new TEntry('email');
        $active = new TCombo('active');
        
        $active->addItems( [ 'Y' => _t('Yes'), 'N' => _t('No') ] );
       
        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel(_t('Name'))], [$name] );
        $this->form->addFields( [new TLabel(_t('Email'))], [$email] );
        $this->form->addFields( [new TLabel(_t('Active'))], [$active] );
       
        $id->setSize('30%');
        $name->setSize('70%');
        $email->setSize('70%');
        $active->setSize('70%');
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('SystemUser_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction(array('SystemUserForm', 'onEdit')), 'bs:plus-sign green');
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        

        $column_id = new TDataGridColumn('id', 'Id', 'center', 50);
        $column_name = new TDataGridColumn('name', _t('Name'), 'left');
        $column_login = new TDataGridColumn('login', _t('Login'), 'left');
        $column_email = new TDataGridColumn('email', _t('Email'), 'left');
        $column_autorizacao = new TDataGridColumn('autorizacao', ('Especial'), 'center');
        $column_active = new TDataGridColumn('active', _t('Active'), 'center');
        
        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_name);
        $this->datagrid->addColumn($column_login);
        $this->datagrid->addColumn($column_email);
        $this->datagrid->addColumn($column_autorizacao);
        $this->datagrid->addColumn($column_active);

        $column_autorizacao->setTransformer( function($value, $object, $row) {
            $class = ($value=='N') ? 'danger' : 'primary';
            $label = ($value=='N') ? ('Não') : ('Sim');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });

        $column_active->setTransformer( function($value, $object, $row) {
            $class = ($value=='N') ? 'danger' : 'success';
            $label = ($value=='N') ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });
        
        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        
        $order_name = new TAction(array($this, 'onReload'));
        $order_name->setParameter('order', 'name');
        $column_name->setAction($order_name);
        
        $order_login = new TAction(array($this, 'onReload'));
        $order_login->setParameter('order', 'login');
        $column_login->setAction($order_login);
        
        $order_email = new TAction(array($this, 'onReload'));
        $order_email->setParameter('order', 'email');
        $column_email->setAction($order_email);
        

        
        // create EDIT action
        $action_edit = new TDataGridAction(array('SystemUserForm', 'onEdit'));
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);

        $action_especial = new TDataGridAction(array($this, 'onEspecial'));
        $action_especial->setButtonClass('btn btn-default');
        $action_especial->setLabel(_t('Activate/Deactivate'));
        $action_especial->setImage('fa:assistive-listening-systems fa-lg blue');
        $action_especial->setField('id');
        $this->datagrid->addAction($action_especial);
        
        // create ONOFF action
        $action_onoff = new TDataGridAction(array($this, 'onTurnOnOff'));
        $action_onoff->setButtonClass('btn btn-default');
        $action_onoff->setLabel(_t('Activate/Deactivate'));
        $action_onoff->setImage('fa:power-off fa-lg orange');
        $action_onoff->setField('id');
        $this->datagrid->addAction($action_onoff);

        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        $panel = new TPanelGroup;
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        //$container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }
    
    /**
     * Turn on/off an user
     */public function onEspecial($param)
    {
        try
        {
            TTransaction::open('database');
            $user = SystemUser::find($param['id']);
            if ($user instanceof SystemUser)
            {
                $user->autorizacao = $user->autorizacao == 'S' ? 'N' : 'S';
                $user->store();
            }
            
            TTransaction::close();
            
            $this->onReload($param);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onDelete( $param = NULL )
    {
        if( isset( $param[ "key" ] ) ) {

            $action_ok = new TAction( [ $this, "Delete" ] );
            $action_cancel = new TAction( [ $this, "onReload" ] );

            $action_ok->setParameter( "key", $param[ "key" ] );

            new TQuestion( "Deseja remover o registro?", $action_ok, $action_cancel,  "Deletar");

        }
    }

    function Delete( $param = NULL )
    {
        try {

            TTransaction::open('database');

            $object = new SystemUser($param['key']);

            $object->delete();

            //$object1 = new SystemUserUnit($param['system_user_id']);

           // $object1->delete();

            TTransaction::close();

            $this->onReload();

            new TMessage( "info", "Registro deletado com sucesso!" );

        } catch ( Exception $ex ) {

            TTransaction::rollback();

            new TMessage( "error",  $ex->getMessage() .'.' );

        }

    }
    public function onTurnOnOff($param)
    {
        try
        {
            TTransaction::open('permission');
            $user = SystemUser::find($param['id']);
            if ($user instanceof SystemUser)
            {
                $user->active = $user->active == 'Y' ? 'N' : 'Y';
                $user->store();
            }
            
            TTransaction::close();
            
            $this->onReload($param);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
