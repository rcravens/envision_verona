<?php

namespace App\Code\Ai;


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
