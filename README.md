# secure-eloquent

Encrypt your Eloquent model by user-provide key (with AES-128-CBC algorithm).

## Installation

To get started, install `secure-eloquent` via Composer:

    composer require rway7/secure-eloquent
    
Next, add the `rway7\SecureEloquent\HasSecrets` trait to the model you want to encrypt.
This trait will give you the ability to encrypt specified attributes:

~~~php
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
~~~

Finally, add a `is_secured` column to your table:

~~~php
Schema::create('posts', function (Blueprint $table) {
    // ...
    $table->boolean('is_secured')->default(false);
});
~~~

## Getting Started

Once a model has added the `HasSecrets` trait, you can use the `secure` and `unsecure` method:

The `secure` and `unsecure` method will only affect the attributes that are specified in `$secrets` 
property.
These method will also update the `is_secured` attribute,
There's also a method called `secured` which allows you to determine whether this model is encrypted.

Example:
~~~php
// Make a new post.
$post = new Post([
    'title' => 'Title',
    'body' => 'Body',
]);

// Encrypt and save the post.
$post->secure('encryption-key');
$post->save();

$post->title;       // eyJpdiI6IndFTWFZTUNDT2t...
$post->body;        // eyJpdiI6IkJ4ZThwNE4zSWd...
$post->is_secured;  // true
$post->secured();   // true

// Decrypt the post.
$post->unsecure('encryption-key');

$post->title;       // Title
$post->body;        // Body
$post->is_secured;  // false
$post->secured();   // false
~~~

> Note: If you `save` the model when it's unsecured, it WILL NOT be encrypted when save to the database.
