<?php

//Kelvin Brucelee

class AjustarPresencaEspecialAdminForm extends TPage
{

    private $form;

    public function __construct()
    
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder('form_ajustarpresencaespecialadmin');
        $this->form->class = "form_ajustarpresencaespecialadmin";
        $this->form->setFormTitle('<font color=blue><b>Ajustar Presença</b></font>');
        
        $id = new THidden('id');

        $username = new TEntry('user');
        $username->setValue(TSession::getValue('key'));
        $username->setEditable(FALSE);

        $datapresenca = new TDate('datapresenca');

        $hora = new TTime('hora');

        $username->setSize('50%');
        $datapresenca->setSize('50%');
        $hora->setSize('50%');

        $datapresenca->addValidation('Data' , new TRequiredValidator);
        $hora->addValidation('Horário' , new TRequiredValidator);

        $action1 = new TAction(array($this, 'onSave'));
        $action1->setParameter('key', '' . filter_input(INPUT_GET, 'key') . '');

        $this->form->addFields([$id]);
        $this->form->addFields([new TLabel('Nome:<font color=red><b>*</b></font>')], [$username]);
        $this->form->addFields([new TLabel('Data:<font color=red><b>*</b></font>')], [$datapresenca]);
        $this->form->addFields([new TLabel('hora:<font color=red><b>*</b></font>')], [$hora]);
        $this->form->addFields( [new TFormSeparator("(<font color=red><b>*</b></font>)Campos obrigatórios")] );
      
        $this->form->addAction('Ajustar', $action1, 'fa:clock-o')->class = 'btn btn-sm btn-primary';
        $this->form->addAction('Voltar', new TAction(array('AjustarPresencaEspecialAdminList', 'onReload')), 'fa:arrow-circle-o-left blue');

        parent::add($this->form);

    }

    function onSave($param = NULL)
    {

        try {

            TTransaction::open('database');

            $this->form->validate();

            $object = $this->form->getData('EspecialRecord');
            $object->dataalteracao = date("Y-m-d H:i:s");
            $object->usuarioalteracao = TSession::getValue('userid');
           
            $object->store();

            TTransaction::close();

            $action_ok = new TAction( [ 'AjustarPresencaEspecialAdminList', "onReload" ] );
            new TMessage( "info", "Registro salvo com sucesso!", $action_ok );

        } catch (Exception $e) {

            new TMessage('error', $e->getMessage());

            TTransaction::rollback();

        }

    }

    function onEdit($param = NULL)
    {

        try {

            if (isset($param['key'])) {

                $key = $param['key'];

                TTransaction::open('database');

                $object = new EspecialRecord($key);

                $this->form->setData($object);

                TTransaction::close();

            }

        } catch (Exception $e) {


            new TMessage('error', '<b>Error</b> ' . $e->getMessage() . "<br/>");

            TTransaction::rollback();

        }

    }

}

?>