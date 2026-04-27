<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class PanelController extends Controller
{
    public function carnets()
    {
        return redirect()->route('filament.admin.pages.gestion-carnets');
    }

    public function emitirCarnet(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->update([
            'carnet_activo' => true,
            'carnet_emitido_at' => now(),
            'carnet_vencimiento' => $user->carnet_vencimiento ?? now()->endOfYear(),
        ]);

        return back()->with('success', 'Carnet emitido correctamente.');
    }

    public function revocarCarnet(int $id): RedirectResponse
    {
        User::findOrFail($id)->update(['carnet_activo' => false]);

        return back()->with('success', 'Carnet revocado correctamente.');
    }
}

