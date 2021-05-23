<?php

//Kelvin Brucelee

class AjustarPontoAdminForm extends TPage
{

    private $form;

    public function __construct()
    
    {

        parent::__construct();

        $this->form = new BootstrapFormBuilder('form_ajustarpontoadmin');
        $this->form->class = "form_ajustarpontoadmin";
        $this->form->setFormTitle('<font color=blue><b>Ajustar Ponto</b></font>');
        
        $id = new THidden('id');

        $username = new TEntry('username');
        $username->setValue(TSession::getValue('username'));
        $username->setEditable(FALSE);

        $semana = new TRadioGroup('semana');
        $semana->addItems(array('1'=>'Dias'));

        $itemdia = array();
        $itemdia['ATESTADO'] = 'ATESTADO';
        $itemdia['ABONO'] = 'ABONO';
        $itemdia['FOLGA COMPENSADA'] = 'FOLGA COMPENSADA';

        $semana->addItems($itemdia);

        $dataponto = new TDate('dataponto');
        $horaponto = new TTime('horaponto');

        $semana = new TRadioGroup('semana');
        $semana->addItems(array('1'=>'Dias'));
    
        $items= array();
        $items['DOMINGO'] = 'DOMINGO';
        $items['SEGUNDA-FEIRA'] = 'SEGUNDA-FEIRA';
        $items['TERCA-FEIRA'] = 'TERCA-FEIRA';
        $items['QUARTA-FEIRA'] = 'QUARTA-FEIRA';
        $items['QUINTA-FEIRA'] = 'QUINTA-FEIRA';
        $items['SEXTA-FEIRA'] = 'SEXTA-FEIRA';
        $items['SABADO'] = 'SABADO';
        $semana->addItems($items);

        $username->setSize('70%');
        $semana->setSize('70%');
        $dataponto->setSize('50%');
        $horaponto->setSize('50%');

        $dataponto->addValidation('Data' , new TRequiredValidator);
        $semana->addValidation('Situação' , new TRequiredValidator);

        $action1 = new TAction(array($this, 'onSave'));
        $action1->setParameter('key', '' . filter_input(INPUT_GET, 'key') . '');
        $action1->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');
       
        $this->form->addFields([$id]);
        $this->form->addFields([new TLabel('Nome:<font color=red><b>*</b></font>')], [$username]);
        $this->form->addFields([new TLabel('Data:<font color=red><b>*</b></font>')], [$dataponto]);
        $this->form->addFields([new TLabel('Dia Semana:<font color=red><b>*</b></font>')], [$semana]);
        $this->form->addFields([new TLabel('Hora:<font color=red><b>*</b></font>')], [$horaponto]);
      
        $this->form->addAction('Ajustar', $action1, 'fa:clock-o')->class = 'btn btn-sm btn-primary';
        $this->form->addAction('Voltar', new TAction(array('AjustarPontoAdminList', 'onReload')), 'fa:arrow-circle-o-left blue');

        parent::add($this->form);

    }

    function onSave($param = NULL)
    {

        try {

            TTransaction::open('database');

            $this->form->validate();

            $object = $this->form->getData('PontoRecord');
            $object->dataalteracao = date("Y-m-d H:i:s");
            $object->usuarioalteracao = TSession::getValue('userid');
             
            unset($object->observacao);
           
            $object->store();

            TTransaction::close();

            $action_ok = new TAction( [ 'AjustarPontoAdminList', "onReload" ] );
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

                $object = new PontoRecord($key);

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