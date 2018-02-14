<?php
return [
    "sections"    => ['filter', 'search', 'sort'],
    "type"        => ['range', 'where', 'like', 'order', 'in'],
    "comparision" => [
        "divider" => "||",
        "regex"   => "(<[=>]?|>=?|:|;)",
        "filter"  => ["=", "<", ">", "<=", ">=", "~", ";"],
        "search"  => ["="],
        "sort"    => ["="],
    ],
    "default"     => [
        "pageSize" => [
            "basic" => 20,
            "max"   => 100,
        ],
        "sort" => [
            "key" => null,
            "value" => "desc",
        ],
    ],
    "conversion"  => [
        "key"   => [
            "contentId" => [
                "model"    => App\Models\Content::class,
                "relation" => "content",
                "column"   => "id"
            ],
            "featured"  => [
                "model"    => App\Models\Content::class,
                "relation" => "content",
                "column"   => "like_count"
            ],
            "latest"    => [
                "model"    => App\Models\Content::class,
                "relation" => "content",
                "column"   => "created_at"
            ],
            "userId"    => [
                "model"    => App\Models\User::class,
                "relation" => "user",
                "column"   => "id"
            ],
        ],
        "value" => [
            "isNull" => null,
        ]
    ],
];
