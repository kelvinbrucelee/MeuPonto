<?php

//Kelvin Brucelee

class PontoEspecialLogin extends TPage
{
    public function __construct()
    {
        parent::__construct();

        $ini = AdiantiApplicationConfig::get();

        $this->{'style'} .= 'clear:both;text-align:center;';
        $this->form = new BootstrapFormBuilder('form_login');

        $icon = new TImage('app/images/meupontoespecial.png');
        $icon->style = "width:70%";
        $login = new TEntry('login');
        $login->setMask('999999999999999999999999999999');
    
        $login->setSize('70%', 40);

        $login->style = 'height:35px; font-size:14px;float:left;border-bottom-left-radius: 0;border-top-left-radius: 0;';
      
        $login->placeholder = 'Matrícula';
       
        $login->autofocus = 'autofocus';

        $user = '<span style="float:left;margin-left:44px;height:35px;" class="login-avatar"><span class="glyphicon glyphicon-user"></span></span>';
        $locker = '<span style="float:left;margin-left:44px;height:35px;" class="login-avatar"><span class="glyphicon glyphicon-lock"></span></span>';

        $this->form->addFields([$icon]);
        $linkTitulo = new \Adianti\Widget\Form\TLabel("<h4>Registrar Presença</h4>");
        $this->form->addFields([$linkTitulo]);
        $this->form->addFields([$user, $login]);
        
        $btn = $this->form->addAction('Confirmar', new TAction(array('PontoEspecialLogin', 'onLogin')), 'fa:sign-in');
        $btn->class = 'btn btn-warning';
        $btn->style = 'height: 40px;width: 90%;display: block;margin: auto;font-size:17px;';

        $btn = $this->form->addAction(_t('Back'), new TAction(array('LoginForm', 'onLoad')), 'fa:reply-all orange');
        $btn->style = 'margin: 15px 125px 0 125px; height: 40px;width: 40%;display: block;font-size:17px;';

        $wrapper = new TElement('div');
        $wrapper->style = 'margin:auto; margin-top:100px;max-width:460px;';
        $wrapper->id = 'login-wrapper';
        $wrapper->add($this->form);

        parent::add($wrapper);
    }

    function onLoad()
    {
    }

    public static function onLogin($param)
    {
        $ini = \Adianti\Core\AdiantiApplicationConfig::get();

        try {
            TTransaction::open('permission');
            $data = (object)$param;

            if (empty($data->login)) {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Matrícula"));
            }

            $logador = PontoEspecialLogin::authenticate($data->login);

        
            TTransaction::open('database');

               if ($logador) {

                \Adianti\Registry\TSession::regenerate();

                TSession::setValue('logged', TRUE);
                TSession::setValue('login', $data->login);
                TSession::setValue('userid', $user->id);
                TSession::setValue('username', $user->name);

                PontoEspecialLogin::registerLogin();

                TTransaction::close();
                
               AdiantiCoreApplication::gotoPage('PontoEspecialPainel'); // reload

            } else {
                new \Adianti\Widget\Dialog\TMessage('error', 'Verifique se você esta autorizado ou digitou a matrícula corretamente, em seguida tente novamente.');
            }

            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TSession::setValue('logged', FALSE);
            TTransaction::rollback();
        }
    }

    public static function registerLogin()
    {

        TTransaction::open('database');

            $repository = new TRepository('SystemUser');

            $criteria = new TCriteria;
            $criteria->add(new TFilter('login', '=', TSession::getValue('login')));
            $autoriza = $repository->load($criteria);

            if ($autoriza) {
                foreach ($autoriza as $auto) {
                    $at = $auto->id;
                    $ano = $auto->anocurso;
                    $curso1 = $auto->curso;
                    $unidades = $auto->system_unit_id;
                }
            }
        $ip = $_SERVER["REMOTE_ADDR"];

        $object = new EspecialRecord;
        
        $object->matricula = TSession::getValue('login');
        $object->system_user_id = $at;
        $object->anocurso = $ano;
        $object->curso = $curso1;
        $object->sessionid = session_id();
        $object->login_time = date("Y-m-d H:i:s");
        $object->datapresenca = date("Y-m-d");
        $object->atual_ip = $ip;
        $object->hora = date("H:i:s");
        $object->system_unit_id = $unidades;
        
        $object->store();
        TTransaction::close(); 
    }
    
    public static function onLogout()
    {
        SystemAccessLog::registerLogout();
        TSession::freeSession();
        AdiantiCoreApplication::gotoPage('LoginForm', '');
    }

    public static function authenticate($login)
    {
        
        TTransaction::open('database');
 
        $repository = new \Adianti\Database\TRepository('SystemUser');

        $criteria = new \Adianti\Database\TCriteria();
        $sit = "S";
        $criteria->add(new \Adianti\Database\TFilter('login', '=', $login));
        $criteria->add(new \Adianti\Database\TFilter('autorizacao', '=', $sit));
        
        $result = $repository->count($criteria);

        TTransaction::close();

        if ($result > 0) {
            return true;
        } else {
            return false;
        }

    }
}
?>


