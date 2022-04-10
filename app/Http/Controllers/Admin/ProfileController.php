<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CategoryType;
use App\Enums\MediaType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\AdminGeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use AdminGeneralTrait;

    public function edit() {
        $account = User::whereId(Auth::id())->first();
        return view('admin.profile.edit', compact('account'));
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $this->validate($request, [
            'username' => 'required|unique:users,username,'.$id,
            'full_name' => 'required',
            'phone_number' => 'required|unique:users,phone_number,'.$id,
            'email' => 'required|unique:users,email,'.$id,
            'whatsapp' => 'required|unique:users,whatsapp,'.$id,
        ]);
        $media = $user->media;
        if ($request->hasFile('image')) {
            $this->validate($request,[
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4048'
            ]);
            $media = $this->uploadMedia($request->image, MediaType::IMAGE, CategoryType::PROFILE, null);
        }
        if (!empty($request->password)) {
            $this->validate($request, [
                'password' => 'required|string|confirmed',
                'password_confirmation' => 'required'
            ]);
            $user->password = Hash::make($request->password);
        }
        $user->media_id = $media ? $media->id : null;
        $user->username = $request->username;
        $user->full_name = $request->full_name;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->whatsapp = $request->whatsapp;
        $user->save();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        return redirect(route('admin.profile.edit'));
    }
}
