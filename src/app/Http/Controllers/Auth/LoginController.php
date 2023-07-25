<?php

namespace App\Http\Controllers\Auth;

use App\Events\LoggedUserSignInHistoriesEvent;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Utils\System\GetSetCookie;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, ListensForLdapBindFailure;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->listenForLdapBindFailure();
    }
    public function email()
    {
        return 'email';
    }
    protected function credentials(Request $request)
    {
        return [
            'mail' => $request->email,
            'password' => $request->password,
            'fallback' => [
                'email' => $request->email,
                'password' => $request->password,
            ],
        ];
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }
            if (Auth::user()) {
                PostLoggedInActions::setTimeZoneWhenLogged($request);
                PostLoggedInActions::setTokenWhenLoggedForCookie();
                $this->loggedInfoUserSignIn($request);
            }
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        if (GetSetCookie::hasCookie('time_zone')) {
            GetSetCookie::forgetCookie('time_zone');
        }
        if (GetSetCookie::hasCookie('tlc_token')) {
            GetSetCookie::forgetCookie('tlc_token');
        }
        try {
            $this->guard()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            if ($response = $this->loggedOut($request)) {
                return $response;
            }
            return redirect('/');
        } catch (\Throwable $th) {
            dump("Logout Exception: " . $th->getMessage());
            return redirect('/');
        }

        // return $request->wantsJson()
        //     ? new JsonResponse([], 204)
        //     : redirect('/');
    }
    private function loggedInfoUserSignIn($request)
    {
        $agent = new Agent();
        $headers = $request->header('User-Agent');
        $agent->setUserAgent($headers);
        try {
            $ipAddress = $request->getClientIp(true);
            $location = Location::get($ipAddress);
            $locationName = '';
            if($location) $locationName = $location->countryName;
            $infoBrowser = [
                'location' => $locationName,
                'browser' => $agent->browser(),
                'browser_version' => $agent->version($agent->browser()),
                'platform' => $agent->platform(),
                'device' => $agent->device(),
            ];
            dd($infoBrowser);
        } catch (\Throwable $th) {
           dd($th->getMessage());
        }
       
        $time = $request->server('REQUEST_TIME');
        event(new LoggedUserSignInHistoriesEvent(Auth::id(), $ipAddress, $time, $infoBrowser));
    }
}
