<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    /**
     * Login
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function login(Request $request){
        if (Auth::check()) { // Nếu server auth đã login thì chuyển hướng lại trang ban đầu kèm token dùng để login
            $user = User::find(Auth::user()->id);
            $user->auth_token = Str::random(40); // Nếu đã login thì tạo auth token dùng để định danh user
            $user->save();
            return redirect()->to("http://" . $request->input('redirect_url') . "?token=" . $user->auth_token);
        }
        // Nếu chưa login thì sẽ hiện thị form login
        $redirectUrl = $request->input('redirect_url', null);
        return view('login', ['redirectUrl' => $redirectUrl]);
    }

    /**
     * Thực hiện login
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function auth(Request $request){
        $credentials = $request->only(['email', 'password']);
        $remember = $request->input('remember', false);
        if (Auth::attempt($credentials, $remember)) {
            $user = User::find(Auth::user()->id);
            $user->auth_token = Str::random(40);
            $user->save();
            if ($request->input('redirect_url')){ // Nếu login thành công sẽ chuyển hường lại trang ban đầu kèm auth token
                return redirect()->to("http://" . $request->input('redirect_url') . "?token=" . $user->auth_token);
            }else{
                return redirect()->route('home');
            }
        }
    }

    /**
     * Thực hiện logout
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request){
        $user = Auth::user();
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->delete(); // Xóa session user
        if ($request->input('redirect_url')){ // Chuyển hướng về trang login
            return redirect('/login?redirect_url=' . $request->input('redirect_url'));
        }else{
            return redirect()->route('home');
        }
    }
}
