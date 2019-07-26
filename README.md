## Installation 
```
composer require salamwaddah/laravel-mandrill-driver
```

## Configure
Add into your .env file
```
MANDRILL_SECRET=YOUR_MANDRILL_API_KEY
```
In your `mail.php` file

```
'from' => [
    'address' => 'noreply@example.com',
    'name' => "From Name"
],

'mandrill' => [
    'key' => env('MANDRILL_SECRET', 'SUPER SECRET KEY')
]
```

## Usage

#### Basic usage
```php
public function via($notifiable)
{
    return [MandrillChannel::class];
}

public function toMandrill($notifiable)
{
    return (new MandrillMessage())
        ->subject('Purchase successful')
        ->addTo($notifiable->email)
        ->view('mandrill-template-name', [
            'product' => $this->product->toArray(),
            'user' => [
                'name' => $notifiable->name,
                'phone' => $notifiable->phone
            ]
        ]);
}
```
#### Advanced
```php
public function toMandrill($notifiable)
{
    return (new MandrillMessage())
        ->subject('Purchase successful')
        ->templateName('mandrill-template-name')
        ->addTo($notifiable->email)
        ->addTos(['a@example.com', 'b@example.com'])
        ->fromName('Customized From')
        ->fromEmail('custom_from@example.com')
        ->content([
            'product' => $this->product->toArray(),
        ])
}
```

## Available methods
|Method|Type|Description|
|------|----|----|
|`subject`|`string`|Sets the email subject| 
|`templateName`|`string`|Sets template name in Mandrill|
|`addTo`|`string`|Adds a To email| 
|`addTos`|`array`|Adds multiple To emails|
|`fromName`|`string`|Overrides the default from name|
|`fromEmail`|`string`|Overrides the default from email|
|`content`|`array`|Content array| 
|`view`|`function`|Accepts 2 params (`templateName`, `keyedContentArray`)|

## Usage in Mandrill
When specifying your content in the methods `content` or `view` you can then write in [handlebars syntax](https://mandrill.zendesk.com/hc/en-us/articles/205582537-Using-Handlebars-for-Dynamic-Content) in your Mandrill templates like this; 

Hey `{{user.name}}`, you have successfully purchased `{{product.name}}`.
