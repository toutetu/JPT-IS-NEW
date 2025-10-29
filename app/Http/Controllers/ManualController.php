<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManualController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            abort(403);
        }

        $role = $user->role;
        $view = match ($role) {
            'student' => 'manual.student',
            'teacher' => 'manual.teacher',
            'admin'   => 'manual.admin',
            default   => 'manual.common',
        };

        if (!view()->exists($view)) {
            $view = 'manual.common';
        }

        return view($view, [
            'user' => $user,
        ]);
    }
}


