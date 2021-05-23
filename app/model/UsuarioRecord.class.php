<?php
/*
 * classe UsuarioRecord
 * Active Record para tabela Usuario
 */
class UsuarioRecord extends TRecord
{
    
    const TABLENAME = 'system_user';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'max'; // {max, serial}
    
    
    }

?>