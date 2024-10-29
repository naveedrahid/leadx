<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\RefreshTokenRepository;
use Carbon\Carbon;
use App\Models\User;
use App\Http\Requests\{
    UserSignUpRequest,
    UserLoginRequest,
    SendResetLinkRequest,
    PasswordResetRequest,
    AccountSettingRequest,
    ChangePasswordRequest,
    DeleteAccountRequest
};
use App\Jobs\{
    LoginMailJob,
    SignupMailJob,
    WelcomeMailJob,
    DeleteAccountMailJob
};

class AuthController extends Controller
{
    public function signup(UserSignUpRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "User already exists!"
            ], 409);
        }

        $filename = null;
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time().uniqid().'.'.$file->getClientOriginalExtension();
	    	$file->storeAs('public/users', $filename);
        }

        $user_data = [
            "user_type" => "customer",
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "email" => $request->email,
            "email_verified_at" => now(),
            "phone_number" => $request->phone_number,
            "password" => bcrypt($request->password),
            "profile_image" => $filename,
            "avatar_color" => get_avatar_color(),
            "status" => "active"
        ];

        $user = User::create($user_data);
        
        $user->customer_details()->create([
            'is_avail_trial' => 0,
            'is_avail_free_plan' => 0,
            'auto_renewal_subscription' => 1,
            'pm_customer_id' => null,
        ]);

        $user->license()->create([
            'uuid' => Str::uuid(),
            'status' => 'deactive'
        ]);

        if (!auth()->attempt($request->only(['email', 'password']))) {
            return response()->json([
                "error" => 1,
                "message" => "Unauthorized!"
            ], 401);
        }

        $user->load('customer_details');
        
        $tokenData = $user->createToken(config('app.name'));
        $token = $tokenData->accessToken;
        $expiration = $tokenData->token->expires_at->diffInSeconds(now());

        dispatch(new SignupMailJob($user, $request->password));
        dispatch(new LoginMailJob($user));

        if (!$user->first_attempt && $user->status == 'active') {
            dispatch(new WelcomeMailJob($user));
            $user->update(['first_attempt' => true]);
        }
        
        return response()->json([
            "error" => 0,
            "data" => [
                'user' => $user,
                'authorisation' => [
                    'type' => 'Bearer',
                    'token' => $token,
                    'expiration' => $expiration
                ]
            ],
            "message" => "Congratulations! Your signup was a success!"
        ], 200);
    }

    public function authenticate(UserLoginRequest $request)
    {
        $emailExists = User::where('email', $request->email)->first();
        if(! $emailExists) {
            return response()->json([
                "error" => 1,
                "message" => 'The email is not registered. Please create your account.'
            ], 400);
        }

        $credentials = $request->only(['email', 'password']);
        if (!auth()->attempt($credentials)) {
            return response()->json([
                "error" => 1,
                "data" => [
                    "password" => [
                        "Incorrect Password!"
                    ]
                ],
                "message" => "Unauthorized!"
            ], 422); 
        }
        
        $user = User::where('email', $request->email)->first();
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                "error" => 1,
                "data" => [
                    "password" => [
                        "Incorrect Password!"
                    ]
                ],
                "message" => "Unauthorized!"
            ], 422); 
        }
        
        if ($user->status == 'deactive') {
            return response()->json([
                "error" => 1,
                "message" => "Your account is inactive."
            ], 403);
        }

        dispatch(new LoginMailJob($user));

        if (!$user->is_super && (!$user->first_attempt && $user->status == 'active')) {
            dispatch(new WelcomeMailJob($user));
            $user->update(['first_attempt' => true]);
        }

        if($user->user_type == 'customer') {
            $user->load('customer_details');
        }

        $tokenData = $user->createToken(config('app.name'));
        $token = $tokenData->accessToken;
        $expiration = $tokenData->token->expires_at->diffInSeconds(now());

        return response()->json([
            "error" => 0,
            "data" => [
                'user' => $user,
                'authorisation' => [
                    'type' => 'Bearer',
                    'token' => $token,
                    'expiration' => $expiration
                ]
            ],
            "message" => "Congratulations! You are now logged in"
        ], 200);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $token->delete();
        $refreshTokenRepository = app(RefreshTokenRepository::class);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
        return response()->json([
            "error" => 0,
            "data" => null,
            "message" => "You have been successfully logged out!"
        ], 200);
    }

    public function send_reset_link_response(SendResetLinkRequest $request)
    {
        $email = $request->only('email');
        $user = User::where('email', $email)->first();
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Email address doesn\'t exist"
            ], 404);
        }

        if($user->status == 'deactive') {
            return response()->json([
                "error" => 1,
                "message" => "Your account is deactive."
            ], 403);
        }

        $response = Password::sendResetLink($email);
        if($response === Password::RESET_LINK_SENT) {
            return response()->json([
                "error" => 0,
                "message" => "Reset password link sent on your email address."
            ], 200);
        } else {
            return response()->json([
                "error" => 0,
                "message" => "Reset password link already sent on your email address."
            ], 200);
        }
    }

    public function password_reset(PasswordResetRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
                    
                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            if($request->user()) {
                $token = $request->user()->token();
                $token->revoke();
                $token->delete();
                $refreshTokenRepository = app(RefreshTokenRepository::class);
                $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
            }

            return response()->json([
                "error" => 0,
                "data" => null,
                "message" => "Your password has been changed!"
            ], 200);
        } else {
            return response()->json([
                "error" => 1,
                "message" => "Token Invalid!"
            ], 401);
        }
    }

    public function get_user(Request $request)
    {
        $user = $request->user();
        if($user->status == 'deactive') {
            return response()->json([
                "error" => 1,
                "message" => "Your account is deactive."
            ], 401);
        }

        $user = User::find($user->id);
        if (is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Not Found!"
            ], 404);
        }

        if($user->user_type == 'customer') {
            $user->load('customer_details');
        }

        return response()->json([
            "error" => 0,
            "data" => [
                'user' => $user
            ],
            "message" => "User Details"
        ], 200);
    }

    public function account_setting(AccountSettingRequest $request)
    {
        $current_user = $request->user();
        $user = User::find($current_user->id);
        if (is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Not Found!"
            ], 404);
        }

        $profile_image = $user->profile_image;
        if($request->has('remove_profile_image')) {
            if (Storage::exists('public/users/' . $user->profile_image)) {
                Storage::delete('public/users/' . $user->profile_image);
            }

            $profile_image = null;
        }

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $profile_image = time().uniqid().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public/users', $profile_image);
            
            if (Storage::exists('public/users/' . $user->profile_image)) {
                Storage::delete('public/users/' . $user->profile_image);
            }
        }

        $user->update([
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "phone_number" => $request->phone_number,
            "profile_image" => $profile_image
        ]);

        if($user->user_type == 'customer') {
            $user->load('customer_details');
        }

        return response()->json([
            "error" => 0,
            "data" => [
                'user' => $user
            ],
            "message" => "Account setting has been updated successfully"
        ], 200);
    }

    public function change_password(ChangePasswordRequest $request)
    {
        $user = User::whereId($request->user()->id)->first();
        if (is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Not Found!"
            ], 404);
        }

        if(!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                "error" => 1,
                "data" => [
                    "current_password" => [
                        "The current password is incorrect"
                    ]
                ],
                "message" => "Validation Errors Found!"
            ], 422);
        }
        
        $user->update([
            "password" => bcrypt($request->password)
        ]);

        return response()->json([
            "error" => 0,
            "data" => null,
            "message" => "Password has been changed successfully"
        ], 200);
    }

    public function delete_account(DeleteAccountRequest $request) 
    {
        $current_user = $request->user();
        $user = User::find($current_user->id);
        if (is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Not Found!"
            ], 404);
        }

        if(!Hash::check($request->password, $user->password)) {
            return response()->json([
                "error" => 1,
                "message" => "Password is incorrect"
            ], 403);
        }
        
        if(!$user->is_super) {
            if($user->subscrptions()->exists()) {
                $user->subscrptions()->update([
                    'status' => 'cancelled'
                ]);
                $user->subscrptions()->delete();
            }
    
            if($user->websites()->exists()) {
                $user->websites()->delete();
            }
    
            if($user->leads()->exists()) {
                foreach($user->leads as $lead) {
                    $data = json_decode($lead->form_data);
                    if(!empty($data) && isset($data->data->file)) {
                        foreach($data->data->file as $key => $value) {
                            if (Storage::exists('/public/leads/' . $value->name)) {
                                Storage::delete('/public/leads/' . $value->name);
                            }
                        }
                    }
                }
                $user->leads()->delete();
            }
    
            if($user->license()->exists()) {
                $user->license()->delete();
            }
        }

        if($user->profile_image) {
            if (Storage::exists('/public/users/' . $user->profile_image)) {
                Storage::delete('/public/users/' . $user->profile_image);
            }
        }
        dispatch(new DeleteAccountMailJob($user->fullname, $user->email, now()->format('M d Y'), now()->format('h:i:s A')));
        
        if($user->user_type == 'customer') {
            $user->customer_details()->delete();
        }
        
        $user->delete();
        return response()->json([
            "error" => 0,
            "data" => null,
            "message" => "Account Deleted Successfully"
        ], 200);
    }
}
