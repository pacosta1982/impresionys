<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Adldap\Laravel\Facades\Adldap;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
        //var_dump('hola');
    }

    public function username()
    {
        return config('ldap_auth.usernames.eloquent');
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string|regex:/^\w+$/',
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $username = $credentials[$this->username()];
        $password = $credentials['password'];

        $user_format = env('LDAP_USER_FORMAT', 'cn=%s,'.env('LDAP_BASE_DN', ''));
        $userdn = sprintf($user_format, $username);

        // you might need this, as reported in
        // [#14](https://github.com/jotaelesalinas/laravel-simple-ldap-auth/issues/14):
        // Adldap::auth()->bind($userdn, $password);

        if(Adldap::auth()->attempt($username.'@senavitat', $password, $bindAsUser = true)) {


            $user = \App\User::where($this->username(), $username)->first();
            if (!$user) {

                $user = new \App\User();
                $user->username = $username;
                $user->password = '';

                $sync_attrs = $this->retrieveSyncAttributes($username);
                foreach ($sync_attrs as $field => $value) {
                    $user->$field = $value !== null ? $value : '';
                }
            }

            $this->guard()->login($user, true);
            return true;
        }

        // the user doesn't exist in the LDAP server or the password is wrong
        // log error
        return false;
    }

    protected function retrieveSyncAttributes($username)
    {
        $ldapuser = Adldap::search()->where(env('LDAP_USER_ATTRIBUTE'), '=', $username)->first();
        if ( !$ldapuser ) {
            // log error
            return false;
        }
        // if you want to see the list of available attributes in your specific LDAP server:
        // var_dump($ldapuser->attributes); exit;

        // needed if any attribute is not directly accessible via a method call.
        // attributes in \Adldap\Models\User are protected, so we will need
        // to retrieve them using reflection.
        $ldapuser_attrs = null;

        $attrs = [];

        foreach (config('ldap_auth.sync_attributes') as $local_attr => $ldap_attr) {
            if ( $local_attr == 'username' ) {
                continue;
            }

            $method = 'get' . $ldap_attr;
            if (method_exists($ldapuser, $method)) {
                $attrs[$local_attr] = $ldapuser->$method();
                continue;
            }

            if ($ldapuser_attrs === null) {
                $ldapuser_attrs = self::accessProtected($ldapuser, 'attributes');
            }

            if (!isset($ldapuser_attrs[$ldap_attr])) {
                // an exception could be thrown
                $attrs[$local_attr] = null;
                continue;
            }

            if (!is_array($ldapuser_attrs[$ldap_attr])) {
                $attrs[$local_attr] = $ldapuser_attrs[$ldap_attr];
            }

            if (count($ldapuser_attrs[$ldap_attr]) == 0) {
                // an exception could be thrown
                $attrs[$local_attr] = null;
                continue;
            }

            // now it returns the first item, but it could return
            // a comma-separated string or any other thing that suits you better
            $attrs[$local_attr] = $ldapuser_attrs[$ldap_attr][0];
            //$attrs[$local_attr] = implode(',', $ldapuser_attrs[$ldap_attr]);
        }

        return $attrs;
    }

    protected static function accessProtected ($obj, $prop)
    {
        $reflection = new \ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }

}
