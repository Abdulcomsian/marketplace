<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

/**
 * Reset Supplier Password controlller for the customer.
 *
 * @copyright 2020 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class ResetPasswordController extends Controller
{

    use ResetsPasswords;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_config = request('_config');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, $token = null)
    {
        return view($this->_config['view'])->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        try {
            $this->validate(request(), [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:6',
            ]);

            $response = $this->broker()->reset(
                request(['email', 'password', 'password_confirmation', 'token']), function ($supplier, $password) {
                    $this->resetPassword($supplier, $password);
                }
            );

            if ($response == Password::PASSWORD_RESET) {
                return redirect()->route($this->_config['redirect']);
            }

            session()->flash('success', trans('b2b_marketplace::app.shop.account.password.update-success'));

            return back()
                ->withInput(request(['email']))
                ->withErrors([
                    'email' => trans($response)
                ]);

        } catch(\Exception $e) {
            session()->flash('error', trans($e->getMessage()));

            return redirect()->back();
        }
    }

    /**
     * Reset the given customer password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $customer
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($supplier, $password)
    {
        $supplier->password = Hash::make($password);

        $supplier->setRememberToken(Str::random(60));

        $supplier->save();

        event(new PasswordReset($supplier));

        auth()->guard('supplier')->login($supplier);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('suppliers');
    }

}