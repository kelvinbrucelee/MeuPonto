<?php
/**
 * SystemAccessLogStats
 *
 * @version    1.0
 * @package    control
 * @subpackage log
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class EstatisticaEspecial extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();

        $html = new THtmlRenderer('app/resources/google_bar_chart.html');

        $accesses = EspecialRecord::getStatsByDay();

        $data = array();
        $data[] = [ _t('Day'),('Presenças') ];
        
        foreach ($accesses as $day => $access)
        {
            $data[] = [ _t('Day') . ' ' . $day, $access ];
        }

        $panel = new TPanelGroup(('<font color=blue><b>Estatísticas Presença Especial</b></font>'));
        $panel->add($html);

        // replace the main section variables
        $html->enableSection('main', array('data' => json_encode($data),
                                           'width'  => '100%',
                                           'height'  => '300px',
                                           'title'  => 'Accesses by day',
                                           'ytitle' => 'Accesses',
                                           'xtitle' => 'Day'));

        // add the template to the page
        $container = new TVBox;
        $container->style = 'width: 97%';
        $container->add($panel);
        parent::add($container);
    }
}
