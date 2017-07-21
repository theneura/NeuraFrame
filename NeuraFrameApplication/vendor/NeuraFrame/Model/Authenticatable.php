<?php

namespace NeuraFrame\Model;

use NeuraFrame\Model;
use NeuraFrame\Session;
use NeuraFrame\Application;
use NeuraFrame\Exceptions\MethodIsNotCallableException;
use NeuraFrame\Contracts\Application\ApplicationInterface;


class Authenticatable extends Model
{
    /**
    * Table row name for attempting login (username)
    *
    * @var string
    */
    protected $authUsername = 'email';

    /**
    * Table row name for attempting login (password)
    *
    * @var string
    */
    protected $authPassword = 'password';

    /**
    * Session name for storing user id for authentication
    *
    * @var string
    */
    protected $authSession = 'user_id';

    /**
    * Session name for storing user remember me for authentication
    *
    * @var string
    */
    protected $authRemember = 'user_remember';

    /**
    * Returns Session name for storing id session
    *
    * @return string
    */
    public function getAuthSession()
    {
        return $this->authSession;
    }

    /**
    * Returns Session name for storing remember me session
    *
    * @return string
    */
    public function getRememberMe()
    {
        return $this->authRememeber;
    }

    /**
    * Returns Table row name for attempting login (username)
    *
    * @return string
    */
    public function getAuthUsername()
    {
        return $this->authUsername;
    }

    /**
    * Returns Table row name for attempting login (password)
    *
    * @return string
    */
    public function getAuthPassword()
    {
        return $this->authPassword;
    }

    /**
    * Static method for attempting to log model
    *
    * @param string $username 
    * @param string $password 
    * @return bool
    */
    public static function login($username,$password)
    {
        $app = Application::getInstance();
        $model = $app->modelFactory->model(get_called_class());
        $realModel = $model->where($model->getAuthUsername().' = "'.$username.'"')->first();
        if(isset($realModel) && $app->password->verify($password,$realModel->password))
        {
            Session::set($model->getAuthSession(),$realModel->id);
            return true;
        }
        return false;
    }

    /**
    * Static method for logout model
    *
    */
    public static function logout()
    {
        $app = Application::getInstance();
        $authModel = self::auth();
        if(!is_null($authModel))
            Session::remove($authModel->getAuthSession());
    }

    /**
    * Static method for returning auth Model
    *
    * @throws \NeuraFrame\Exceptions\MethodIsNotCallableException
    * @return \NeuraFrame\Model
    */
    public static function auth()
    {
        $app = Application::getInstance();
        $model = $app->modelFactory->model(get_called_class());
        if(!Session::has($model->getAuthSession()))
            return null;
            //TODO: Returning null is not valid, its violate rules of SOLID principles
        $callback = get_called_class().'::find';
        if(!is_callable($callback))
            throw new MethodIsNotCallableException([$callback]);
        return call_user_func($callback,Session::get($model->getAuthSession()));
        
    }
}