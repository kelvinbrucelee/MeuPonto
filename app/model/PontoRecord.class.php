<?php
/*
 * classe UsuarioRecord
 * Active Record para tabela Usuario
 */
class PontoRecord extends TRecord
{
    
    const TABLENAME = 'ponto';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'serial'; // {max, serial}


    private $login;
    private $unidade;
    private $matriculauser;
    private $cursoatual;


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
    public function get_login()
    {
        // loads the associated object
        if (empty($this->login))
            $this->login = new SystemUser($this->system_user_id);
    
        // returns the associated object
        return $this->login->login;
    }

    public function get_unidade()
    {
        // loads the associated object
        if (empty($this->unidade))
            $this->unidade = new SystemUserUnit($this->system_user_unit_id);
    
        // returns the associated object
        return $this->unidade->system_unit_id;
    }
    

public static function getStatsByDay()
    {
        TTransaction::open('database');
        
        $logs = self::where('dataponto', '>=', date('Y-m-01'))->where('dataponto', '<=', date('Y-m-t'))->load();
        
        $accesses = array();

        if (count($logs)>0)
        {
            $accesses = array();
            foreach ($logs as $log)
            {
                $day = substr($log->dataponto,8,2);
               
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