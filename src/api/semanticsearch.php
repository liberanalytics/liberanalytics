<?php

    require_once(dirname(__DIR__, 2) . "/" . 'vendor/autoload.php');

    use classes\LaConfig;
    use classes\LaData;
    use enums\CrudOperation;
    use enums\CrudType;
    use enums\CrudResultsetType;
    use enums\CrudFetchStyle;

    $laConfig = new LaConfig();
    $laData = new LaData();

    // Get the liber authoritative post category domain data.
    $postCategories = $laData->crudData(
        CrudOperation::GETPOSTCATEGORIES,
        CrudType::CRUDREAD,
        CrudResultsetType::RESULTSET_ROWS,
        CrudFetchStyle::FETCHSTYLE_COL
    );

    // Get the liber authoritative tags domain data.
    $postTags = $laData->crudData(
        CrudOperation::GETPOSTTAGS,
        CrudType::CRUDREAD,
        CrudResultsetType::RESULTSET_ROWS,
        CrudFetchStyle::FETCHSTYLE_COL
    );

    // Define the domain of acceptable response values
    // and the structured output schema.
    $semanticSearchSchema = [
        'type' => 'object',
        'properties' => [
            // Define the type and value for post category that
            // can be returned, including null.
            'postCategory' => [
                'type' => ['string', 'null'],
                'enum' => array_merge($postCategories, [null])
            ],
            // Define the type and set of post tag values that
            // can be returned.           
            'postTags' => [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                    'enum' => $postTags
                ]
            ]
        ],
        // Returned objects must contain the postCategory
        // and postTags properties.
        'required' => [
            'postCategory',
            'postTags'
        ],
        // Don't return any other properties.
        'additionalProperties' => false
    ];

    // Get the user's search prompt request.
    $userSearchPromptRequest = $requestPxs['userSearchPromptRequest'];

    // Define open ai responses api parameters.
    $openAiRequest = [

        // Define the OpenAI model used for semantic interpretation.
        'model' => 'gpt-5.6-luna',

        // Guide how the LLM should interpret the user's search prompt
        // within LiberAnalytics' allowed semantic domain, including
        // the expected response behavior when no clear match exists.
        'instructions' =>
            'Interpret the user search request using only the supplied LiberAnalytics domain. ' .
            'Treat postCategory as the primary subject or broad topic of the request. ' .
            'Choose a postCategory only when the request clearly centers on one of the allowed categories. ' .
            'Treat postTags as narrower concepts, technologies, methods, or attributes mentioned or clearly implied by the request. ' .
            'Be conservative. If there is no clear semantic match, return null for postCategory and an empty array for postTags.',

        // Supply the user's submitted search prompt.
        'input' => $userSearchPromptRequest,

        // Define the model's text output as structured JSON using a
        // JSON Schema named semantic_search, with strict adherence to
        // the $semanticSearchSchema contract.
        'text' => [
            'format' => [
                'type' => 'json_schema',
                'name' => 'semantic_search',
                'strict' => true,
                'schema' => $semanticSearchSchema
            ]
        ]
    ];

    // Define the Open AI Responses API call body, url, and key file.
    $requestBody = json_encode($openAiRequest);
    $curl = curl_init('https://api.openai.com/v1/responses');
    $openAiKeyFile = $laConfig->getConfigAttr('liberanalytics', 'config', 'env', 'openapikey');
    $openAiApiKey = trim(file_get_contents($openAiKeyFile));

    // Set curl session options, supply the request body.
    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $openAiApiKey,
            'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS => $requestBody
    ]);

    // Execute the cURL request; return 502 if the upstream request fails.
    $openAiResponse = curl_exec($curl);
    if ($openAiResponse === false) {
        http_response_code(502);
        return [
            'error' => 'Semantic search request failed.'
        ];
    }

    // Iterate through ai response array; store content from the 
    // message->content->output_text->text.
    $openAiResponseArray = json_decode($openAiResponse, true);
    $semanticSearchResponseJson = null;

    // Iterate through ai response array; store relevant content from
    // contentItem[]
    foreach ($openAiResponseArray['output'] ?? [] as $outputItem) {

        // Inspect content only for outputItems which are actual messages.
        if (($outputItem['type'] ?? null) !== 'message') {
            continue;
        }

        // Find the text for the given content member.
        foreach ($outputItem['content'] ?? [] as $contentItem) {

            if (
                ($contentItem['type'] ?? null) === 'output_text'
                && isset($contentItem['text'])
            ) {
                $semanticSearchResponseJson = $contentItem['text'];
                break 2;
            }
        }
    }

    // Treat the response as an associative array.
    $semanticSearchResponse =
        $semanticSearchResponseJson
            ? json_decode($semanticSearchResponseJson, true)
            : null;

    // Return 502 if the extracted semantic search response is unusable.
    if (!is_array($semanticSearchResponse))
    {
        http_response_code(502);
        return [
            'error' => 'Invalid semantic search response.'
        ];
    }

    // Validate the response data is of authoritative membership.
    $response = [
        'postCategory' => $semanticSearchResponse['postCategory'] ?? null,
        'postTags' => $semanticSearchResponse['postTags'] ?? []
    ];

    // Make sure the post category is in the authoritative membership domain.
    if (
        $response['postCategory']
        && !in_array($response['postCategory'], $postCategories, true)
    ) {
        http_response_code(502);
        return [
            'error' => 'Invalid post category.'
        ];
    }

    // Make sure the post tags are in the authoritative membership domain.
    $invalidPostTags = array_diff($response['postTags'], $postTags);
    if ($invalidPostTags) {
        http_response_code(502);
            return [
                'error' => 'Invalid post tag(s).'
            ];
    }

    // Return the response data as name => value pairs.
    return $response;

?>

