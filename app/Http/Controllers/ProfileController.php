<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function edit(Request $request)
    {
        return view('profiles.edit')->with(['user' => $request->user()]);
    }

    public function update(ProfileRequest $request)
    {
        // dump($request);
        // dump($request->user());
        // dd(array_filter($request->validated()));
        return DB::transaction(function () use ($request){

            // $file = $request->file('photo');
            // $file = $request->photo;

            $user = $request->user();

            $user->fill(array_filter($request->validated()));

            if($user->isDirty('email')){
                $user->email_verified_at = null;
                // $user->sendEmailVerificationNotification();
            }
            
            $user->save();

            if($request->hasFile('image')){
                if($user->image != null){
                    Storage::disk('images')->delete($user->image->path);
                    $user->image->delete();
                }

                $file = $request->image;
                $file_name = $file->getClientOriginalName();
                $file_ext = $file->getClientOriginalExtension();
                $fileInfo = pathinfo($file_name);
                $newname = $fileInfo['filename']  . "." . $file_ext;
                $destinationPath = public_path('images/users');
                $file->move($destinationPath, $newname);
                $url = 'users/' . $fileInfo['filename'] . "." . $file_ext;

                $user->image()->create([
                    'path' => $url
                    // 'path'=> $request->image->pathname
                ]);
            }

            return redirect()
                ->route('profile.edit')
                ->withSuccess('Profile edited');
        }, 5);
    }
}
