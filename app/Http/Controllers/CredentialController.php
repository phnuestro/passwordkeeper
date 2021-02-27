<?php

namespace App\Http\Controllers;

use App\Models\Credential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

function generateStrongPassword($length = 15, $add_dashes = false, $available_sets = 'luds')
{
    $sets = array();
    if(strpos($available_sets, 'l') !== false)
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
    if(strpos($available_sets, 'u') !== false)
        $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    if(strpos($available_sets, 'd') !== false)
        $sets[] = '23456789';
    if(strpos($available_sets, 's') !== false)
        $sets[] = '!@#$%&*?';

    $all = '';
    $password = '';
    foreach($sets as $set)
    {
        $password .= $set[tweak_array_rand(str_split($set))];
        $all .= $set;
    }

    $all = str_split($all);
    for($i = 0; $i < $length - count($sets); $i++)
        $password .= $all[tweak_array_rand($all)];

    $password = str_shuffle($password);

    if(!$add_dashes)
        return $password;

    $dash_len = floor(sqrt($length));
    $dash_str = '';
    while(strlen($password) > $dash_len)
    {
        $dash_str .= substr($password, 0, $dash_len) . '-';
        $password = substr($password, $dash_len);
    }
    $dash_str .= $password;
    return $dash_str;
}

function tweak_array_rand($array){
    if (function_exists('random_int')) {
        return random_int(0, count($array) - 1);
    } elseif(function_exists('mt_rand')) {
        return mt_rand(0, count($array) - 1);
    } else {
        return array_rand($array);
    }
}

class CredentialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $credentials = Credential::where('user_id','=',Auth::id())->get();

        /* dd($vaults); */

        return inertia('Credential/Index',[
            'credentials' => $credentials,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
            'application' => ['required', 'string', 'max:255'],
            'link' => ['nullable', 'string', 'max:255'],

        ])->validate();

        if($request['password']) {
            $password=$request['password'];
        }
        else {
            $password=generateStrongPassword();
        }

        Credential::create([
            'username' => $request['username'],
            'password' => $password,
            'application' => $request['application'],
            'link' => $request['link'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('message', 'Credential Added Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Credential  $credential
     * @return \Illuminate\Http\Response
     */
    public function show(Credential $credential)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Credential  $credential
     * @return \Illuminate\Http\Response
     */
    public function edit(Credential $credential)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Credential  $credential
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Credential $credential)
    {
        Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
            'application' => ['required', 'string', 'max:255'],
            'link' => ['nullable', 'string', 'max:255'],
        ])->validate();

        if($request['password']!="") {
            $password=$request['password'];
        }
        else {
            $password=generateStrongPassword();
        }

        if ($request->has('id')) {
            Credential::find($request->input('id'))->update([
                'username' => $request['username'],
                'password' => $password,
                'application' => $request['application'],
                'link' => $request['link'],
            ]);

            return redirect()->back()->with('message', 'Credential Updated Successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Credential  $credential
     * @return \Illuminate\Http\Response
     */
    public function destroy(Credential $credential)
    {
        //
    }
}
