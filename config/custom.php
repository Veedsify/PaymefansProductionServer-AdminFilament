<?php

return [
    "app_name" => "PayMeFans",
    "app_version" => "1.0.0",

    // AWS
    "aws_access_key_id" => env("AWS_ACCESS_KEY_ID"),
    "aws_secret_access_key" => env("AWS_SECRET_ACCESS_KEY"),
    "aws_region" => env("AWS_DEFAULT_REGION"),
    "aws_bucket_name" => env("AWS_BUCKET"),
    "aws_path" => env("AWS_USE_PATH_STYLE_ENDPOINT"),
    "aws_cloudfront_url" => env("AWS_CLOUDFRONT_URL"),

    // Cloudflare
    "cloudflare_api_token" => env("CLOUDFLARE_API_TOKEN"),
    "cloudflare_account_id" => env("CLOUDFLARE_ACCOUNT_ID"),
    "cloudflare_customer_subdomain" => env("CLOUDFLARE_CUSTOMER_SUBDOMAIN"),

    // BackendURl
    "backend_url" => env("BACKEND_URL"),
    "express_api_base_url" => env("EXPRESS_API_BASE_URL"),

    //Paystack
    "paystack_public_key" => env("PAYSTACK_PUBLIC_KEY"),
    "paystack_secret_key" => env("PAYSTACK_SECRET_KEY"),
];
