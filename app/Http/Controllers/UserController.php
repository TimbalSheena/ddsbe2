<?php

    namespace App\Http\Controllers;
    use App\Models\User;  // <-- your model
    use Illuminate\Http\Response;
    use App\Traits\ApiResponser;  // <-- use to standardized our code for api response
   use Illuminate\Http\Request; 
  // use App\Http\Requests\Request;

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

        $users = app('db')->select("SELECT * FROM tbluser WHERE username='$username' and password='$password'");

        if(!$users || !$password){
            return 'Invalid credentials!';
        }else{
            return 'Successfully Log-In!';
        }
        
    }
    

    public function index()
    {
    $users = User::all();
    return response()->json($users);
    }

    public function create(Request $request ){
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $this->validate($request,$rules);

        $users = User::create($request->all());

        return response()->json(['status' => 'successfully added!','result' =>$users]);
    }

    public function read($id)
    {
        $users = User::find($id);
        return response()->json($users);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
        'username' => 'filled',
        'password' => 'filled',
         ]);
        $users = User::find($id);
        if($users->fill($request->all())->save()){
           return response()->json(['status' => 'success','result' =>$users]);
        }
        return response()->json(['status' => 'failed','result' =>$users]);
    }

    public function destroy($id)
    {
        $users = User::find($id);
        $users->delete();
        return response()->json('Deleted successfully!');
    }
}
