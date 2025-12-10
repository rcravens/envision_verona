<?php

namespace App\Code\Ai;


use NeuronAI\Chat\Enums\MessageRole;
use NeuronAI\Chat\Messages\Message;
use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\OpenAI\Responses\OpenAIResponses;
use NeuronAI\RAG\Embeddings\EmbeddingsProviderInterface;
use NeuronAI\RAG\Embeddings\OpenAIEmbeddingsProvider;
use NeuronAI\RAG\RAG;
use NeuronAI\RAG\VectorStore\FileVectorStore;
use NeuronAI\RAG\VectorStore\VectorStoreInterface;
use NeuronAI\SystemPrompt;

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
        // Messages
        //
        $messages = [];

        // Retrieve RAG context with a score
        //
        $retrieved = $this->retrieveContext( $question );
        $use_rag   = $retrieved->score > 0.5;

        // Create the context block with or without the RAG context
        //
        if ( $use_rag )
        {
            $context_block = "Use the following Verona knowledge base context as your primary source.
                             If needed, add general knowledge.\n\n---\n{$retrieved->context}\n---";
            $messages[]    = new Message( MessageRole::SYSTEM, $context_block );
        }

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

    public function instructions(): string
    {
        return (string) new SystemPrompt(
            background: [
                            "You are Verona Advisor AI, an expert on all things related to Verona, Wisconsin.",
                            "Be concise, professional, and cite relevant knowledge from your document base when possible.",
                            "You must ALWAYS cite your sources at the end of every response.",
                            "Sources may include: RAG document names, document excerpts, URLs, or any retrieved context blocks.",
                            "If no RAG context is used, cite: 'General Knowledge.'",
                            "Never answer without a citations section.",
                            "You may output HTML for formatting. Do NOT escape HTML tags.",
                            "Only the following HTML tags are allowed: <p>, <br>, <strong>, <em>, <h3>, <h4>, <ul>, <li>.",
                            "Never output &lt; or &gt; when writing actual HTML tags.",
                            "If HTML is not needed, plain text is fine."
                        ],
            steps     : [
                            "Retrieve relevant information from the document base.",
                            "Analyze the question and provide a concise and professional response.",
                            "Always include a citations section at the end using <p> or <ul> formatting."
                        ],
            output    : [
                            "Write the main answer inside <p> or <div> elements as appropriate.",
                            "After the summary, provide any additional details in HTML.",
                            "Finish with a <h4>Sources</h4> section and an HTML <ul> list of citations.",
                            "Never escape HTML tags and never output raw angle-bracket entities.",
                        ]
        );
    }

    protected function provider(): AIProviderInterface
    {
        return new OpenAIResponses( $this->api_key, $this->model );
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

    private function retrieveContext( string $question ): \stdClass
    {
        $result           = new \stdClass();
        $result->question = $question;
        $result->context  = '';
        $result->score    = 0;


        $vector_store = $this->vectorStore();
        $emb          = $this->embeddings()->embedText( $question );

        $results = $vector_store->similaritySearch( $emb );

        if ( ! empty( $results ) )
        {
            $result->context = implode( "\n\n", array_column( $results, 'content' ) );
            $result->score   = array_sum( array_column( $results, 'score' ) ) / count( $results );
        }

        return $result;
    }

}
