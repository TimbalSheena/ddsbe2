<?php

    namespace App\Http\Controllers;

    use App\Models\User;  // <-- your model
    use App\Models\UserJob;  // Require

    use Illuminate\Http\Response;
    use App\Traits\ApiResponser;  // <-- use to standardized our code for api response
    use Illuminate\Http\Request; 
  // use App\Http\Requests\Request;
  use App\Http\Controllers\Controller;

Class UserController extends Controller {
    use ApiResponser;
    private $request;

    public function __construct(Request $request){
    $this->request = $request;
    }

    public function getUsers(){
        $users = app('db') ->select ("SELECT * FROM tbluser");
        return $this->successResponse($users);
 }

    function Login()
    {
    return view('login');
    }

    public function test(Request $request)
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $users = app('db')->select("SELECT * FROM tbluser2 WHERE username='$username' and password='$password'");

        if(!$users || !$password){
            return 'Invalid credentials!';
        }else{
            return 'Successfully Log-In!';
        }
        
    }
    

    public function index()
    {
    $users = User::all();
    return $this->sucessResponse($users);
    }

    public function create(Request $request ){
        $rules = [
        'username' => 'required|max:20',
        'password' => 'required|max:20',
        'jobid' => 'required|numeric|min:1|not_in:0', //we need to require, then it should be numeric and will start in 1 not 0
        ];

        $this->validate($request,$rules);
        // validate if Jobid is found in the table tbluserjob
        $userjob =UserJob::findOrFail($request->jobid);
        $user = User::create($request->all());
        return $this->successResponse($user,Response::HTTP_CREATED);
        }

    // public function create(Request $request ){
    //     $rules = [
    //         'username' => 'required',
    //         'password' => 'required'
    //     ];

    //     $this->validate($request,$rules);

    //     $users = User::create($request->all());

    //     return response()->json(['status' => 'successfully added!','result' =>$users]);
    // }

    public function read($id)
    {
        $users = User::find($id);
        return response()->json($users);
    }
    //old code in update

    // public function update(Request $request, $id)
    // {
    //     $this->validate($request, [
    //     'username' => 'filled',
    //     'password' => 'filled',
    //     'jobid' => 'required|numeric|min:1|not_in:0',
    //      ]);
    //     $userjob = UserJob::findOrFail($request->jobid);
    //     $users = User::find($id);
    //     if($users->fill($request->all())->save()){
    //        return response()->json(['status' => 'success','result' =>$users]);
    //     }
    //     return response()->json(['status' => 'failed','result' =>$users]);
    // }
    public function update(Request $request,$id){
        $rules = [
        'username' => 'max:20',
        'password' => 'max:20',
        'jobid' => 'required|numeric|min:1|not_in:0',
        ];

        $this->validate($request, $rules);
        // validate if Jobid is found in the table tbluserjob
        // $userjob = UserJob::findOrFail($request->jobid);
        $user = User::findOrFail($id);
    
        $user->fill($request->all());
        // if no changes happen
        if ($user->isClean()) {
        return $this->errorResponse('At least one value must change',Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user->save();
        return $this->successResponse($user);
   
    }

    public function destroy($id)
    {
        $users = User::find($id);
        $users->delete();
        return response()->json('Deleted successfully!');
    }
}
