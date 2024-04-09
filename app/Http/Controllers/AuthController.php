<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Validator;
use App\Services\SmsService;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|between:10,13',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Tài khoản hoặc mật khẩu không hợp lệ'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|between:10,13|unique:users',
            'password' => 'required|string|min:6',
        ], [
            'phone.unique' => 'Số điện thoại đã tồn tại',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.string' => 'Số điện thoại không hợp lệ',
            'phone.between' => 'Số điện thoại không hợp lệ',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create(
            array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            )
        );
        // $code = rand(100000, 999999);
        // $content = 'Mã xác thực của bạn tại' . env('APP_NAME') . ' là: ' . $code . '. Vui lòng không chia sẻ mã này với bất kỳ ai.';
        // $content = urlencode($content);

        // create user verify
        // $user->userVerify()->create([
        //     'code' => $code,
        //     'type' => 'sms'
        // ]);

        // // send sms
        // $smsService = new SmsService('yLfieumHKerd_vL1ZG05O8TwIJS8iqnZ');
        // $response = $smsService->sendSMS(['0389228496'], $content, 2, 'SMS');

        // var_dump($response);
        // exit();

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user->with([
                'userFinances',
                'userSalaryStatements',
                'userPhoneWorkPlaces',
                'userPhoneReferences',
            ])->first()
        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        $user = auth()->user();
        $user->load([
            'userFinances',
            'userSalaryStatements',
            'userPhoneWorkPlaces',
            'userPhoneReferences',
            'userIdentifications',
            'userLicenses'
        ]);
        return response()->json($user);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        $user = auth()->user();
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
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }

    public function changePassWord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $userId = auth()->user()->id;

        $user = User::where('id', $userId)->update(
            ['password' => bcrypt($request->new_password)]
        );

        return response()->json([
            'message' => 'User successfully changed password',
            'user' => $user,
        ], 201);
    }

    public function me (Request $request) {
        $user = User::find($request->user()->id);

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
        return response()->json($user);
    }
}
