<?php

use Webkul\Customer\Models\Customer;
use Webkul\User\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Encryption\DecryptException;
// use PDF;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// use PDF;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/test', function (Request $request) {
    $customer = auth()->guard('customer')->user();
    // if(!$customer){
    //         $url = 'https://www.formitydev.com/customer/login';
    // }
    if($customer != null && $customer->customer_group_id == 4){
    $email = 'user@domain.com';
    $url='https://formity.accrualdev.com/formity-frontend/login?email='.$email;
    return redirect($url);
    }
    $url = 'https://www.formitydev.com/bagisto/public/customer/login';
    return redirect($url);

    // $email = 'user@example.com';
    $password = '12345678';
        // Attempt to authenticate the user
    if (Auth::attempt(['email' => $email, 'password' => $password])) {
        // Authentication was successful
        return redirect()->intended('/admin/dashboard');
    } else {
        // Authentication failed
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

})->name('test');

Route::get('/generate-pdf', function (Request $request) {
    // share data to view
//    $customer = auth('customer')->user();
//    dd($customer);
    $employee = array(
        'name' => 'John',
        'age' => 30,
        'gender' => 'Male',
        'email' => 'john@example.com',
        'phone' => '+1-555-555-1234'
    );
    
    view()->share('employee',$employee);
    $pdf = PDF::loadView('pdf', $employee);
    // download PDF file with download method
    return response($pdf->output(), 200)
    ->header('Content-Type', 'application/pdf')
    ->header('Content-Disposition', 'attachment; filename="pdf_file.pdf"');
})->name('generate-pdf');


