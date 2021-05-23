<?php

//Kelvin Brucelee

class JustificativaForm extends TPage
{

    private $form;

    public function __construct()
    
    {

        parent::__construct();

        $this->form = new BootstrapFormBuilder('form_justificativaentrada1');
        $this->form->class = "form_justificativaentrada1";
        $this->form->setFormTitle('<font color=blue><b>Justificativa</b></font>');

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
        $semana->setSize('50%');
        $observacao->setSize('70%');
        $horario->setSize('50%');
        $situacao->setSize('50%');

        $dataponto->addValidation('Data' , new TRequiredValidator);
        $tipojustificativa_id->addValidation('Tipo Justificativa' , new TRequiredValidator);
        $semana->addValidation('Dia da Semana' , new TRequiredValidator);
        $observacao->addValidation('Observação' , new TRequiredValidator);
        $horario->addValidation('Horário' , new TRequiredValidator);
        $situacao->addValidation('Situação' , new TRequiredValidator);

        $this->form->addFields([$id]);
        $this->form->addFields([new TLabel('Nome:<font color=red><b>*</b></font>')], [$username]);
        $this->form->addFields([new TLabel('Data:<font color=red><b>*</b></font>')], [$dataponto]);
        $this->form->addFields([new TLabel('Semana:<font color=red><b>*</b></font>')], [$semana]);
        $this->form->addFields([new TLabel('Motivo:<font color=red><b>*</b></font>')], [$tipojustificativa_id]);
        $this->form->addFields([new TLabel('Ponto:<font color=red><b>*</b></font>')], [$situacao]);
        $this->form->addFields([new TLabel('Horario:<font color=red><b>*</b></font>')], [$horario]);
        $this->form->addFields([new TLabel('<font color=red><b>Obs:*</b></font>')], [$observacao]);
        $this->form->addFields( [new TFormSeparator("(<font color=red><b>*</b></font>)Campos obrigatórios")] );
        
        $this->form->addAction('Justificar', new TAction(array($this, 'onSave')), 'fa:plus-circle')->class = 'btn btn-sm btn-primary';
        $this->form->addAction('Voltar', new TAction(array('JustificativaList', 'onReload')), 'fa:arrow-circle-o-left blue');

        parent::add($this->form);

    }
    public function onReload( $param = NULL )
    {

       
    }

    function onSave($param = NULL)
    {

        try {

            TTransaction::open('database');


            $object = $this->form->getData('JustificativaRecord');

            if ( empty($object->dataponto) )
            {
                throw new Exception('Por favor selecione a data');
                
            }
            if ( empty($object->semana) )
            {
                throw new Exception('Por favor selecione a semana ');
            }
            if ( empty($object->tipojustificativa_id) )
            {
                throw new Exception('Por favor selecione o tipo de justificativa');
            }
            
            if ( empty($object->observacao) )
            {
                throw new Exception('Por favor digite a observação');
            }
            $object->username = TSession::getValue('username');
            $object->system_user_id = TSession::getValue('userid');
            $object->datajustificativa = date("Y-m-d");
            $object->horariojustificativa = date("H:i:s");
            $object->system_user_unit_id = TSession::getValue('userunitid');

            $object->store();

            $repository = new TRepository('SystemUser');

            $criteria = new TCriteria;
            $criteria->add(new TFilter('id', '=', TSession::getValue('userid')));
            $autoriza = $repository->load($criteria);

            if ($autoriza) {
                foreach ($autoriza as $auto) {
                    $system_user = $auto->id;
                    $curso1 = $auto->curso;
                    $loginatual = $auto->login;
                }
            }

            $objeto = $this->form->getData('PontoRecord');
            
            $ip = $_SERVER["REMOTE_ADDR"];

            $computadorip = $_SERVER["REMOTE_ADDR"];
            $host = gethostbyaddr($computadorip);

            $objeto->username = TSession::getValue('username');
            $objeto->justificado = 'J';
            $objeto->computador_ip = $computadorip;
            $objeto->ponto_ip = $ip;
            $objeto->curso = $curso1;
            $objeto->system_user_id = $system_user;
            $objeto->system_user_unit_id = TSession::getValue('userunitid');
            $objeto->matricula = $loginatual;
           
            unset($objeto->tipojustificativa_id);
            unset($objeto->observacao);
        
            $objeto->store();

            $this->form->validate();
           
            TTransaction::close();

            $action_ok = new TAction( [ 'JustificativaList', "onReload" ] );
            new TMessage( "info", "Justificativa registrada com sucesso!", $action_ok );

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