<?php
require_once 'init.php';
$theme  = $ini['general']['theme'];
$class  = isset($_REQUEST['class']) ? $_REQUEST['class'] : '';
$public = in_array($class, $ini['permission']['public_classes']);
new TSession;

//var_dump(TSession::getValue('multiunit'));
   // exit;

if ( TSession::getValue('logged') )
{
    $userunit = TSession::getValue('userunitid');
    $login = TSession::getValue('login');
    if( $userunit <= 0){
        $content = file_get_contents("app/templates/{$theme}/layout_especial.html");
        
    }else{
      
        $content = file_get_contents("app/templates/{$theme}/layout.html"); 
    }
    $menu_string = AdiantiMenuBuilder::parse('menu.xml', $theme);
    $content     = str_replace('{MENU}', $menu_string, $content);
}
else
{
    $content = file_get_contents("app/templates/{$theme}/login.html");
}

$content = ApplicationTranslator::translateTemplate($content);
$content = AdiantiTemplateParser::parse($content);

echo $content;

if (TSession::getValue('logged') OR $public)
{
    if ($class)
    {
        $method = isset($_REQUEST['method']) ? $_REQUEST['method'] : NULL;
        AdiantiCoreApplication::loadPage($class, $method, $_REQUEST);
    }
}
else
{
    AdiantiCoreApplication::loadPage('LoginForm', '', $_REQUEST);
    
}