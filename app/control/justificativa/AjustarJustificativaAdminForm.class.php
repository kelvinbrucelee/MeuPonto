<?php

//Kelvin Brucelee

class AjustarJustificativaAdminForm extends TPage
{

    private $form;

    public function __construct()
    
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder('form_ajustarjustificativaadmin');
        $this->form->class = "form_ajustarjustificativaadmin";
        $this->form->setFormTitle('<font color=blue><b>Ajustar Ponto</b></font>');
        
        $id = new THidden('id');
        $username = TSession::getValue('username');
        $dataponto = new TDate('dataponto');

        $tipojustificativa_id = new TDBCombo('tipojustificativa_id','database','TipoJustificativaRecord','id','nome');

        $observacao = new TText('observacao');
        $horario = new TTime('horaponto');

        $situacao = new TCombo('situacao');
        $combo_items = array();
        $combo_items['0'] ='Entrada';
        $combo_items['1'] ='Saida';
        $situacao->addItems($combo_items);

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

        $dataponto->setSize('50%');
        $tipojustificativa_id->setSize('50%');
        $semana->setSize('70%');
        $observacao->setSize('70%');
        $horario->setSize('50%');
        $situacao->setSize('50%');

        $dataponto->addValidation('Data' , new TRequiredValidator);
        $tipojustificativa_id->addValidation('Tipo Justificativa' , new TRequiredValidator);
        $semana->addValidation('Dia da Semana' , new TRequiredValidator);
        $observacao->addValidation('Observação' , new TRequiredValidator);
        $horario->addValidation('Horário' , new TRequiredValidator);
        $situacao->addValidation('Situação' , new TRequiredValidator);

        $action1 = new TAction(array($this, 'onSave'));
        $action1->setParameter('key', '' . filter_input(INPUT_GET, 'key') . '');
        $action1->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');

        $this->form->addFields([$id]);
        $this->form->addFields([new TLabel('Nome:<font color=red><b>*</b></font>')], [$username]);
        $this->form->addFields([new TLabel('Data:<font color=red><b>*</b></font>')], [$dataponto]);
        $this->form->addFields([new TLabel('Semana:<font color=red><b>*</b></font>')], [$semana]);
        $this->form->addFields([new TLabel('Motivo:<font color=red><b>*</b></font>')], [$tipojustificativa_id]);
        $this->form->addFields([new TLabel('Ponto:<font color=red><b>*</b></font>')], [$situacao]);
        $this->form->addFields([new TLabel('Horario:<font color=red><b>*</b></font>')], [$horario]);
        $this->form->addFields([new TLabel('<font color=red><b>Obs:*</b></font>')], [$observacao]);
        $this->form->addFields( [new TFormSeparator("(<font color=red><b>*</b></font>)Campos obrigatórios")] );
      
        $this->form->addAction('Ajustar', $action1, 'fa:clock-o')->class = 'btn btn-sm btn-primary';
        $this->form->addAction('Voltar', new TAction(array('AjustarJustificativaAdminList', 'onReload')), 'fa:arrow-circle-o-left blue');

        parent::add($this->form);

    }

    function onSave($param = NULL)
    {

        try {

            TTransaction::open('database');

            $this->form->validate();

            $object = $this->form->getData('JustificativaRecord');
            $object->dataalteracao = date("Y-m-d H:i:s");
            $object->usuarioalteracao = TSession::getValue('userid');
           
            $object->store();

            TTransaction::close();

            $action_ok = new TAction( [ 'AjustarJustificativaAdminList', "onReload" ] );
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

                $object = new JustificativaRecord($key);

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