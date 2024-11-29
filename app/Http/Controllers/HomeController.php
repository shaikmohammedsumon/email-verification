<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\rand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MyFirstNotification;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }




    public function sendnotification(){
        $user = [Auth::user()];
        $rand = rand(1111,9999);

        $codeVe = rand::where('user_id', Auth::user()->id)->first();

        if (empty($codeVe)) {
            rand::create([
                'rand' => $rand,
                'user_id' => Auth::user()->id,
                'created_ar' => now(),
            ]);
        } else {
            $codeVe->delete();
            rand::create([
                'rand' => $rand,
                'user_id' => Auth::user()->id,
                'created_ar' => now(),
            ]);
        }

        $details = [
            'greeting' => 'হাই! লারাভেল ডেভেলপার',
            'body' => 'এটি একটি ইমেইল বডি',
            'actiontext' => 'সাবজেক্ট ট্যাগস',
            'actionUrl' => '/',
            'lastLine' =>  $rand,
        ];
        Notification::send($user, new MyFirstNotification($details));
        return redirect()->route('submit.index');
    }

    public function submit_index(Request $request){

        $expiry = rand::where('user_id', Auth::user()->id)->first();

        if ($expiry) {
            // Created_at থেকে 60 সেকেন্ড যোগ করা
            $expiryDate = Carbon::parse($expiry->created_at)->addSeconds(60)->timestamp; // Unix timestamp এ কনভার্ট করা
        } else {
            $expiryDate = null; // ডেটা না থাকলে null সেট করা
        }
        return view('passReset.index', compact('expiryDate'));
    }

    public function submit(Request $request){

        $expiry = rand::where('user_id', Auth::user()->id)->first();

        if ($expiry) {
            // Calculate expiry date as 60 seconds after creation
            $createdAt = Carbon::parse($expiry->created_at);
            $expiryDate = $createdAt->addSeconds(60);

            if (Carbon::now()->greaterThan($expiryDate)) {
                echo "আপনার কোডের মেয়াদ শেষ হয়ে গেছে।";
            } else {
                echo "আপনার কোড এখনও বৈধ।";
            }
        } else {
            echo "এই ইউজারের জন্য কোড পাওয়া যায়নি।";
        }
    }

}
