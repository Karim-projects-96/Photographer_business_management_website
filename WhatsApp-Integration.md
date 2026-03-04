# WhatsApp Communication Strategy

## 1. Zero-Cost Implementation
Instead of expensive API providers (like Twilio), we utilize **WhatsApp Universal Links** to trigger messages from the photographer's browser/phone.

## 2. Integration Logic (PHP)
```php
function generateWhatsAppLink($phone, $message) {
    $encodedMsg = urlencode($message);
    return "[https://wa.me/](https://wa.me/){$phone}?text={$encodedMsg}";
}