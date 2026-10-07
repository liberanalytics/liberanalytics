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

    // Define the structured output schema.
    $semanticSearchSchema = [
        'type' => 'object',
        'properties' => [
            'postCategory' => [
                'type' => ['string', 'null'],
                'enum' => array_merge($postCategories, [null])
            ],
            'postTags' => [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                    'enum' => $postTags
                ]
            ]
        ],
        'required' => [
            'postCategory',
            'postTags'
        ],
        'additionalProperties' => false
    ];

    $nlpRequest = $requestPxs['nlpRequest'];

    $openAiRequest = [
        'model' => 'gpt-5.6-luna',

    'instructions' =>
        'Interpret the user search request using only the supplied LiberAnalytics domain. ' .
        'Treat postCategory as the primary subject or broad topic of the request. ' .
        'Choose a postCategory only when the request clearly centers on one of the allowed categories. ' .
        'Treat postTags as narrower concepts, technologies, methods, or attributes mentioned or clearly implied by the request. ' .
        'Be conservative. If there is no clear semantic match, return null for postCategory and an empty array for postTags.',

        'input' => $nlpRequest,

        'text' => [
            'format' => [
                'type' => 'json_schema',
                'name' => 'semantic_search',
                'strict' => true,
                'schema' => $semanticSearchSchema
            ]
        ]
    ];

    $requestBody = json_encode($openAiRequest);
    $curl = curl_init('https://api.openai.com/v1/responses');
    $openAiKeyFile = $laConfig->getConfigAttr('liberanalytics', 'config', 'env', 'openapikey');
    $openAiApiKey = trim(file_get_contents($openAiKeyFile));

    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $openAiApiKey,
            'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS => $requestBody
    ]);

    $openAiResponse = curl_exec($curl);
    if ($openAiResponse === false) {
        http_response_code(500);
        return [
            'error' => 'Semantic search request failed.'
        ];
    }

    $openAiResponseArray = json_decode($openAiResponse, true);
    $semanticSearchResponseJson = $openAiResponseArray['output'][1]['content'][0]['text'] ?? null;
    $semanticSearchResponseJson = null;

    foreach ($openAiResponseArray['output'] ?? [] as $outputItem) {

        if (
            ($outputItem['type'] ?? null) === 'message'
            && isset($outputItem['content'][0]['text'])
        ) {
            $semanticSearchResponseJson =
                $outputItem['content'][0]['text'];

            break;
        }
    }

    $semanticSearchResponse =
        $semanticSearchResponseJson
            ? json_decode($semanticSearchResponseJson, true)
            : null;

    if (!is_array($semanticSearchResponse))
    {
        http_response_code(502);
        return [
            'error' => 'Invalid semantic search response.'
        ];
    }

    return [
        'postCategory' => $semanticSearchResponse['postCategory'] ?? null,
        'postTags' => $semanticSearchResponse['postTags'] ?? []
    ];

?>

