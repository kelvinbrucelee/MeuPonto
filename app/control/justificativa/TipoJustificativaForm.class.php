<?php

//Kelvin Brucelee

class TipoJustificativaForm extends TPage
{

    private $form;

    public function __construct()
    
    {

        parent::__construct();
      
        $this->form = new BootstrapFormBuilder('form_tipojustificativa');
        $this->form->class = "form_tipojustificativa";
        $this->form->setFormTitle('<font color=blue><b>Formulário de Tipo de Justificativa</b></font>');

        $id = new THidden('id');
        $nome = new TEntry('nome');

        $nome->setSize('70%');
    
        $this->form->addFields([$id]);
        $this->form->addFields([new TLabel('Nome:')], [$nome],[new TLabel(NULL)]);
        $this->form->addAction('Salvar', new TAction(array($this, 'onSave')), 'fa:plus-circle')->class = 'btn btn-sm btn-primary';
        $this->form->addAction('Voltar', new TAction(array('TipoJustificativaList', 'onReload')), 'fa:arrow-circle-o-left blue');

        parent::add($this->form);

    }
    function onSave($param = NULL)
    {

        try {

            TTransaction::open('database');

            $this->form->validate();

            $ip = $_SERVER["REMOTE_ADDR"];

            $object = $this->form->getData('TipoJustificativaRecord');
            $object->registro_ip = $ip;
           
            $object->store();

            TTransaction::close();

            $action_ok = new TAction( [ 'TipoJustificativaList', "onReload" ] );
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

                $object = new TipoJustificativaRecord($key);

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