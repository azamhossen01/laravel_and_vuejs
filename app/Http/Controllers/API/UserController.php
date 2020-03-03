<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('auth:api');
    }

    public function index()
    {
        $users = User::latest()->paginate(10);
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $this->validate($request,[
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:6',
            'type' => 'required'
        ]);
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'type' => $request->type,
            'password' => bcrypt($request->password)
        ]);
        return 'ok';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $user = User::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6',
            'type' => 'required'
        ]);

        $user->update($request->all());
        // $user->name = $request->name;
        // $user->email = $request->email;
        // $user->bio = $request->bio;
        // $user->type = $request->type;
        // $user->password = bcrypt($request->password);
        // $user->update();
        // return 'ok';
        return response()->json(['message'=>'User updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;
        $user->delete();
        return response()->json(['message'=>"User $name deleted successfully"]);
    }

    public function profile(){
        return auth('api')->user();
    }

    public function update_profile(Request $request){
        $exploded = explode(',',$request->photo);
        if(strpos($exploded[0],'jpeg')){
            $extension = 'jpg';
        }else{
            $extension = 'png';
        }
        $decoded = base64_decode($exploded[1]);
        $file_name = time().'.'.$extension;
        $file_path = public_path('img/').$file_name;
        file_put_contents($file_path,$decoded);
        return 'ok';
    }
}
