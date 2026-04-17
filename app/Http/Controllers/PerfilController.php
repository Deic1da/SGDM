<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function atualizarFoto(Request $request)
    {
        $request->validate([
            'foto_perfil' => ['required', 'image', 'max:5120'],
        ]);

        $user = $request->user();

        if (!empty($user->foto_perfil) && !str_starts_with((string) $user->foto_perfil, 'http')) {
            if (Storage::disk('public')->exists($user->foto_perfil)) {
                Storage::disk('public')->delete($user->foto_perfil);
            }
        }

        $caminho = $request->file('foto_perfil')->store('fotos-perfil', 'public');

        $user->update([
            'foto_perfil' => $caminho,
        ]);

        return redirect()
            ->route('pagina-inicial')
            ->with('success', 'Foto de perfil atualizada com sucesso.');
    }
}
