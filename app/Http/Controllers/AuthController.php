<?php

namespace App\Http\Controllers;

use App\Mail\email;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function store(Request $request)
    {
          //Validate inputs
          $request->validate
          ([
             'name'=>'required',
             'email'=>'required|email|unique:users,email',
             'role'=>'required',
             'status'=>'required',
             'location'=>'required',
             'password'=>'required|min:3|max:30',
             'cpassword'=>'required|min:3|max:30|same:password'
            ]);
          $user = new User();
          $user->name = $request->name;
          $user->email = $request->email;
          $user->role = $request->role;
          $user->status = $request->status;
          $user->location = $request->location;
          $user->password = \Hash::make($request->password);
          $save = $user->save();
          if( $save ){
              return redirect('/login')->with('success','You are now registered successfully as Laboratory');
          }else{
              return redirect()->back()->with('fail','Something went Wrong, failed to register');
          }
    }
    public function login(Request $request)
    {
        //Validate Inputs
        // int n = 0;
        $request->validate([
            'email'=>'required|email|exists:users,email',
            'password'=>'required|min:5|max:30'
        ],[
            'email.exists'=>'This email is not exists in users table'
        ]);

        $creds = $request->only('email','password');

        if( Auth::guard('web')->attempt($creds) ){
                
            if( Auth::guard('web')->user()->role =='laboratory' )
            {
                if(Auth::guard('web')->user()->status =='1')
                {
                    return redirect('/lab/home');
                }
                else
                {
                    return redirect()->back()->with('fail', 'Your Request Yet to be Approved');
                }
            }
            if( Auth::guard('web')->user()->role =='patient' )
            {
                return redirect('/patient/home');
            }
            if( Auth::guard('web')->user()->role =='employee' )
            {
                return redirect('/employee/home');
            }
            if( Auth::guard('web')->user()->role =='admin' )
            {
                return redirect('/admin/home');
            }
        }
        else{
            return redirect('/login')->with('fail','Incorrect Credentials');
        }
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect('/login');
    }

    public function labRequests()
    {
        $user = DB::table('users')->where('status', 0)->get();
        return view('admin.requests', [ 'users' => $user]);
    }

    public function approveRequests(Request $request, $user_id)
    {
        $id = User::find($user_id);
        $details = [
            'title' => 'Demo Class Request',
            'body' => 'Your request for demo class has been Approved',
            // 'Course-Name' => $course_name,
            // 'Course-schedule' => $course_schedule,
            // 'Course-howToConduct' => $course_howToConduct,
        ];
        Mail::to('uxama.ali420@gmail.com')->send(new email($details));
        if (Mail::failures()) {
            return response()->Fail('Sorry! Please try again latter');
       }else{
        return redirect('/admin/home');
            // return response()->success('Great! Successfully send in your mail');
          }
        // return redirect('/admin/home');

        // return redirect()->back()->with('success' , 'Request Approved');
    }

}
