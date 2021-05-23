<?php

//Kelvin Brucelee

class GeraRelatorioBolsista extends TPage
{
    private $form;
    
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Customer_report');
        $this->form->setFormTitle('<font color=blue><b>Relátorio</b></font>');
        
        $dataponto         = new TEntry('dataponto');

        $dataponto->placeholder = 'EX: 2019-11 ou 2019-11-01';

        $output_type  = new TRadioGroup('output_type');
        
        $this->form->addFields( [new TLabel('Data:')], [$dataponto] );
        $this->form->addFields( [new TLabel('Formato:')],   [$output_type] );
 
        $dataponto->setSize( '70%' );
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
            
            $repository = new TRepository('PontoRecord');
            $criteria = new TCriteria();
            $criteria->add(new TFilter('username','=',TSession::getValue('username')));
            $criteria->add(new TFilter('system_user_unit_id','=',TSession::getValue('userunitid')));
            
            if ($data->dataponto)
            {
                $criteria->add(new TFilter('dataponto', 'like', "%{$data->dataponto}%"));
            }
           
            $customers = $repository->load($criteria);
            $format  = $data->output_type;
            
            if ($customers)
            {
                $widths = array(250, 150, 100, 80,80,80);
                
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
                        $table->addCell('Listagem de Ponto', 'center', 'header', 6);
                        
                        $table->addRow();
                        $table->addCell('Nome',      'left', 'title');
                        $table->addCell('Matrícula',      'center', 'title');
                        $table->addCell('Data',  'center', 'title');
                        $table->addCell('Hora',     'center', 'title');
                        $table->addCell('Tipo',     'center', 'title');
                        $table->addCell('Situação', 'center', 'title');
                    });
                    
                    $table->setFooterCallback( function($table) {
                        $table->addRow();
                        $table->addCell(date('Y-m-d h:i:s'), 'center', 'footer', 6);
                    });
                    
                    $colour= FALSE;
                    
                    foreach ($customers as $customer)
                    {
                        $style = $colour ? 'datap' : 'datai';
                        $table->addRow();
                        $table->addCell($customer->username,           'left',   $style);
                        $table->addCell($customer->matriculauser,           'center',   $style);
                        $table->addCell($customer->dataponto,  'center', $style);
                        $table->addCell($customer->horaponto,          'center',   $style);
                        $table->addCell($customer->justificado,          'center',   $style);
                        if($customer->situacao == 0){
                        $table->addCell($customer = 'Entrada',          'center',   $style);
                        }else{
                        $table->addCell($customer = 'Saida',          'center',   $style);
                        }
                        $colour = !$colour;
                    }
                    
                    $output = "app/output/RelatorioPonto.{$format}";
                    
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
                new TMessage('error', 'Informações não existe em nossa base de dados');
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
