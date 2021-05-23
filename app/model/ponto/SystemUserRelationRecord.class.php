<?php

class SystemUserRelationRecord extends TRecord
{

    const TABLENAME = 'system_user_relation';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'serial'; // {max, serial}

    private $empresa;

    function get_nome_empresa()
    {

        if (empty ($this->empresa)) {
            $this->empresa = new EmpresaRecord($this->empresa_id);
        }

        return $this->empresa->sigla;

    }

    public static function getName($id)
    {

        try {

            TTransaction::open('database');

            $userRelation = new SystemUserRelationRecord($id);

            if ($userRelation) {
                switch ($userRelation->tipo_vinculo) {
                    CASE 'SERVIDOR':
                        return (new ServidorRecord($userRelation->vinculo_id))->nome;
                }
            } else {
                return null;
            }

            TTransaction::close();

        } catch (Exception $e) {

            TTransaction::rollback();

            new TMessage("error in Record SystemGroup ", $e->getMessage() . '.');

            return null;

        }

    }

    public static function getServidorLogado()
    {

        try {

            $session_id = TSession::getValue('vinculo_id');

            if(empty($session_id))
                throw new Exception('Usuário sem vínculo cadastrado.');

            TTransaction::open('database');

            $servidor = new ServidorRecord($session_id);

            TTransaction::close();

            return $servidor;

        } catch (Exception $e) {

            TTransaction::rollback();

            new TMessage('error', $e->getMessage());

            return null;

        }

    }

}
