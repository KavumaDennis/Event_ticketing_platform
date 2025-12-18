<?php
// Function to generate UUID v4
function generate_uuid_v4() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

// Step 1: Generate UUID
$uuid = generate_uuid_v4();

// MTN Subscription Key
$subscriptionKey = "a174859604654f689a7573b1c2e5e962";

// Step 2: Create API user
$headers = [
    "Content-Type: application/json",
    "X-Reference-Id: $uuid",
    "Ocp-Apim-Subscription-Key: $subscriptionKey"
];

$body = json_encode([
    "providerCallbackHost" => "https://yourdomain.com/momo/callback"
]);

$ch = curl_init("https://sandbox.momodeveloper.mtn.com/v1_0/apiuser");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

echo "API User Created. UUID: $uuid\n";

// Step 3: Wait before generating API key
sleep(10); // Wait 10 seconds for sandbox to register the user

// Step 4: Generate API key with retry logic
$maxRetries = 5;
$attempt = 0;
do {
    $attempt++;
    $ch2 = curl_init("https://sandbox.momodeveloper.mtn.com/v1_0/apiuser/$uuid/apikey");
    curl_setopt($ch2, CURLOPT_POST, true);
    curl_setopt($ch2, CURLOPT_HTTPHEADER, [
        "Ocp-Apim-Subscription-Key: $subscriptionKey"
    ]);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    $apiKeyResponse = curl_exec($ch2);
    curl_close($ch2);

    if (strpos($apiKeyResponse, 'apiKey') !== false) break; // Success

    echo "Retry $attempt: API key not ready. Waiting 5 seconds...\n";
    sleep(5);

} while ($attempt < $maxRetries);

echo "API Key Response: $apiKeyResponse\n";
