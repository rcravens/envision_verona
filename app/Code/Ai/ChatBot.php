<?php

namespace App\Code\Ai;


use NeuronAI\Chat\Enums\MessageRole;
use NeuronAI\Chat\Messages\Message;
use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\OpenAI\OpenAI;
use NeuronAI\RAG\Embeddings\EmbeddingsProviderInterface;
use NeuronAI\RAG\Embeddings\OpenAIEmbeddingsProvider;
use NeuronAI\RAG\RAG;
use NeuronAI\RAG\VectorStore\FileVectorStore;
use NeuronAI\RAG\VectorStore\VectorStoreInterface;

class ChatBot extends RAG
{
    private string      $api_key;
    private string      $model = 'gpt-4o-mini';
    private VectorStore $vector_store;

    public function __construct( ?string $api_key = null )
    {
        $this->api_key      = $api_key ?? config( 'openai.api_key' );
        $this->vector_store = new VectorStore( 'verona' );
    }

    public function ask( string $question, array $history = [] ): ?string
    {
        $persona = "You are Verona Advisor AI, an expert on all things related to Verona, Italy.
                Always answer as if advising the mayor and city council. Be concise, professional,
                and cite relevant knowledge from your document base when possible.";

        $messages = [ new Message( MessageRole::SYSTEM, $persona ) ];

        foreach ( $history as $m )
        {
            if ( $m[ 'role' ] === 'user' )
            {
                $messages[] = new UserMessage( $m[ 'content' ] );
            }
            elseif ( $m[ 'role' ] === 'assistant' )
            {
                $messages[] = new Message( MessageRole::ASSISTANT, $m[ 'content' ] );
            }
        }
        $messages[] = new UserMessage( $question );
        
        try
        {
            $result   = $this->chat( $messages );
            $response = $result->getContent();
        }
        catch( \Throwable $e )
        {
            dd( $e, $question );

            return null;
        }

        return $response;
    }

    protected function provider(): AIProviderInterface
    {
        return new OpenAI( $this->api_key, $this->model );
    }

    protected function embeddings(): EmbeddingsProviderInterface
    {
        return new OpenAIEmbeddingsProvider( key: $this->api_key, model: VectorStore::$embedding_model, dimensions: VectorStore::$expected_dimensions );
    }

    protected function vectorStore(): VectorStoreInterface
    {
        $store_file = $this->vector_store->store_file();

        if ( ! file_exists( $store_file ) || filesize( $store_file ) === 0 )
        {
            file_put_contents( $store_file, '' );
        }

        return new FileVectorStore( directory: $this->vector_store->vector_dir(), topK: 3, name: $this->vector_store->store() );
    }
}
