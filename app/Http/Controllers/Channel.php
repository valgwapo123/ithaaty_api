<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserChannel;
use App\Models\UserPodcast;


class Channel extends Controller
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


    public function Search_Channel(Request $request){
       
        // $fields=$request->validate([
        //     'setup_ownerid'=>'required|string',
        // ]);

         $search = '%'.$request->input('searchdata').'%';

        $Userchannel= UserChannel::where('channel_typestatus','active')
        ->where('channel_name', 'like', $search)->take(10)->get();





        return response($Userchannel, 201);



     }   

         public function Search_Podcast(Request $request){
       
        $fields=$request->validate([
            'channel_id'=>'required|string',
        ]);

         $search = '%'.$request->input('searchdata').'%';


        $Userchannel= UserPodcast::where('podcast_channelid',$fields['channel_id'])
        ->where('podcast_title', 'like', $search)->take(10)->get();





        return response($Userchannel, 201);



     }   

    public function Search_Episode(Request $request){

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
