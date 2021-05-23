<?php

//Kelvin Brucelee

class AgendamentoForm extends TPage
{
    private $form;

    function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder('form_agendamento');
        $this->form->setFormTitle('<font color=blue><b>Formulário de Agendamentos</b></font>');

        $id = new \Adianti\Widget\Form\THidden('id');

        $criteria = new TCriteria;
        $criteria->add(new TFilter('id', '>=', 1));
        $criteria->add(new TFilter('id', '<=', 10));
        $criteria->setProperty('order', 'id');

        $title = new TEntry('title');
        $color = new TColor('color');

        $start_time = new TDateTime('start_time');
        $end_time = new TDateTime('end_time');
        $description = new TText('description');

        $title->setSize('50%');
        $color->setSize('50%');
        $start_time->setSize('50%');
        $start_time->setSize('50%');
        $end_time->setSize('50%');
        $description->setSize('50%');

        $this->form->addFields([], [$id]);
        $this->form->addFields([new TLabel('Titulo: <font color="red"> *</font>')], [$title]);
        $this->form->addFields([new TLabel('Cor: <font color="red"> *</font>')], [$color]);
        $this->form->addFields([new TLabel('Data Inicio: <font color="red"> *</font>')], [$start_time]);
        $this->form->addFields([new TLabel('Data Fim: <font color="red"> *</font>')], [$end_time]);
        $this->form->addFields([new TLabel('Obs: <font color="red"> *</font>')], [$description]);

        $btn = $this->form->addAction('Salvar', new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';

        $this->form->addAction(_t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addAction(_t('Back'), new TAction(array('AgendamentoList', 'onReload')), 'fa:arrow-circle-o-left blue');
        $this->form->addFields([new TLabel('')], [TElement::tag('label', '<font color="red"> *</font> Campos obrigatórios' ) ]);

        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($this->form);

        parent::add($vbox);
    }

    public function onEdit($param)
    {
        try {
            if (isset($param['key'])) {
                $key = $param['key'];

                TTransaction::open('database');

                $object = new CalendarioEventoRecord($key);

                $this->form->setData($object);

                TTransaction::close();

                return $object;
            } else {
                $this->form->clear();
            }
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onSave($param)
    {
        try {
            TTransaction::open('database');
            $this->form->validate();

            $object = $this->form->getData('CalendarioEventoRecord');
            $object->situacao = 'A';
            $object->store();

            $this->form->setData($object);

            $action_ok = new TAction( [ 'AgendamentoList', "onReload" ] );
            new TMessage( "info", "Registro salvo com sucesso!", $action_ok );

            TTransaction::close();
            $this->form->clear();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
}