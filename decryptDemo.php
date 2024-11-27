<?php
/**
 * generate key and iv for AES encryption.
 * 生成AES加密的密钥和iv
 * @param $password string 密码
 * @param $salt string 盐
 * @param $keySize int 密钥长度
 * @param $ivSize int iv长度
 * @param $iterations int 迭代次数
 * @param $hash string 哈希算法
 * @return array 返回密钥和iv
 * @throws RuntimeException
 */
#[\JetBrains\PhpStorm\ArrayShape(['key' => "string", 'iv' => "string"])]
function evpkdf(string $password, string $salt, int $keySize = 32, int $ivSize = 16, int $iterations = 1, string $hash = 'md5'): array
{
    $key_iv = '';
    $block = '';
    if (!in_array($hash, hash_algos(), true)) {
        throw new RuntimeException("Hash algorithm $hash not supported.");
    }
    while (strlen($key_iv) < $keySize + $ivSize) {
        $block = hash($hash, $block . $password . $salt, true);
        for ($i = 1; $i < $iterations; $i++) {
            $block = hash($hash, $block, true);
        }
        $key_iv .= $block;
    }
//    var_dump(bin2hex($key_iv));//DEBUG
    return [
        'key' => substr($key_iv, 0, $keySize),
        'iv' => substr($key_iv, $keySize, $ivSize),
    ];
}

/**
 * Decrypt AES encrypted data without iv. AES解密加密数据，不需要iv
 * @param string $encryptedData 密文（base64编码）
 * @param string $password 密码
 * @param bool $isBase64Encoded 密文是否是base64编码
 * @return bool|string
 * @throws RuntimeException
 */
function decryptAes(string $encryptedData, string $password,bool $isBase64Encoded = true): bool|string
{
    $decodedData = $isBase64Encoded ? base64_decode($encryptedData) : $encryptedData;
    if (!str_starts_with($decodedData, "Salted__"))//demo PHP8.0+
//        if (substr($decodedData, 0, 8) !== "Salted__")//if you are using PHP7.4+
            throw new RuntimeException("Error: Invalid ciphertext format. The ciphertext may not be applicable to this algorithm ");

    $salt = substr($decodedData, 8, 8);
    $ciphertext = substr($decodedData, 16);
    $key_iv = evpkdf($password, $salt);
    $key = $key_iv['key'];
    $iv = $key_iv['iv'];
    return openssl_decrypt($ciphertext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
}
//Test
$encryptedData = "U2FsdGVkX18OXPtxnMEHa0vOjDYf/hmRwhRdMCklbu0v+NWby3PIprAM15EttS4QdMyN9EYcqlPwe68qKLLLztvmKCdhDipKyqykkdYJ96M=";
$password = "AES-CBC-decrypt-without-iv";
//Output: Decrypted text: https://github.com/cccaimingjian/AES-CBC-decrypt-without-iv
echo "Decrypted text: " . decryptAes($encryptedData, $password) . PHP_EOL;
//another test
$encryptedData = "U2FsdGVkX1/soU7FOzBRN+D0t8qB/1kS3UGfrsmzu40=";
$password = "World";
//Output: Decrypted text: hello
echo "Decrypted text: " . decryptAes($encryptedData, $password) . PHP_EOL;

