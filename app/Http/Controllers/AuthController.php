<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; 
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
class AuthController extends Controller
{

    public function register(Request $request){

    	$fields=$request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string',
            'birth_month'=>'required|in:January,February,March,April,May,June,July,August,Septempber,October,November,December',
            'birth_day'=>'required|min:2|max:2',
            'birth_year'=>'required|min:4|max:4',
            'country'=>'required|in:Afghanistan,Albania,Algeria,American Samoa,Andorra,Angola,Anguilla,Antigua & Barbuda,Argentina,Armenia,Aruba,Australia,Austria,Azerbaijan,Bahamas,Bahrain,Bangladesh,Barbados,Belarus,Belgium,Belize,Benin,Bermuda,Bhutan,Bolivia,Bonaire,Bosnia & Herzegovina,Botswana,Brazil,British Indian Ocean Ter,Brunei,Bulgaria,Burkina Faso,Burundi,Cambodia,Cameroon,Canada,Canary Islands,Cape-Verde,Cayman Islands,Central African Republic,Chad,Channel Islands,Chile,China,Christmas-Island,Cocos Island,Colombia,Comoros,Congo,Cook Islands,Costa-Rica,Cote-DIvoire,Croatia,Cuba,Curacao,Cyprus,Czech Republic,Denmark,Djibouti,Dominica,Dominican Republic,East-Timor,Ecuador,Egypt,El-Salvador,Equatorial Guinea,Eritrea,Estonia,Ethiopia,Falkland Islands,Faroe-Islandsm,Fiji,Finland,France,French Guiana,French Polynesia,French Southern Ter,Gabon,Gambia,Georgia,Germany,Ghana,Gibraltar,Great Britain,Greece,Greenland,Grenada,Guadeloupe,Guam,Guatemala,Guinea,Guyana,Haiti,Hawaii,Honduras,Hong Kong,Hungary,Iceland,Indonesia,India,Iran,Iraq,Ireland,Isle of Man,Israel,Italy,Jamaica,Japan,Jordan,Kazakhstan,Kenya,Kiribati,Korea North,Korea South,Kuwait,Kyrgyzstan,Laos,Latvia,Lebanon,Lesotho,Liberia,Libya,Liechtenstein,Lithuania,Luxembourg,Macau,Macedonia,Madagascar,Malaysia,Malawi,Maldives,Mali,Malta,Marshall Islands,Martinique,Mauritania,Mauritius,Mayotte,Mexico,Midway Islands,Moldova,Monaco,Mongolia,Montserrat,Morocco,Mozambique,Myanmar,Nambia,Nauru,Nepal,Netherland Antilles,Netherlands(Holland,Europe),Nevis,New Caledonia,New Zealand,Nicaragua,Niger,Nigeria,Niue,Norfolk Island,Norway,Oman,Pakistan,Palau Island,Palestine,Panama,Papua New Guinea,Paraguay,Peru,Philippines,Pitcairn Island,Poland,Portugal,Puerto Rico,Qatar,Republic of Montenegro,Republic of Serbia,Reunion,Romania,Russia,Rwanda,St Barthelemy,St Eustatius,St Helena,St Kitts Nevis,St Lucia,St Maarten,St Pierre & Miquelon,St Vincent & Grenadines,Saipan,Samoa,Samoa American,San Marino,Sao Tome & Principe,Saudi Arabia,Senegal,Seychelles,Sierra Leone,Singapore,Slovakia,Slovenia,Solomon Islands,Somalia,South Africa,Spain,Sri Lanka,Sudan,Suriname,Swaziland,Sweden,Switzerland,Syria,Tahiti,Taiwan,Tajikistan,Tanzania,Thailand,Togo,Tokelau,Tonga,Trinidad & Tobago,Tunisia,Turkey,Turkmenistan,Turks & Caicos Is,Tuvalu,Uganda,United Kingdom,Ukraine,United Arab Emirates,United States of America,Uruguay,Uzbekistan,Vanuatu,Vatican City State,Venezuela,Vietnam,Virgin Islands (Brit),Virgin Islands (USA),Wake-Island,Wallis & Futana Is,Yemen,Zaire,Zambia,Zimbabwe',


            'gender'=>'required|in:Male,Female' 

    	]
        ,
        [
        'birth_month.in' => 'Invalid birth month value. Valid value are January,February,March,April,May,June,July,August,Septempber,October,November,December',
        'gender.in' => 'Invalid gender value. Valid value Male and Female',
        'birth_year.min' => 'Invalid value example valid 1996',
        'birth_year.max' => 'Invalid value example valid 1996',
        'birth_day.min' => 'Invalid value example valid 01-31',
        'birth_day.max' => 'Invalid value example valid 01-31',
         'country.in' => 'Invalid Country value. Valid example `Philippines` etc must uppercase format ',

        ]

    );


   

     
       $randomStr = Str::random(5);
       $verified_link =  Str::random(25);

    	$user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=> Hash::make($request->password),
            'birthday'=>$request->birth_month.','.$request->birth_day.','.$request->birth_year,
            'roles' => 'editor',
            'gender' => $request->gender,
            'country' =>$request->country,
            'age' => '0', 
            'verified_user' => '1',
            'verified_link' => $verified_link, 
            'plan' => 'new',
            'alias' => "User".$randomStr,
            'about' => ' ',
    	]);



    	//$token= $user->createToken('myapptoken')->plainTextToken;

    	$response=[


    		'user'=>'Save Successfully',
            'redirect_link'=>'login',
    	];

    	return response($response, 201);
    }



    public function login(Request $request){

    	$fields=$request->validate([
    		'email'=>'required|string',
    		'password'=>'required|string'
    	]);

        $id='';
        $name='';
        $email='';
        $roles='';
        $firstlogin='';
        $alias='';

    	//Check email

    	$user=User::where('email',$fields['email'])->first();

         //  foreach($user as $row){
         //    $id =$row->id;  
         //    $name =$row->name;  
         //    $email =$row->email;  
         //    $roles =$row->roles;  
         //    $firstlogin =$row->firstlogin;  
         //    $alias =$row->alias; 
         // }     


    	//Checkpassword
    	if(!$user || !Hash::check($fields['password'],$user->password)){
    		return response([
    			'message'=>'Bad Creds'
    		],401);
    	}

    	$token= $user->createToken('myapptoken')->plainTextToken;

    	$response=[
            'message'=>'Sucess',
            'id'=>$user->id,
            'name'=>$user->name,
            'email'=>$user->email,
            'roles'=>$user->roles,
            'firstlogin'=>$user->firstlogin,
            'alias'=>$user->alias,
    		'token'=>$token,
             'redirect_link'=>($user->firstlogin == 0) ? 'Setup' : 'Dashboard',
    
    	];

    	return response($response, 201);
    }


     public function logout(Request $request){
     	auth()->user()->tokens()->delete();

     	return [
     		'message'=>'Logged out'
     	];
     }



     //facebook
     //twitter

     
    /**
     * Redirect the user to the Provider authentication page.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Obtain the user information from Provider.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function handleProviderCallback($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        $userCreated = User::firstOrCreate(
            [
                'email' => $user->getEmail()
            ],
            [
                'email_verified_at' => now(),
                'name' => $user->getName(),
                'status' => true,
            ]
        );
        $userCreated->providers()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $user->getId(),
            ],
            [
                'avatar' => $user->getAvatar()
            ]
        );
        $token = $userCreated->createToken('token-name')->plainTextToken;

        return response()->json($userCreated, 200, ['Access-Token' => $token]);
    }

    /**
     * @param $provider
     * @return JsonResponse
     */
    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'github', 'google'])) {
            return response()->json(['error' => 'Please login using facebook, github or google'], 422);
        }
    }


}
