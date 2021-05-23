<?php

//Kelvin Brucelee

class FormQrcodeView extends TPage
{
    private $form;
    
    function __construct()
    {
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle('<font color=blue><b>Gerar QR Code</b></font>');
        
        $template = new TText('template');
        
        $this->form->addFields( [new TLabel('QR Code')],  [$template] );
        
        $template->setSize('100%', 100);
        
        $label  = '' . "\n";
        $label .= '<b>Nome</b>: {$name}' . "\n";
        $label .= '<b>Matrícula</b>: {$login}' . "\n";
        $label .= '#qrcode#' . "\n";
        
        $template->setValue($label);
        
        $this->form->addAction('Gerar', new TAction(array($this, 'onSend')), 'fa:check-circle-o green');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($this->form);

        parent::add($vbox);
    }
    
    public function onSend($param)
    {
        try
        {
            $data = $this->form->getData();
            $this->form->setData($data);
            
            $properties['leftMargin']    = 12;
            $properties['topMargin']     = 12;
            $properties['labelWidth']    = 64;
            $properties['labelHeight']   = 54;
            $properties['spaceBetween']  = 4;
            $properties['rowsPerPage']   = 5;
            $properties['colsPerPage']   = 3;
            $properties['fontSize']      = 12;
            $properties['barcodeHeight'] = 35;
            $properties['imageMargin']   = 0;
            
            $generator = new AdiantiBarcodeDocumentGenerator;
            $generator->setProperties($properties);
            $generator->setLabelTemplate($data->template);


        TTransaction::open('database');

        $repository = new TRepository('SystemUser');

        $criteria = new TCriteria;
        $criteria->add(new TFilter('autorizacao', '=', 'S'));

        $logins = $repository->load($criteria, FALSE);

        foreach ($logins as $log)
        {
            $log->login;
            $generator->addObject($log);
        }
            
            $generator->setBarcodeContent('../index.php?class=PontoEspecialLogin&method=onLogin&login={login}');
            $generator->generate();
            $generator->save('app/output/qrcodes.pdf');
            
            $window = TWindow::create('Gerar QR Code', 0.900, 0.900);
            $object = new TElement('object');
            $object->data  = 'app/output/qrcodes.pdf';
            $object->type  = 'application/pdf';
            $object->style = "width: 100%; height:calc(100% - 10px)";
            $window->add($object);
            $window->show();
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}