<?php

class ErrorController extends Zend_Controller_Action
{

    public function indexAction()
    {
        $m = 'Ошибка. Обратитесь к Cоболеву Сергею.';
        switch ($this->_getParam('what'))
        {
            case 'tplnotfound':
                $m = 'Такого шаблона не существует';
        	break;
            case 'usergroupnotfound':
                $m = 'Такой группы пользователей не существует';
        	break;
        }
        $this->view->message = $m;
    }
    
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        if (!$errors || !$errors instanceof ArrayObject)
        {
            $this->view->message = 'You have reached the error page';
            return;
        }

        /*
        var_dump(get_class($errors->exception));
        var_dump($errors->exception);
        var_dump($errors->request);
        var_dump($errors->type);
        echo $errors->exception->xdebug_message;
        */
        //echo $errors->exception->getMessage();

        switch ($errors->type)
        {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Page not found';
                break;

            case 'EXCEPTION_OTHER':

                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;

                $this->view->message = 'Ошибка апликации';
                $this->view->exception = $errors->exception;

            break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                $this->view->message = 'Application error';
                
            break;
        }
        
        $this->view->request   = $errors->request;
    }
}

