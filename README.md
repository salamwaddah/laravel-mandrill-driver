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
|`view`|`function`|Accepts 2 params (`$templateName`, `$keyedContentArray`)|

## Usage in Mandrill (Dynamic Handlebars)
When specifying your content in the methods `content` or `view` you can then write in [handlebars syntax](https://mandrill.zendesk.com/hc/en-us/articles/205582537-Using-Handlebars-for-Dynamic-Content) in your Mandrill templates like this; 

Hey `{{user.name}}`, you have successfully purchased `{{product.name}}`.

## Mailchimp syntax
If you wish to use [Mailchimp Merge Tags](https://mandrill.zendesk.com/hc/en-us/articles/205582787-Mailchimp-Merge-Tags-Supported-in-Mandrill) instead of the dynamic handlebars then you can set the `$mergeLanguage` optional param in `templateName` method to `mailchimp`.

In mailchimp merge tags, arrays are not supported, so each tag only accepts a string. [Full documentation including booked keywords on mandrill](https://mandrill.zendesk.com/hc/en-us/articles/205582787-Mailchimp-Merge-Tags-Supported-in-Mandrill)
#### Mailchimp Example
```php
public function toMandrill($notifiable)
{
    return (new MandrillMessage())
        ->subject('Purchase successful')
        ->templateName('mandrill-template-name', 'mailchimp') << HERE
        ->addTo($notifiable->email)
        ->content([
            'customer_name' => $notifiable->name,
            'invoice_link' => 'http://example.com/download/invoice.pdf',
        ])
}
```
Then in your mandrill template use as follows;

Hi `*|customer_name|*`, you can download your invoice from here `*|invoice_link|*`, 