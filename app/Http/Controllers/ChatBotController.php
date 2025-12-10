<?php

namespace App\Http\Controllers;

use App\Code\Ai\ChatBot;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatBotController extends Controller
{
    public function chat( Request $request ): View
    {
        $messages = session( 'chat_messages', [] );

        return view( 'chat.index', compact( 'messages' ) );
    }

    public function clear()
    {
        session()->forget( 'chat_messages' );

        return response()->json( [ 'success' => true ] );
    }

    public function get_response( Request $request )
    {
        $request->validate( [
                                'message' => 'required|string'
                            ] );

        $messages = session( 'chat_messages', [] );

        $bot      = new ChatBot();
        $response = $bot->ask( $request->message, $messages );
//        $response = nl2br( e( $response ) );

        $messages[] = [
            'role'    => 'user',
            'content' => $request->message,
        ];

        $messages[] = [
            'role'    => 'assistant',
            'content' => $response,
        ];
        session( [ 'chat_messages' => $messages ] );

        return response()->json( [
                                     'messages' => $messages,
                                     'reply'    => $response,
                                 ] );
    }
}
