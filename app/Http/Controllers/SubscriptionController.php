<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SubscriptionController extends Controller
{
    public function create()
    {
        $intent = Auth::user()->createSetupIntent();

        return view('subscription.create', compact('intent'));
    }

    public function store(User $user)
    {
        Route::post('/user/subscribe', function (Request $request) {
            $request->user()->newSubscription(
                'premium_plan', 'price_1PBCDL2MIT849GWt3ambAejQ'
            )->create($request->paymentMethodId);
        });

        return redirect()->route('user.index', compact('user'))->with('flash_message', '有料プランへの登録が完了しました。');
    }

    public function edit()
    {
        return view('subscription.edit', compact('user,intent'));
    }

    public function update(Request $request, User $user)
    {
        $user->updateDefaultPaymentMethod($request->paymentMethodId);

        return redirect()->route('user.index', compact('user'))->with('flash_message', 'お支払い方法を変更しました。');
    }

    public function cancel()
    {
        return view('subscription.cancel');
    }

    public function destroy(User $user)
    {
        $user->subscription('premium_plan')->cancelNow();

        $user->delete();

        return redirect()->route('user.index', compact('user'))->with('flash_message', '有料プランを解約しました。');
    }
}
