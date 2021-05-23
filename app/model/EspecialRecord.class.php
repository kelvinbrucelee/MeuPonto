<?php

class EspecialRecord extends TRecord {

    const TABLENAME = 'especial';
    const PRIMARYKEY = 'id';
    const IDPOLICY =  'serial';


    private $user;
    private $periodo;
    private $unidade;
    private $matriculauser;
    private $cursoatual;

    public function get_unidade()
    {
        $repository = new TRepository('SystemUserUnit');
        $criteria = new TCriteria();
        //$criteria->add(new TFilter('system_user_id', '=', $this->system_user_id));
        $criteria->add(new TFilter('system_user_id', '=', TSession::getValue('userid')));
        $criteria->add(new TFilter('system_unit_id', '=', TSession::getValue('userunitid')));
        $objetos = $repository->load($criteria); 
        foreach ($objetos as $objeto)
        {
        return $this->unidade = $objeto->system_unit_id;
        }
    }

    public function get_cursoatual()
    {
        if (empty($this->cursoatual)) {
            $this->cursoatual = new SystemUser($this->system_user_id);
        }
        return $this->cursoatual->curso;
   
    }

    public function get_matriculauser()
    {
        if (empty($this->matriculauser)) {
            $this->matriculauser = new SystemUser($this->system_user_id);
        }
        return $this->matriculauser->login;
   
    }

    public function get_user()
    {
        if (empty($this->user)) {
            $this->user = new SystemUser($this->system_user_id);
        }
        return $this->user->name;
   
    }
    public function get_periodo()
    {
        if (empty($this->periodo)) {
            $this->periodo = new SystemUser($this->system_user_id);
        }
        return $this->periodo->periodo;
   
    }
    public static function getStatsByDay()
    {
        TTransaction::open('database');
        
        $logs = self::where('datapresenca', '>=', date('Y-m-01'))->where('datapresenca', '<=', date('Y-m-t'))->load();
        
        $accesses = array();

        if (count($logs)>0)
        {
            $accesses = array();
            foreach ($logs as $log)
            {
                $day = substr($log->datapresenca,8,2);
               
                if (isset($accesses[$day]))
                {
                    $accesses[$day] ++;
                }
                else
                {
                    $accesses[$day] = 1;
                }
            }
        }

        TTransaction::close();
        return $accesses;
    }
}

?>