<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function uploadCccd(Request $request)
    {
        $this->middleware('auth:api');
        $validator = Validator::make($request->all(), [
            'image_front' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
            'image_back' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
        ], [
            'image_front.image' => 'Ảnh phải là định dạng ảnh',
            'image_front.mimes' => 'Ảnh phải là định dạng jpeg, png, jpg, gif, svg',
            'image_front.max' => 'Ảnh không được vượt quá 2MB',
            'image_back.image' => 'Ảnh phải là định dạng ảnh',
            'image_back.mimes' => 'Ảnh phải là định dạng jpeg, png, jpg, gif, svg',
            'image_back.max' => 'Ảnh không được vượt quá 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->hasFile('image_front')) {

            $imageNameFront = time() . '.' . $request->image_front->extension();

            // save image to storage
            $request->image_front->storeAs('public/images', $imageNameFront);

            $response = $this->uploadAPI(storage_path('app/public/images/' . $imageNameFront), env('API_URL_CCCD'));

            if ($response['errorCode'] === 0) {
                $data =  $response['data'][0];

                if ($data['type'] === 'new' || $data['type'] === 'old') {

                    $user = User::find($request->user()->id);

                    $user->userIdentifications()->updateOrCreate(
                        ['user_id' => $user->id],
                        // thay the id bang $data = id_card
                        array_merge($data, [
                            'id_card' => $data['id'],
                            'birrthday' => $data['dob'],
                            'image_front' => asset('storage/images/' . $imageNameFront),
                        ])
                    );
                } else {
                    return response()->json([
                        'message' => 'Vui lòng tải lên ảnh mặt trước',
                    ], 400);
                }

                return response()->json([
                    'message' => 'Tải lên thành công',
                    'user' => $user,
                    'data' => $data
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Tải lên thất bại',
                ], 400);
            }
        } elseif ($request->hasFile('image_back')) {

            $imageNameBack = time() . '.' . $request->image_back->extension();

            // save image to storage
            $request->image_back->storeAs('public/images', $imageNameBack);

            $response = $this->uploadAPI(storage_path('app/public/images/' . $imageNameBack), env('API_URL_CCCD'));

            if ($response['errorCode'] === 0) {
                $data =  $response['data'][0];

                if ($data['type'] === 'old_back' || $data['type'] === 'new_back') {

                    $user = User::find($request->user()->id);

                    $user->userIdentifications()->updateOrCreate(
                        ['user_id' => $user->id],
                        // thay the id bang $data = id_card
                        array_merge($data, [
                            'issue_date' => $data['doe'],
                            'image_back' => asset('storage/images/' . $imageNameBack),
                        ])
                    );
                } else {
                    return response()->json([
                        'message' => 'Vui lòng tải lên ảnh mặt sau',
                    ], 400);
                }

                return response()->json([
                    'message' => 'Tải lên thành công',
                    'user' => $user,
                    'data' => $data
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Tải lên thất bại',
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Ảnh không được để trống',
            ], 400);
        }
    }

    public function uploadBLX(Request $request)
    {
        $this->middleware('auth:api');
        $validator = Validator::make($request->all(), [
            'image_front' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
            'image_back' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
        ], [
            'image.image' => 'Ảnh phải là định dạng ảnh',
            'image.mimes' => 'Ảnh phải là định dạng jpeg, png, jpg, gif, svg',
            'image.max' => 'Ảnh không được vượt quá 2MB',
            'image_back.image' => 'Ảnh phải là định dạng ảnh',
            'image_back.mimes' => 'Ảnh phải là định dạng jpeg, png, jpg, gif, svg',
            'image_back.max' => 'Ảnh không được vượt quá 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->hasFile('image_front')) {
            $imageNameFront = time() . '.' . $request->image_front->extension();

            // save image to storage
            $request->image_front->storeAs('public/images', $imageNameFront);

            $response = $this->uploadAPI(storage_path('app/public/images/' . $imageNameFront), env('API_URL_BLX'));

            if ($response['errorCode'] === 0) {
                $data = $response['data'][0];
                if ($data['type'] == 'New-front' || $data['type'] == 'Old-front') {
                    $user = User::find($request->user()->id);

                    $user->userLicenses()->updateOrCreate(
                        ['user_id' => $user->id],
                        array_merge($data, [
                            'id_card' => $data['id'],
                            'image_front' => asset('storage/images/' . $imageNameFront),
                        ])
                    );

                    return response()->json([
                        'message' => 'Tải lên thành công',
                        'user' => $user,
                        'data' => $data
                    ], 201);
                } else {
                    return response()->json([
                        'message' => 'Vui lòng tải lên ảnh mặt trước',
                    ], 400);
                }
            } else {
                return response()->json([
                    'message' => 'Tải lên thất bại',
                ], 400);
            }
        } elseif ($request->hasFile('image_back')) {
            $imageNameBack = time() . '.' . $request->image_back->extension();

            // save image to storage
            $request->image_back->storeAs('public/images', $imageNameBack);

            $response = $this->uploadAPI(storage_path('app/public/images/' . $imageNameBack), env('API_URL_BLX'));

            if ($response['errorCode'] === 0) {
                $data = $response['data'][0];

                if ($data['type'] === 'New-back' || $data['type'] === 'Old-back') {
                    $user = User::find($request->user()->id);

                    $user->userLicenses()->updateOrCreate(
                        ['user_id' => $user->id],
                        array_merge($data, [
                            'image_back' => asset('storage/images/' . $imageNameBack),
                            'type' => $data['type'],
                        ])
                    );
                } else {
                    return response()->json([
                        'message' => 'Vui lòng tải lên ảnh mặt sau',
                    ], 400);
                }
            } else {
                return response()->json([
                    'message' => 'Tải lên thất bại',
                ], 400);
            }
        }
    }

    public function updateUser(Request $request)
    {
        $this->middleware('auth:api');
        $validator = Validator::make($request->all(), [
            'address' => 'required|string',
            'birthday' => 'required|string',
            'name' => 'required|string',
            'address_now' => 'required|string',
            'issue_date' => 'required|string',
            'msbhxh' => 'required|string',
            'facebook' => 'string|nullable',
            'zalo' => 'string|nullable',
            'phone_reference' => 'required|array',
        ], [
            'address.required' => 'Địa chỉ không được để trống',
            'address.string' => 'Địa chỉ phải là chuỗi',
            'birthday.required' => 'Ngày sinh không được để trống',
            'birthday.string' => 'Ngày sinh phải là chuỗi',
            'name.required' => 'Họ tên không được để trống',
            'name.string' => 'Họ tên phải là chuỗi',
            'issue_date.required' => 'Ngày hết hạn không được để trống',
            'issue_date.string' => 'Ngày hết hạn phải là chuỗi',
            'phone_reference.required' => 'Danh sách số điện thoại không được để trống',
            'phone_reference.array' => 'Danh sách số điện thoại phải là mảng',
            'msbhxh.required' => 'Mã số bảo hiểm xã hội không được để trống',
            'msbhxh.string' => 'Mã số bảo hiểm xã hội phải là chuỗi',
            'facebook.string' => 'Facebook phải là chuỗi',
            'zalo.string' => 'Zalo phải là chuỗi',

        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($request->user()->id);
        $data = $request->except('phone_reference');
        $user->userIdentifications()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        if ($request->has('phone_reference')) {
            $user->userPhoneReferences()->delete();
            foreach ($request->phone_reference as $phone) {
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




    public function uploadAPI($fileName, $url)
    {
        $curl = curl_init();

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $finfo = finfo_file($finfo, $fileName);
        $cFile = curl_file_create($fileName, $finfo, basename($fileName));
        $data = array("image" => $cFile, "filename" => $cFile->postname);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'api-key: ' . env('API_KEY_CCCD'),
            ),
        ));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }
}
