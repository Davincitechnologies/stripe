<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function create(Request $request, Plan $plan)
    {
        $plan = Plan::findOrFail($request->get('plan'));

        $user = $request->user();
        $paymentMethod = $request->paymentMethod;

        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($paymentMethod);

        $request->user()
            ->newSubscription('default', $plan->id)
            ->create($paymentMethod, [
                    'email' => $user->email,
        ]);

        return redirect()->route('home')->with('success', 'Your plan subscribed successfully');
    }
}
