<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(){
        //$chats=all();
return   Chat::hasParticipant(auth()->user()->user_id)
        ->whereHas('Chat_message')->with('lastMessage.normal_user','participants.normal_user')
        ->latest('updated_at')->get();
    }

    public function store(Request $request){
      $data=  $request->validate([
            "user_id" => "required|exists:normal_user,user_id"
        ]);
        $other_user_id= $data['user_id'];
        if($other_user_id==auth()->user()->id){
            return response('you cannot chat with yourself');
        }
       //
        $data['created_by'] = auth()->user()->user_id;



        $previousChat = $this->getPreviousChat($other_user_id);

        if($previousChat === null){

            $chat = Chat::create($data['data']);
            $chat->participants()->createMany([
                [
                    'user_id'=>$data['userId']
                ],
                [
                    'user_id'=>$other_user_id
                ]
            ]);

            $chat->refresh()->load('lastMessage.user','participants.user');
            return $chat;
        }

        return $previousChat->load('lastMessage.normal_user','participants.normal_user');



    }


    public function getPreviousChat(int $otherUserId) {

        $userId = auth()->user()->id;

        return Chat::whereHas('participants', function ($query) use ($userId){
                $query->where('user_id',$userId);
            })
            ->whereHas('participants', function ($query) use ($otherUserId){
                $query->where('user_id',$otherUserId);
            })
            ->first();
    }


    public function show(Chat $chat)
    {
        $chat->load('lastMessage.user', 'participants.user');
        return $chat;
    }
}












