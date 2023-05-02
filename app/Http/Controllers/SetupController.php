<?php

namespace App\Http\Controllers;

use App\Models\UserSetup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\UserFriends;
use Illuminate\Support\Facades\DB;



class SetupController extends Controller
{


     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
     
     
     

    }

    //POST
    //setup_ownerid
    //bearer token = generated toke after login

    public function Step_setup(Request $request){


        $fields=$request->validate([
            'setup_ownerid'=>'required|string',
        ]);


       $Setup= UserSetup::where('setup_ownerid',$fields['setup_ownerid'])->get();

       $friendsetup;
       $interesetup;
       $channelsetup;
        foreach($Setup as $row){
             if($row->setup_type=='Friend Setup'){
                $friendsetup=$row->setup_typestatus;
             }elseif($row->setup_type=='Interest Setup'){
                 $interesetup=$row->setup_typestatus;
             }elseif ($row->setup_type=='Channel Setup') {
                  $channelsetup=$row->setup_typestatus;
             }
         }      


        $currentstep='0';
 
        if($friendsetup=='Incomplete'){

            $currentstep='1';     
        }
        if($interesetup=='Incomplete' &&  $friendsetup=='Complete'){

            $currentstep='2';     
        }
        if($interesetup=='Incomplete' &&  $interesetup=='Complete'){

            $currentstep='3';     
        }   

          if($interesetup=='Complete' &&  $interesetup=='Complete'){

            $currentstep='done';     
        }    
       
        $response=[
            'friend_setup'=>$friendsetup,
            'interest_setup'=>$interesetup,
            'channel_setup'=>$channelsetup,
            'current_step'=>$currentstep,
            'redirect_link'=>'',
    
        ];

        return response($response, 201);
    }

   //POST
    //setup_ownerid
    //bearer token = generated toke after login
     public function searchFriend(Request $request){
       
        $fields=$request->validate([
            'setup_ownerid'=>'required|string',
        ]);

         $search = '%'.$request->input('searchdata').'%';

        $Setup= User::where('id','<>',$fields['setup_ownerid'])
        ->where('roles','editor')
        ->where('name', 'like', $search)
        ->select('id','name','email','gender',DB::raw('(CASE WHEN 
        '.DB::raw("(SELECT COUNT(id)  FROM user_friends WHERE user_friends.id=Users.id AND user_friends.friend_requestid ='".$fields['setup_ownerid']."')   ").' =0 THEN "ADD" ELSE "SENT" END) AS sendrequest')
        ) ->take(10)->get();



 // DB::raw("(SELECT COUNT(id)  FROM user_friends
 //                                WHERE user_friends.id=Users.id AND user_friends.friend_requestid ='".$fields['setup_ownerid']."')  as sendrequest "),
        //  foreach($Setup as $row){

        //   $response=[
        //     'name'=>$row->name,
        //     'email'=>$row->email,
        //     'gender'=>$row->roles,
        //     'sendrequest'=>$row->sendrequest
        // ];

        // }     

         return response($Setup, 201);



     }   

     public function addFriend(Request $request){


        $fields=$request->validate([
            'friend_userid'=>'required|string',
            'friend_requestid'=>'required|string',       
        ]);


             $data = new UserFriends;
             $data->friend_userid = $fields['friend_userid'];
             $data->friend_requestid = $fields['friend_requestid'];
             $data->friend_type = "Send Request";
             $data->friend_status = "active";
             $data->friend_block_type = "empty";
             $data->save();


            // UserFriends::updateOrCreate(['friend_userid' =>  $fields['friend_userid'], [
            //     'friend_userid' =>$fields['friend_userid'],        
            //     'friend_requestid' => $fields['friend_requestid'],         
            //     'friend_type' => "Send Request",      
            //     'friend_status' => "active",       
            //     'friend_block_type' => "empty" ,       
            // ]);


        $response=[
            'data'=>$data,
             'Request'=>'Sent',
        ];

            return response($response, 201);

     }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
