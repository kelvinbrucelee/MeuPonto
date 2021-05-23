<?php
/**
 * CalendarEvent Active Record
 * @author  <your-name-here>
 */
class CalendarioEventoRecord extends TRecord
{
    const TABLENAME = 'calendario_evento';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('start_time');
        parent::addAttribute('end_time');
        parent::addAttribute('color');
        parent::addAttribute('title');
        parent::addAttribute('description');
        //parent::addAttribute('clientes_id');
        parent::addAttribute('situacao');
    }

    private $cliente;

    function get_cliente() {
        if (empty($this->clientes)) {
            $this->clientes = new ClienteRecord($this->clientes_id);
        }
        return $this->clientes->nome;
    }


}
