<?php

//Kelvin Brucelee

class CalendarDatabaseView extends TPage
{
    private $fc;
    
    public function __construct()
    {
        parent::__construct();
        $this->fc = new TFullCalendar(date('Y-m-d'), 'month');
        $this->fc->setReloadAction(new TAction(array($this, 'getEvents')));
        $this->fc->disableDragging();
        $this->fc->disableResizing();
        parent::add( $this->fc );
    }
    
    public static function getEvents($param=NULL)
    {
        $return = array();
        try
        {
            TTransaction::open('database');
            
            $events = CalendarioEventoRecord::where('start_time', '>=', $param['start'])
                                   ->where('end_time',   '<=', $param['end'])->load();
                                   
            if ($events)
            {
                foreach ($events as $event)
                {
                    $event_array = $event->toArray();
                    $event_array['start'] = str_replace( ' ', 'T', $event_array['start_time']);
                    $event_array['end']   = str_replace( ' ', 'T', $event_array['end_time']);
                    
                    $popover_content = $event->render("<b>Título</b>: {title} <br> <b>Descrição</b>: {description}");
                    $event_array['title'] = TFullCalendar::renderPopover($event_array['title'], 'Anotações', $popover_content);
                    
                    $return[] = $event_array;
                }
            }
            TTransaction::close();
            echo json_encode($return);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    public function onReload($param = null)
    {
        if (isset($param['view']))
        {
            $this->fc->setCurrentView($param['view']);
        }
        
        if (isset($param['date']))
        {
            $this->fc->setCurrentDate($param['date']);
        }
    }
}