# QuickyPHP
A php micro-framework for simple and quick web-applications

## Motivation
I started this project because I wanted to procrastinate important work for university. No joke. But it turned into a slight obsession that has been with me for a few days now. I found developing my own PHP micro-framework so interesting that I kept reading up on documentation and articles and watching tutorial after tutorial.

The framework has the sense to be structured as simple as possible, to be easily customizable by anyone to their needs. I also experimented with technologies that I had never used before but found in other projects or got to know at university (e.g. method dispatcher or reflection classes). Also, the project was partly done in collaboration with ChatGPT (OpenAI), which was also a memorable experiment.

I got the idea of how a simple PHP framework works from other open source projects. Here is a selection (if you read it carefully, you will quickly see parallels to my framework and its structure):
- [FightPHP](https://flightphp.com/)
- [SlimFramework](https://www.slimframework.com/)
- [CakePHP](https://cakephp.org/)

## Sneak Peak
A simple web application powered by this framework:
```php
require __DIR__ . "/../../vendor/autoload.php";

$app = Quicky::create();

Quicky::route("GET", "/", function(Request $request, Response $response) {
    $response->send("Hello World");
});

$app->run();
```

## Work in progress
#### ToDo:
- [ ] Create a logo  
- [ ] Write tests
- [ ] Write documentation
- [ ] Host a website
#### Done:
- [x] ~~Implement DynamicLoader->findMethod() as BST~~
- [x] ~~Find a project name~~  
- [x] ~~Buy domain: quickyphp.de~~
- [x] ~~In-built Sessions~~  
- [x] ~~In-built Global Variables~~  
- [x] ~~In-built Caching~~
- [x] ~~Response->sendFile method~~  
- [x] ~~Config Parser~~  
- [x] ~~Route Wildcards~~ 
- [x] ~~Route RegEx~~ 
- [x] ~~Support .env files~~
- [x] ~~Add support for DELETE, UPDATE, PUT, ... methods~~
- [x] ~~Response->toString() method~~  
- [x] ~~Create composer package~~
- [x] ~~Simple Middleware~~
- [x] ~~In-built CSRF Tokens~~  
- [x] ~~In-built Access Logging~~  
- [x] ~~In-built Error Handling~~  

## Requirements
QuickyPHP requires PHP 7.4 or higher and a webserver that supports Rewrite Rules.  
Note: Composer 2+ is required to find the package.

## Installation
### Composer:

Install the project via command-line:
```bash
composer create-project david-prv/quickyphp
```
Install requirements:
```bash
composer install
```

### Manual:

Create a project folder:
```bash
mkdir myProject && cd myProject
```
Download git repository:
```bash
git clone https://github.com/david-prv/QuickyPHP.git
```
Install requirements:
```bash
composer install
```

### Code Examples:
I have written some basic examples [here](https://github.com/david-prv/QuickyPHP/tree/main/examples).

## Contributing
TBD

## Support
If you like what I do, feel free to buy me a coffee for my work.  
Programming early in the morning is hard without a good cup of this magical liquid.
  
Click here to support me:

<a href="https://www.buymeacoffee.com/david.dewes">
    <img src="https://media3.giphy.com/media/TDQOtnWgsBx99cNoyH/giphy.gif" height="80" alt="buy me a coffee!"/>
</a>

## License
Quicky is released under the [MIT](https://en.m.wikipedia.org/wiki/MIT_License) license.
