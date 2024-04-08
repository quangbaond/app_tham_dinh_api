<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;

class UserController extends Controller
{
    public function uploadCccd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'mattruoc' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'matsau' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'mattruoc.required' => 'Mặt trước không được để trống',
            'mattruoc.image' => 'Mặt trước phải là ảnh',
            'mattruoc.mimes' => 'Mặt trước phải là định dạng jpeg, png, jpg, gif, svg',
            'mattruoc.max' => 'Mặt trước không được vượt quá 2MB',
            'matsau.required' => 'Mặt sau không được để trống',
            'matsau.image' => 'Mặt sau phải là ảnh',
            'matsau.mimes' => 'Mặt sau phải là định dạng jpeg, png, jpg, gif, svg',
            'matsau.max' => 'Mặt sau không được vượt quá 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        
        if($request->hasFile('mattruoc') && $request->hasFile('matsau')) {
            $imageNameFront = 'mat-truoc-'. time() . '.' . $request->mattruoc->extension();
            $imageNameBack = 'mat-sau-'. time() . '.' . $request->matsau->extension();

            // $request->mattruoc->move(public_path('images'), $imageNameFront);
            // $request->matsau->move(public_path('images'), $imageNameBack);

            //store image to storage
            $request->mattruoc->storeAs('public/images', $imageNameFront);
            $request->matsau->storeAs('public/images', $imageNameBack);

            $user = User::find($request->user_id);
            $user->userIdentifications()->create([
                'image_front' => asset('storage/images/' . $imageNameFront),
                'image_back' => asset('storage/images/' . $imageNameBack),
            ]);

            return response()->json([
                'message' => 'Cập nhật thành công',
                'user' => $user,
            ], 201);
        } else {
            return response()->json([
                'message' => 'Tải lên thất bại',
            ], 400);
        }
        
    }
}
