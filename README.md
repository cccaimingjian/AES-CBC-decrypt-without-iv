# AES_CBC_decrypt_without_iv
AES CBC decryption operation via a generic key extraction algorithm that generates key and iv without IV parameters. 没有IV参数的AES_CBC解密算法。demo提供实现叫EVP_Bytestokey，现在已经不推荐使用这个算法生成加密，只用来解密已经加密出来的密文（使用场景通常是JS使用crypto加密出来的）  
## PROCESS. 流程  

1. Verify ciphertext(after base64 decode).  
   验证密文格式是否正确（Base64解码后）  
   1. The correct ciphertext string begins with `Salted__`, which is exactly 8 characters long.  
      正确的密文字符串开头是`Salted__`，刚好是8个字符。
   2. Starting with the 8th character is an 8 byte salt value.  
      从第8个字符开始是8个字节的盐值。  
   3. Starting with the 16th character, it's ciphertext.  
      从第16个字符开始是密文  
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
### Reference  
AES加密的CBC模式中，通过密码推算出KEY和IV的算法通常被称为“密钥派生函数” (Key Derivation Function, KDF)。以下是几个常用的 KDF 算法及其特点：  
1. PBKDF2 (Password-Based Key Derivation Function 2)
标准化算法（定义于 RFC 8018）。
输入密码、盐值 (Salt) 和迭代次数，生成固定长度的密钥。
适合从低熵密码生成高熵密钥，迭代次数增加破解成本。
示例：用密码生成 AES 的密钥和 IV（通常分配密钥前 n 位和剩余部分）。
2. HKDF (HMAC-based Extract-and-Expand Key Derivation Function)
标准化算法（定义于 RFC 5869）。
使用 HMAC 提供提取和扩展功能，将输入密钥材料（IKM）生成输出密钥材料（OKM）。
比 PBKDF2 更灵活，适合多用途密钥派生。
3. bcrypt
密码散列函数，也可用作 KDF。
加入盐值并使用多轮密集计算（基于 Blowfish 加密算法）。
比 PBKDF2 更慢，但安全性更高。
4. scrypt
专为防止硬件加速攻击设计的 KDF。
使用内存硬性要求，通过消耗大量内存和计算资源提高破解难度。
常用于生成高熵密钥。
