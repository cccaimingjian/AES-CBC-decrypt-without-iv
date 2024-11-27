# AES_CBC_decrypt_without_vi
AES CBC decryption operation via a generic key extraction algorithm that generates key and iv without IV parameters. 没有IV参数的AES_CBC解密算法
## PROCESS. 流程  

1. Verify ciphertext(after base64 decode).  
   验证密文格式是否正确（Base64解码后）  
   1. The correct ciphertext string begins with `Salted__`, which is exactly 8 characters long.  
      正确的密文字符串开头是`Salted__`，刚好是8个字符。
   2. Starting with the 8th character is an 8 byte salt value.  
      从第8个字符开始是8个字节的盐值。  
   3. Starting with the 16th character, it's ciphertext.  
      从第16个字符开始是密文  
   4. _The length of the ciphertext is an integer multiple of the length of the salt. **AI speculated, not verified**  
     _密文长度是盐值长度的整数倍。_  **AI推测的，没有验证**_  
   5. _Ciphertext length is an integer multiple of 16._  
   6. _密文长度是16的整数倍。 **AI推测的，没有验证**_
   6. _The ciphertext is at least 16 bytes long._ **AI speculated, not verified**  
     _密文长度至少是16个字节。**AI推测的，没有验证**_
2. Extract salt from ciphertext via `substr()`.  
   从密文中提取盐值。
3. Generate `key` and `iv` via `evpkdf()`.  
   通过`evpkdf()`生成`密钥`和`iv`。
4. Decrypt ciphertext via `openssl_decrypt()`.  
   通过`openssl_decrypt()`解密密文。
### PHP Language level 语言等级
PHP 8.0  
Please modify the code according to your PHP version.  
请跟据自己使用的语言等级进行相应的代码修改。
