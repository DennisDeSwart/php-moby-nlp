
# PHP Moby NLP

Gets the "complete thought" from a verb and a noun using Moby Thesaurus


## What does it do?
- It uses the Moby Thesaurus to get the "complete thought" from a verb and a noun.
- This is meant for Natural Language Processing (NLP) tasks.

How does it work?
- The code looks for "complete thoughts", see the link to Wikipedia here:
```
https://simple.wikipedia.org/wiki/Simple_sentence
```

- First: open the index.php, a form will appear.
- Second: enter a verb, like "walk", "talk" or "run". It is best to user the base form or lemma. So do not use "running", but use "run". This program can be used to get the lemma for you: 
```
https://github.com/DennisDeSwart/php-stanford-corenlp-adapter
https://www.phpclasses.org/package/10056-PHP-Natural-language-processing-using-Stanford-server.html
```

- Third, enter a noun like "dog", "cat", "house" or "car".
- Finally, press Submit, wait for the result. The result may take up to 2 seconds.


## What can I do with the result?

- First, you can use it in spam killers: if there are related words in a text, the text is probably not fake.
- Second: you can use this for creating reports and understanding text.
- Third: this could be used as suggestions in search engines.


## Requirements
- PHP 5.3 or higher: it also works on PHP 7


## Installation using Composer 

You can install the adapter by putting the following line into your composer.json and running a composer update

```
    {
        "require": {
            "dennis-de-swart/php-moby-nlp": "*"
        }
    }
```


## Recommended practices

- Looking up words in the thesaurus costs a lot of time, sometimes up to 2 seconds. You should only lookup words if you need to.
- To select the most important words like verbs and nouns, you can use a NLP parser like Stanford's CoreNLP
- To use Stanford CoreNLP check these links:
```
https://github.com/DennisDeSwart/php-stanford-corenlp-adapter
http://stanfordnlp.github.io/CoreNLP/corenlp-server.html
```


## Can I use a different set of words (=thesaurus)? 
Yes. This script uses the Moby Thesaurus that is based on the English language from 1996. It doesn't have many modern words. You can change the Thesaurus by using an API.
However, this is not programmed yet. To make it happen, there needs to be an API connection. Also the existing Synonym function needs to be re-written to use this API.
I want to write a script to use the Urban Dictionary API. This would make it talk "urban". Here is an example on how to connect:
```
https://github.com/zdict/zdict/wiki/Urban-dictionary-API-documentation
```

## Example output

See "example_write_document.PNG"


## Any questions?

Please let me know. 


## Credits

Brent Rossen, orginal author of the MobyThesaurus.php class
```
 https://github.com/phyous/moby-thesaurus
```

