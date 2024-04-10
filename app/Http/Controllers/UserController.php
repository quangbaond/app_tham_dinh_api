<?php

namespace App\Http\Controllers;

use App\Models\SettingPeriod;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;

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
                $data = $response['data'][0];

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
                $data = $response['data'][0];

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

    public function updateFinance(Request $request)
    {
        $this->middleware('auth:api');
        $validator = Validator::make($request->all(), [
            'thu_nhap_hang_thang' => 'required|string',
            'ten_cong_ty' => 'required|string',
            'dia_chi_cong_ty' => 'required|string',
            'so_dien_thoai_cong_ty' => 'required|string',
            'so_dien_thoai_noi_lam_viec' => 'required|array',
            'sao_ke_nhan_luong' => 'array|nullable',

        ], [
            'thu_nhap_hang_thang.required' => 'Thu nhập hàng tháng không được để trống',
            'thu_nhap_hang_thang.string' => 'Thu nhập hàng tháng phải là chuỗi',
            'ten_cong_ty.required' => 'Tên công ty không được để trống',
            'ten_cong_ty.string' => 'Tên công ty phải là chuỗi',
            'dia_chi_cong_ty.required' => 'Địa chỉ công ty không được để trống',
            'dia_chi_cong_ty.string' => 'Địa chỉ công ty phải là chuỗi',
            'so_dien_thoai_cong_ty.required' => 'Số điện thoại công ty không được để trống',
            'so_dien_thoai_cong_ty.string' => 'Số điện thoại công ty phải là chuỗi',
            'so_dien_thoai_noi_lam_viec.required' => 'Danh sách số điện thoại nơi làm việc không được để trống',
            'so_dien_thoai_noi_lam_viec.array' => 'Danh sách số điện thoại nơi làm việc phải là mảng',
            'sao_ke_nhan_luong.array' => 'Danh sách sao kê nhận lương phải là mảng',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find(auth()->user()->id);
        $data = $request->except('so_dien_thoai_noi_lam_viec', 'sao_ke_nhan_luong');
        $user->userFinances()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        if ($request->has('so_dien_thoai_noi_lam_viec')) {
            $user->userPhoneWorkPlaces()->delete();

            foreach ($request->so_dien_thoai_noi_lam_viec as $phone) {
                $user->userPhoneWorkPlaces()->create([
                    'phone' => $phone['phone'],
                    'name' => $phone['name'],
                    'relationship' => $phone['relationship'],
                ]);
            }
        }

        if ($request->has('sao_ke_nhan_luong')) {
            $user->userSalaryStatements()->delete();
            foreach ($request->sao_ke_nhan_luong as $saoKe) {
                $imageName = time() . '.' . $saoKe->extension();
                $saoKe->storeAs('public/images', $imageName);
                $user->userSalaryStatements()->create([
                    'images' => asset('storage/images/' . $imageName),
                ]);
            }
        }

        $user->load([
            'userFinances',
            'userSalaryStatements',
            'userPhoneWorkPlaces',
            'userPhoneReferences',
            'userIdentifications',
            'userLicenses',
            'userMovables',
            'userSanEstates',
            'userLoanAmounts'
        ]);

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

    public function updateTaiSan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bat_dong_san' => 'array|nullable',
            'dong_san' => 'array|nullable',
        ], [
            'bat_dong_san.array' => 'Bất động sản phải là mảng',
            'dong_san.array' => 'Động sản phải là mảng',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find(auth()->user()->id);

        if ($request->has('bat_dong_san')) {
            $user->userSanEstates()->delete();
            foreach ($request->bat_dong_san as $batDongSan) {
                // check $batDongSan['hinh_anh'] is image
                if ($batDongSan['hinh_anh'] == null) {
                    $user->userSanEstates()->updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'dia_chi' => $batDongSan['dia_chi'],
                        ]
                    );
                } else {
                    // check iss file image hinh anh
                    if ($batDongSan['hinh_anh'] instanceof UploadedFile) {
                        $imageName = time() . '.' . $batDongSan['hinh_anh']->extension();
                        $batDongSan['hinh_anh']->storeAs('public/images', $imageName);

                        $user->userSanEstates()->updateOrCreate(
                            ['user_id' => $user->id],
                            [
                                'hinh_anh' => asset('storage/images/' . $imageName),
                                'dia_chi' => $batDongSan['dia_chi'],
                            ]
                        );
                    } else {
                        $user->userSanEstates()->updateOrCreate(
                            ['user_id' => $user->id],
                            [
                                'dia_chi' => $batDongSan['dia_chi'],
                            ]
                        );
                    }
                }


            }
        }

        if ($request->has('dong_san')) {
            $user->userMovables()->delete();
            foreach ($request->dong_san as $dongSan) {
                if ($dongSan['hinh_anh'] == null) {
                    $user->userMovables()->updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'dia_chi' => $dongSan['dia_chi'],
                            'loai_tai_san' => $dongSan['loai_tai_san'],
                        ]
                    );
                } else {
                    if ($dongSan['hinh_anh'] instanceof UploadedFile) {
                        $imageName = time() . '.' . $dongSan['hinh_anh']->extension();
                        $dongSan['hinh_anh']->storeAs('public/images', $imageName);

                        $user->userMovables()->updateOrCreate(
                            ['user_id' => $user->id],
                            [
                                'hinh_anh' => asset('storage/images/' . $imageName),
                                'dia_chi' => $dongSan['dia_chi'],
                                'loai_tai_san' => $dongSan['loai_tai_san'],
                            ]
                        );
                    } else {
                        $user->userMovables()->updateOrCreate(
                            ['user_id' => $user->id],
                            [
                                'dia_chi' => $dongSan['dia_chi'],
                                'loai_tai_san' => $dongSan['loai_tai_san'],
                            ]
                        );
                    }
                }

            }
        }

        $user->load([
            'userFinances',
            'userSalaryStatements',
            'userPhoneWorkPlaces',
            'userPhoneReferences',
            'userIdentifications',
            'userLicenses',
            'userMovables',
            'userSanEstates',
            'userLoanAmounts'
        ]);

        return response()->json([
            'message' => 'Cập nhật thành công',
            'user' => $user,
        ], 201);

    }

    public function createLoanAmount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'khoan_vay' => 'required|string',
            'thoi_han_vay' => 'required|string',
        ], [
            'khoan_vay.required' => 'Khoản vay không được để trống',
            'khoan_vay.string' => 'Khoản vay phải là chuỗi',
            'thoi_han_vay.required' => 'Thời hạn vay không được để trống',
            'thoi_han_vay.string' => 'Thời hạn vay phải là chuỗi',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $user = User::find(auth()->user()->id);

        // check xem có khoản vay nào chưa duyệt không
        $userLoanAmount = $user->userLoanAmounts()->where('status', 0)->first();
        if ($userLoanAmount) {
            return response()->json([
                'message' => 'Bạn có 1 khoản vay chưa được duyệt',
            ], 400);
        }

        // check xem có khoản vay nào chưa hoàn thành
        $userLoanAmount = $user->userLoanAmounts()->where('status', 3)->first();
        if ($userLoanAmount) {
            return response()->json([
                'message' => 'Bạn có 1 khoản vay chưa hoàn thành',
            ], 400);
        }

        $userLoanAmount = $user->userLoanAmounts()->updateOrCreate(
            ['user_id' => $user->id],
            $validator->validated()
        );

        $phantram = SettingPeriod::where('title', $request->thoi_han_vay)->first()->value;

        $lichtra = $this->tinhlai($userLoanAmount->khoan_vay, $userLoanAmount->thoi_han_vay, $phantram);
        $userLoanAmount->userHistoryLoanAmounts()->createMany($lichtra);

        $user->load([
            'userFinances',
            'userSalaryStatements',
            'userPhoneWorkPlaces',
            'userPhoneReferences',
            'userIdentifications',
            'userLicenses',
            'userMovables',
            'userSanEstates',
            'userLoanAmounts'
        ]);

        return response()->json([
            'message' => 'Cập nhật thành công',
            'user' => $user,
        ], 201);
    }

    protected function tinhlai($khoanvay, $thoihanvay, $phantram)
    {
        $laixuat = $phantram / 12;
        $goc = $khoanvay;
        $thoihan = (int)str_replace(' tháng', '', $thoihanvay);
        $lichtra = [];
        $goc_con_lai = $goc;
        $goc_moi_ky = $goc / $thoihan;
        $lai = 0;
        $tong_goc_lai = 0;

        for ($i = 1; $i <= $thoihan; $i++) {
            $lai = $goc_con_lai * $laixuat / 100;
            $tong_goc_lai = $goc_moi_ky + $lai;
            $goc_con_lai = $goc_con_lai - $goc_moi_ky;
            $date = date('Y-m-d', strtotime("+$i months"));
            $lichtra[] = [
                'ngay_tra' => $date,
                'so_tien_tra' => 0,
                'so_goc_con_no' => $goc_con_lai,
                'so_tien_lai' => $lai,
                'tong_goc_lai' => $tong_goc_lai,
                'status' => 0,
                'status_1' => 0,
                'status_2' => 0,
                'status_3' => 0,
            ];
        }
        return $lichtra;
    }

    public function getUserLoanAmount(Request $request, $id)
    {
        $this->middleware('auth:api');
        $user = User::find(auth()->user()->id);
        $userLoanAmount = $user->userLoanAmounts()->where('id', $id)->first();
        if (!$userLoanAmount) {
            return response()->json([
                'message' => 'Không tìm thấy khoản vay',
            ], 404);
        }

        $userLoanAmount->load('userHistoryLoanAmounts');

        return response()->json([
            'message' => 'Thành công',
            'userLoanAmount' => $userLoanAmount,
        ], 200);
    }
}
