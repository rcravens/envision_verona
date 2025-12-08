<?php

namespace App\Http\Controllers;

use App\Code\Ai\ChatBot;
use Illuminate\Http\Request;
use Illuminate\View\View;
use NeuronAI\Chat\Messages\UserMessage;

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

        $userMessage = new UserMessage( $request->message );

        // --- Retrieve existing session messages ---
        $messages = session( 'chat_messages', [] );

        // --- Add user message to history ---
        $messages[] = [
            'role'    => 'user',
            'content' => $request->message,
        ];

        // --- Call your RAG chatbot ---
        $bot        = new ChatBot();
        $aiResponse = $bot->chat( $userMessage );
        $response   = $aiResponse->getContent();
        $response   = nl2br( e( $response ) );


        // --- Add assistant message to history ---
        $messages[] = [
            'role'    => 'assistant',
            'content' => $response,
        ];

        // --- Save updated conversation back to session ---
        session( [ 'chat_messages' => $messages ] );


        return response()->json( [
                                     'messages' => $messages,
                                     'reply'    => $response,
                                 ] );
    }
}
