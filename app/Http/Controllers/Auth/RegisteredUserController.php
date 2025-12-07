<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view( 'auth.register' );
    }

    public function store( Request $request ): RedirectResponse
    {
        $request->validate( [
                                'first_name' => [ 'required', 'string', 'max:255' ],
                                'last_name'  => [ 'required', 'string', 'max:255' ],
                                'email'      => [ 'required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class ],
                                'product_id' => [ 'nullable', 'integer', 'exists:products,id' ],
                                'password'   => [ 'required', 'confirmed', Rules\Password::defaults() ],
                            ] );

        $product = Product::find( $request->product_id );

        $user = User::create( [
                                  'first_name'     => $request->first_name,
                                  'last_name'      => $request->last_name,
                                  'plan_name'      => $product->name ?? null,
                                  'renewal_cost'   => $product->price ?? 0,
                                  'renewal_period' => $product->period,
                                  'expires_at'     => now()->addDays( 5 ), // 5 Day Trial
                                  'email'          => $request->email,
                                  'password'       => Hash::make( $request->password ),
                              ] );

        event( new Registered( $user ) );

        Auth::login( $user );

        return redirect()->intended( route( 'viewers.home', absolute: false ) );
    }
}
