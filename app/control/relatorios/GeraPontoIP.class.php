<?php

//Kelvin Brucelee

class GeraPontoIP extends TPage
{
    private $form;
    
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Customer_report');
        $this->form->setFormTitle('<font color=blue><b>Relátorio de Ponto</b></font>');
        
        $username      = new TDBUniqueSearch('name', 'permission', 'SystemUser', 'name', 'name');
        $dataponto         = new TEntry('dataponto');
        $curso         = new TEntry('curso');

        $dataponto->placeholder = 'EX: 2019-11 ou 2019-11-01';

        $output_type  = new TRadioGroup('output_type');
        
        $this->form->addFields( [new TLabel('Nome:')],     [$username] );
        $this->form->addFields( [new TLabel('Data:')], [$dataponto] );
        $this->form->addFields( [new TLabel('Curso:')], [$curso] );
        $this->form->addFields( [new TLabel('Formato:')],   [$output_type] );
 
        $username->setSize( '70%' );
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
            $criteria->add(new TFilter('system_user_unit_id','=',TSession::getValue('userunitid')));
            
            if ($data->name)
            {
                $criteria->add(new TFilter('username', '=', "{$data->name}"));
            }
            if ($data->dataponto)
            {
                $criteria->add(new TFilter('dataponto', 'like', "%{$data->dataponto}%"));
            }
            if ($data->curso)
            {
                $criteria->add(new TFilter('curso', 'like', "%{$data->curso}%"));
            }
           
            $customers = $repository->load($criteria);
            $format  = $data->output_type;
            
            if ($customers)
            {
                $widths = array(230, 150, 95, 75, 145,75);
                
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
                        $table->addCell('Listagem de Ponto', 'center', 'header', 7);
                        
                        $table->addRow();
                        $table->addCell('Nome',      'left', 'title');
                        $table->addCell('Matrícula',      'center', 'title');
                        $table->addCell('Data',  'center', 'title');
                        $table->addCell('Hora',     'center', 'title');
                        $table->addCell('Ponto IP',     'center', 'title');
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
                        $table->addCell($customer->ponto_ip,      'center', $style);
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
