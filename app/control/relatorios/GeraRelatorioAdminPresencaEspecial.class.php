<?php

//Kelvin Brucelee

class GeraRelatorioAdminPresencaEspecial extends TPage
{
    private $form;
    
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Customer_report');
        $this->form->setFormTitle('<font color=blue><b>Relátorio Presença Especial</b></font>');
        
        $datapresenca = new TEntry('datapresenca');

        $datapresenca->placeholder = 'EX: 2019-11 ou 2019-11-01';

        $matricula = new TEntry('matricula');
        $matricula->setMask('999999999999999999999999999999');

        $output_type  = new TRadioGroup('output_type');
        
        $this->form->addFields( [new TLabel('Matrícula:')], [$matricula] );
        $this->form->addFields( [new TLabel('Data:')], [$datapresenca] );
        $this->form->addFields( [new TLabel('Formato:')],   [$output_type] );
        
        $datapresenca->setSize( '70%' );
        $matricula->setSize( '70%' );
        $output_type->setUseButton();
        $options = ['html' =>'HTML', 'pdf' =>'PDF', 'rtf' =>'RTF', 'xls' =>'XLS'];
        $output_type->addItems($options);
        $output_type->setValue('pdf');
        $output_type->setLayout('horizontal');
        
        $this->form->addAction( 'Gerar', new TAction(array($this, 'onGenerate')), 'fa:download blue');
        
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($this->form);
        
        parent::add($vbox);
    }

    function onGenerate()
    {
        try
        {
            TTransaction::open('database');
            
            $data = $this->form->getData();
            
            $repository = new TRepository('EspecialRecord');
            $criteria   = new TCriteria;
            $criteria->add(new TFilter('system_unit_id','=',TSession::getValue('userunitid')));
            
            if ($data->matricula)
            {
                $criteria->add(new TFilter('matricula', '=', "{$data->matricula}"));
            }
            if ($data->datapresenca)
            {
                $criteria->add(new TFilter('datapresenca', 'like', "%{$data->datapresenca}%"));
            }
           
            $customers = $repository->load($criteria);
            $format  = $data->output_type;
            
            if ($customers)
            {
                $widths = array(200, 100, 137, 80, 60,130);
                
                switch ($format)
                {
                    case 'html':
                        $table = new TTableWriterHTML($widths);
                        break;
                    case 'pdf':
                        $table = new TTableWriterPDF($widths);
                        break;
                    case 'rtf':
                        $table = new TTableWriterRTF($widths);
                        break;
                    case 'xls':
                        $table = new TTableWriterXLS($widths);
                        break;
                }
                
                if (!empty($table))
                {
                    // create the document styles
                    $table->addStyle('title', 'Arial', '10', '',    '#ffffff', '#607EFE');
                    $table->addStyle('datap', 'Arial', '10', '',    '#000000', '#E3E3E3', 'LR');
                    $table->addStyle('datai', 'Arial', '10', '',    '#000000', '#ffffff', 'LR');
                    $table->addStyle('header', 'Times', '16', 'BI', '#6F2828', '#FFF8D6');
                    $table->addStyle('footer', 'Times', '12', 'BI', '#2B2B2B', '#B5FFB4');
                    
                    $table->setHeaderCallback( function($table) {
                        
                        $table->addRow();
                        $table->addCell('Listagem de Presença', 'center', 'header', 6);
                        
                        $table->addRow();
                        $table->addCell('Nome',      'left', 'title');
                        $table->addCell('Matrícula',  'center', 'title');
                        $table->addCell('Curso', 'center', 'title');
                        $table->addCell('Data',  'center', 'title');
                        $table->addCell('Hora',     'center', 'title');
                        $table->addCell('IP', 'center', 'title');
                    });
                    
                       $table->setFooterCallback( function($table) {
                        $table->addRow();
                        $table->addCell(date('Y-m-d h:i:s'), 'center', 'footer', 8);
                    });
                    
                    $colour= FALSE;
                    
                    foreach ($customers as $customer)
                    {
                        $style = $colour ? 'datap' : 'datai';
                        $table->addRow();
                        $table->addCell($customer->user,           'left',   $style);
                        $table->addCell($customer->matriculauser,  'center', $style);
                        $table->addCell($customer->cursoatual,      'center', $style);
                        $table->addCell($customer->datapresenca,           'center',   $style);
                        $table->addCell($customer->hora,          'center',   $style);
                        $table->addCell($customer->atual_ip,          'center',   $style);
                        
                        $colour = !$colour;
                    }
                    
                    $output = "app/output/RelatorioPresencaEspecial.{$format}";
                    
                    if (!file_exists($output) OR is_writable($output))
                    {
                        $table->save($output);
                        parent::openFile($output);
                    }
                    else
                    {
                        throw new Exception(_t('Permission denied') . ': ' . $output);
                    }
                }
            }
            else
            {
                new TMessage('error', 'A Informação Selecionada Não Existe');
            }
    
            $this->form->setData($data);
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            
            TTransaction::rollback();
        }
    }
}
