<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();

        $quotes = Quote::query()
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return Inertia::render('Customer/Dashboard', [
            'user' => $user,
            'quotes' => $quotes,
        ]);
    }
}
