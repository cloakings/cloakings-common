Cloakings Common Library
========================

Detect if user is bot or real user.

## Install

```bash
composer require cloakings/cloakings-common
```

## Usage

### Basic Usage

If there is library that fits your needs and implements `CloakerInterface` - use it.

```php
use Cloakings\CloakingsCommon\CloakerFactory;
use Cloakings\CloakingsCommon\CloakModeEnum;
use Cloakings\CloakingsCommon\SampleCloaker;

$cloaker = new SampleCloaker();
// or
$cloaker = (new CloakerFactory())->create(SampleCloaker::class);

$result = $cloaker->handle(new Request(server: ['HTTP_USER_AGENT' => 'Chrome 100']));
// $result->mode === CloakModeEnum::Real
// show real site

$result = $cloaker->handle(new Request(server: ['HTTP_USER_AGENT' => 'GoogleBot', 'REMOTE_ADDR' => '8.8.8.8']));
// $result->mode === CloakModeEnum::Fake
// show fake site

$result = $cloaker->handle(new Request(server: ['HTTP_USER_AGENT' => 'GoogleBot', 'REMOTE_ADDR' => '11.22.33.44']));
// $result->mode === CloakModeEnum::Response
// stop and show this response

$result = $cloaker->handle(new Request()); // no user agent
// $result->mode === CloakModeEnum::Error
// decide by yourself: may be show default (fake or real) site, may be try chaining decision to another cloaker
```

### Chaining

If you want visitor to pass several cloakers then use `ChainCloaker`

```php
use Cloakings\CloakingsCommon\AlwaysRealCloaker;
use Cloakings\CloakingsCommon\CloakerFactory;
use Cloakings\CloakingsCommon\SampleCloaker;

$cloaker = (new CloakerFactory())->createChain([
    AlwaysRealCloaker::class,
    SampleCloaker::class,
]);
```

Logic:
- if cloaker tells "Fake" - stop and return "Fake"
- if cloaker tells "Response" - stop and return "Response"
- if cloaker tells "Real" - continue to the next cloaker;
  return last "Real" response
- if cloaker tells "Error" - continue to the next cloaker;
  return last "Real" response;
  if all cloakers return "Error" then return last "Error"

## Create own Cloaker

Implement `CloakerInterface`.

Return `mode`:
- "Real": it seems that visitor is real human user
- "Fake": it seems that visitor is bot user
- "Response": don't show fake or real site but this response:
  usually temp pages for additional checks like captcha or js challenge
- "Error": cannot decide

Return `response`: used only in mode "Response" or "Error"

Return `probability`: how sure you are `0.0 - 1.0`.
Useful if you have several cloakers and one says "Real", another says "Fake" and you want to decide.
Usually just keep default `1.0`.
