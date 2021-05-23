<?php

//Kelvin Brucelee

class PontoSaidaForm extends TPage
{

    private $form;

    public function __construct()
    
    {

        parent::__construct();

        $this->form = new BootstrapFormBuilder('form_pontosaida');
        $this->form->class = "form_pontosaida";
        $this->form->setFormTitle('<font color=red><b>Saida</b></font>');

        $id = new THidden('id');

        $username = TSession::getValue('username');
        $dataponto = date("d/m/Y");
        $horaponto = date("H:i:s");

        $semana = date("w");
        switch($semana){
            case"0": $semana = "DOMINGO"; break;
            case"1": $semana = "SEGUNDA-FEIRA"; break;
            case"2": $semana = "TERCA-FEIRA"; break;
            case"3": $semana = "QUARTA-FEIRA"; break;
            case"4": $semana = "QUINTA-FEIRA"; break;
            case"5": $semana = "SEXTA-FEIRA"; break;
            case"6": $semana = "SABADO"; break;
        };

        $this->form->addFields([$id]);
        $this->form->addFields([new TLabel('Nome:')], ['<font color=red><b>'.$username.'</b></font>']);
        $this->form->addFields([new TLabel('Data:')],[$dataponto]);
        $this->form->addFields([new TLabel('Hora:')],[$horaponto]);
        $this->form->addFields([new TLabel('Dia:')], [$semana]);

        $this->form->addAction('Saida', new TAction(array($this, 'onSave')), 'fa:plus-circle')->class = 'btn btn-sm btn-danger';
        $this->form->addAction('Voltar', new TAction(array('PontoList', 'onReload')), 'fa:arrow-circle-o-left blue');

        parent::add($this->form);

    }

    function onSave($param = NULL)
    {

        try {

            TTransaction::open('database');

            $repository = new TRepository('SystemUser');

            $criteria = new TCriteria;
            $criteria->add(new TFilter('id', '=', TSession::getValue('userid')));
            $autoriza = $repository->load($criteria);

            if ($autoriza) {
                foreach ($autoriza as $auto) {
                    $system_user = $auto->id;
                    $ano = $auto->anocurso;
                    $curso1 = $auto->curso;
                    $periodo1 = $auto->periodo;
                    $loginatual = $auto->login;
                }
            }

            $this->form->validate();

            $ip = $_SERVER["REMOTE_ADDR"];

            $computadorip = $_SERVER["REMOTE_ADDR"];
            $host = gethostbyaddr($computadorip);

            $object = $this->form->getData('PontoRecord');

            $semana = date("w");

            switch($semana){
            case"0": $semana = "DOMINGO"; break;
            case"1": $semana = "SEGUNDA-FEIRA"; break;
            case"2": $semana = "TERCA-FEIRA"; break;
            case"3": $semana = "QUARTA-FEIRA"; break;
            case"4": $semana = "QUINTA-FEIRA"; break;
            case"5": $semana = "SEXTA-FEIRA"; break;
            case"6": $semana = "SABADO"; break;
             };

            $object->username = TSession::getValue('username');
            $object->semana = $semana;
            $object->dataponto = date("Y-m-d");
            $object->justificado = 'P';
            $object->horaponto = date("H:i:s");
            $object->situacao = 1;
            $object->computador_ip = $computadorip;
            $object->curso = $curso1;
            $object->ponto_ip = $ip;
            $object->system_user_id = $system_user;
            $object->system_user_unit_id = TSession::getValue('userunitid');
            $object->matricula = $loginatual;
           
            $object->store();

            TTransaction::close();

            $action_ok = new TAction( [ 'PontoList', "onReload" ] );
            new TMessage( "info", "Saida registrada com sucesso!", $action_ok );

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