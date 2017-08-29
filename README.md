# secure-eloquent

Encrypt your Eloquent model by user-provide key (with AES-128-CBC algorithm).

## Installation

To get started, install `secure-eloquent` via Composer:

    composer require rway7/secure-eloquent

Next, add the `rway7\SecureEloquent\HasSecrets` trait to the model you want to encrypt.
This trait will give you the ability to encrypt specified attributes:

```php
<?php

namespace App;

use rway7\SecureEloquent\HasSecrets;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasSecrets;

    /**
     * The attributes that need to be encrypted.
     *
     * @var array
     */
    protected $secrets = [
        'title', 'body',
    ];
}
```

Finally, add a `is_secured` column to your table:

```php
Schema::create('posts', function (Blueprint $table) {
    // ...
    $table->boolean('is_secured')->default(false);
});
```

## Getting Started

Once a model has added that trait, you will be able to use `secure` and `unsecure` methods.

These methods will only affect the attributes that are specified in `$secrets`
property, and it will update the model's `is_secured` attribute to indicates whether the model is encrypted.

#### Encrypt a model

```php
$post->secure('encryption-key');

$post->title;   // eyJpdiI6IndFTWFZTU...
$post->body;    // eyJpdiI6IkJ4ZThwNE...

$post->save();
```

> Note: It WILL NOT be encrypted if you `save` a model wihtout calling the `secure` method.

#### Decrypt a model

```php
$post->unsecure('encryption-key');

$post->title;   // Title
$post->body;    // Body
```

#### Determine if a model is encrypted

There's also a `secured` method let you determine if that model is encrypted:

```php
$post->secured();   // true
```

