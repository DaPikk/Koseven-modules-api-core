# API Core Module for Kohana/Koseven (PHP 5.6 - 8.3 Compatible)

This module provides a RESTful API base for Kohana 3.3.6 and Koseven 3.3.10 with broad PHP compatibility (5.6 through 8.3). It supports:

* REST verbs: `GET`, `POST`, `PUT`, `DELETE`
* Output formats: `json`, `jsonp`, `xml`, `txt`
* Authorization via Bearer tokens
* IP/Referer-based access control
* CORS support
* HTTPS enforcement
* Input sanitization
* Audit logging

---

## 📁 Module Structure

```
modules/api-core/
├── classes/
│   ├── API/
│   │   ├── Auth.php          # Authorization and IP/referer checks
│   │   ├── Response.php      # Output formatting
│   │   ├── CORS.php          # CORS header management
│   │   ├── Log.php           # Audit logging
│   │   ├── Sanitize.php      # Input sanitization
│   │   └── Security.php      # HTTPS enforcement
│   └── Controller/
│       └── Api/
│           └── V1.php        # Central API router
├── config/
│   └── api-core.php               # Configuration file
├── init.php                  # Route definition
```

---

## 🔧 Installation

1. Copy `api-core` to your `modules/` directory.
2. Enable it in `application/bootstrap.php`:

```php
Kohana::modules([
    'api-core' => MODPATH.'api-core',
    // other modules...
]);
```

3. Modify `config/api-core.php` as needed:

```php
return [
    'allowed_ips' => ['127.0.0.1', '::1'],
    'allowed_referers' => ['example.com', 'localhost'],
    'tokens' => ['demo_token'],
    'allowed_origins' => [],
    'enforce_https' => true,
];
```

---

## 🧪 Example Usage

### URL Patterns:

* `GET /api/v1/users.json`
* `POST /api/v1/orders.xml`
* `PUT /api/v1/products/12.json`
* `DELETE /api/v1/items/44.txt`
* `GET /api/v1/customers.jsonp?callback=render`

### Authorization Header:

```
Authorization: Bearer demo_token
```

---

## ✅ Features Summary

| Feature              | Status |
| -------------------- | ------ |
| REST verb routing    | ✅      |
| Output formatting    | ✅      |
| Token auth           | ✅      |
| IP/Referer whitelist | ✅      |
| CORS support         | ✅      |
| HTTPS enforcement    | ✅      |
| Input sanitization   | ✅      |
| Logging              | ✅      |
| PHP 5.6 compatible   | ✅      |

---

## 📌 Notes

* Ensure your `.htaccess` or server config supports routing to `index.php`.
* For PUT/DELETE, clients must send the correct `Content-Type` and raw body.
* Customize resource controllers under `modules/your_module/classes/Controller/Api/YourResource.php`.

---

## 🧩 Extending

Create custom API modules using:

```php
class Controller_Api_Users extends Controller {
    public function get() {
        $data = ['users' => [...]];
        API_Response::send($data, Request::current()->param('format', 'json'));
    }
    // post, put, delete ...
}
```

---

For help, contact the maintainer or open a ticket in your project repo.

