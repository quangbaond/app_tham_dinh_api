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
            'user_id.required' => 'User ID không được để trống',
            'user_id.numeric' => 'User ID phải là số',
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

    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'address' => 'required|string',
            'birthday' => 'required|string',
            'name' => 'required|string',
            'sex' => 'required|string',
            'nationality' => 'required|string',
            'religion' => 'required|string',
            'doe' => 'required|string',
            'issue_date' => 'required|string',
            'phone_number_reference' => 'required|array',
        ], [
            'address.required' => 'Địa chỉ không được để trống',
            'address.string' => 'Địa chỉ phải là chuỗi',
            'birthday.required' => 'Ngày sinh không được để trống',
            'birthday.string' => 'Ngày sinh phải là chuỗi',
            'name.required' => 'Họ tên không được để trống',
            'name.string' => 'Họ tên phải là chuỗi',
            'sex.required' => 'Giới tính không được để trống',
            'sex.string' => 'Giới tính phải là chuỗi',
            'nationality.required' => 'Quốc tịch không được để trống',
            'nationality.string' => 'Quốc tịch phải là chuỗi',
            'religion.required' => 'Tôn giáo không được để trống',
            'religion.string' => 'Tôn giáo phải là chuỗi',
            'doe.required' => 'Ngày cấp không được để trống',
            'doe.string' => 'Ngày cấp phải là chuỗi',
            'issue_date.required' => 'Ngày hết hạn không được để trống',
            'issue_date.string' => 'Ngày hết hạn phải là chuỗi',
            'phone_number_reference.required' => 'Danh sách số điện thoại không được để trống',
            'phone_number_reference.array' => 'Danh sách số điện thoại phải là mảng',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($request->user_id);
        $data = $request->except('phone_number_reference');
        $user->userIdentifications()->update($data);

        if($request->has('phone_number_reference')) {
            $user->userPhoneReferences()->delete();
            foreach ($request->phone_number_reference as $phone) {
                $user->userPhoneReferences()->create([
                    'phone' => $phone['phone'],
                    'relationship' => $phone['relationship'],
                    'name' => $phone['name'],
                ]);
            }
        }
        
        return response()->json([
            'message' => 'Cập nhật thành công',
            'user' => $user,
        ], 201);

    }
}
