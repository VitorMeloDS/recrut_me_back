<?php

namespace App\Http\Controllers;

use App\Mail\InviteMail;
use App\Models\Employee;
use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class InviteController extends Controller
{
    public function index() {
        $invites = Invite::all();

        foreach ($invites as $invite) {
            if (Employee::where('email', $invite->email)->exists()) {
                $invite->update(['status' => 'Finalizado']);
            }
            elseif ($invite->isExpired() && $invite->status !== 'Finalizado') {
                $invite->update(['status' => 'Vencido']);
            }
        }

        return response()->json($invites);
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $token = Str::random(40);
        $expiresAt = Carbon::now()->addHours(24);

        $existInvite = Invite::where('email', $request->email)->where('status', '<>', "%Finalizado%")->exists();

        if ($existInvite) {
            Invite::where('email', $request->email)->update([
                'token' => $token,
                'status' => 'Em Aberto',
                'expires_at' => $expiresAt,
                'updated_at' => now(),
            ]);
        } else {
            Invite::create([
                'email' => $request->email,
                'token' => $token,
                'status' => 'Em Aberto',
                'expires_at' => $expiresAt,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        $link = env('APP_URL') . "/colaborador/criar?token=$token";

        Mail::to($request->email)->send(new InviteMail($request->email, $link));

        return response()->json(['message' => 'Convite enviado com sucesso!']);
    }

    public function verifyToken(Request $request)
    {
        $token = $request->query('token');

        $invite = DB::table('invite')->where('token', $token)->first();

        if (!$invite) {
            return response()->json(['error' => 'Token inválido'], 400);
        }

        if (Carbon::now()->greaterThan($invite->expires_at)) {
            return response()->json(['error' => 'Token expirado', 'valid' => false, ], 400);
        }

        return response()->json(['message' => 'Token válido', 'valid' => true, 'email' => $invite->email]);
    }
}