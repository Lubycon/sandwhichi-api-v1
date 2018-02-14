<?php
return [
//↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//      2xx response
//↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
"0010" => (object)array(
    "httpCode" => 201,
    "msg" => "Resulting in the creation of a new resource."
),
"0011" => (object)array(
    "httpCode" => 202,
    "msg" => "Accepted for processing, but the processing has not been completed."
),
// Removed protocol
//"0012" => (object)array(
//    "httpCode" => 204,
//    "msg" => "Login Failure"
//),
"0013" => (object)array(
    "httpCode" => 204,
    "msg" => "Verification code not match"
),
"0014" => (object)array(
    "httpCode" => 204,
    "msg" => "Successfully processed the request and is not returning any content."
),
"0015" => (object)array(
    "httpCode" => 208,
    "msg" => "Already been enumerated in a previous reply to this request."
),
//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
//      2xx response
//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

//↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//      4xx response
//↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
"0040" => (object)array(
    "httpCode" => 400,
    "msg" => "Bad request"
),
"0041" => (object)array(
    "httpCode" => 401,
    "msg" => "Login is required"
),
"0042" => (object)array(
    "httpCode" => 401,
    "msg" => "Authentication is required"
),
"0043" => (object)array(
    "httpCode" => 403,
    "msg" => "User permission denied"
),
"0044" => (object)array(
    "httpCode" => 404,
    "msg" => "Not Found Http Exception"
),
"0045" => (object)array(
    "httpCode" => 408,
    "msg" => "Time out"
),
"0046" => (object)array(
    "httpCode" => 409,
    "msg" => "Request Conflict"
),
"0047" => (object)array(
    "httpCode" => 411,
    "msg" => "Some Header Required"
),
"0048" => (object)array(
    "httpCode" => 413,
    "msg" => "Payload Too Large"
),
"0049" => (object)array(
    "httpCode" => 414,
    "msg" => "URI Too Long"
),
"0050" => (object)array(
    "httpCode" => 415,
    "msg" => "Unsupported Media Type"
),
"0051" => (object)array(
    "httpCode" => 422,
    "msg" => "Unprocessable Validation"
),
"0052" => (object)array(
    "httpCode" => 424,
    "msg" => "Failed Dependency"
),
"0053" => (object)array(
    "httpCode" => 429,
    "msg" => "Too Many Requests"
),
"0054" => (object)array(
    "httpCode" => 404,
    "msg" => "Resource Was Not Found"
),
"0055" => (object)array(
    "httpCode" => 405,
    "msg" => "Method Not Allowed"
),
"0056" => (object)array(
    "httpCode" => 404,
    "msg" => "Crawling Data Not Found"
),
"0057" => (object)array(
    "httpCode" => 402,
    "msg" => "Failed request"
),
"0058" => (object)array(
    "httpCode" => 400,
    "msg" => "Sku Have No Stock"
),
"0059" => (object)array(
    "httpCode" => 400,
    "msg" => "Resource expiry"
),
"0060" => (object)array(
    "httpCode" => 400,
    "msg" => "Invalid Currency Rate"
),
"0061" => (object)array(
    "httpCode" => 401,
    "msg" => "Login Failure"
),
"0062" => (object)array(
    "httpCode" => 419,
    "msg" => "Token Expired"
),
//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
//      4xx response
//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

//↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//      5xx response
//↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

"0070" => (object)array(
    "httpCode" => 500,
    "msg" => "Fatal server error"
),
"0071" => (object)array(
    "httpCode" => 500,
    "msg" => "Upload Failed with contents server"
),
"0072" => (object)array(
    "httpCode" => 500,
    "msg" => "Sent mail Failure"
),
"0073" => (object)array(
    "httpCode" => 502,
    "msg" => "Bad Gateway"
),
"0074" => (object)array(
    "httpCode" => 503,
    "msg" => "Service Unavailable temporary down"
),
"0075" => (object)array(
    "httpCode" => 507,
    "msg" => "Insufficient Storage"
),

//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
//      5xx response
//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


"9999" => (object)array(
    "httpCode" => 500,
    "msg" => "Fatal server error"
),
];
