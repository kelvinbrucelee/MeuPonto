<?php

//Kelvin Brucelee

class PontoEspecialPainel extends TPage
{

    public function __construct()
    {
        parent::__construct();

        if (!TSession::getValue('logged')) {
            header('Location: index.php');
        }
        $icon = new TImage( 'app/images/rr.png' );

        $panel = new TPanelGroup("<h4>Presença Registrada com Sucesso!!</h4>");
        $panel->add($icon);
        $panel->style = "width:300px; margin-top: 200px;";

        parent::add($panel);
    }

    function onLoad()
    {
    }
}

?>
<script type='text/JavaScript'>
   setTimeout(function () {
       window.location.href = 'index.php?class=PontoEspecialLogin&method=onLogout&static=1'; 
    }, 2000); 
</script>
