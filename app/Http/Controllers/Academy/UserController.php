<?php

namespace App\Http\Controllers\Academy;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Academy\UserRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Image;

class UserController extends Controller
{
    public function edit(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        CheckPermission::checkManyAuth(['Acessar Academia', 'Editar Usuários']);

        $user = Auth::user();

        return view('academy.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request): RedirectResponse
    {
        CheckPermission::checkAuth('Editar Usuário');

        $data = $request->all();

        $user = Auth::user();

        if ($request->google2fa_secret_enabled && $user->google2fa_secret == null) {
            $data['google2fa_secret'] = $user->generateSecretKey();
        }

        if (! $request->google2fa_secret_enabled) {
            $data['google2fa_secret'] = null;
        }

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($request->password);
        } else {
            $data['password'] = $user->password;
        }

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $name = Str::slug(mb_substr($data['name'], 0, 200)).'-'.time();
            $imagePath = storage_path().'/app/public/users/'.$user->photo;

            if (File::isFile($imagePath)) {
                unlink($imagePath);
            }

            $data = $this->saveImage($request, $name, $data);
        }

        if ($user->update($data)) {
            return redirect()
                ->route('academy.user.edit')
                ->with('success', 'Atualização realizada!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar!');
        }
    }

    private function saveImage(UserRequest $request, string $name, array $data): array
    {
        $extension = $request->photo->extension();
        $nameFile = "$name.$extension";

        $data['photo'] = $nameFile;

        $destinationPath = storage_path().'/app/public/users';

        if (! file_exists($destinationPath)) {
            mkdir($destinationPath, 755, true);
        }

        $img = Image::make($request->photo);
        $img->resize(null, 100, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->crop(100, 100)->save($destinationPath.'/'.$nameFile);

        return $data;
    }
}
