GitHub Short Code
=================
Add a short code for adding GitHub Star and Fork buttons with counts to a HTMLText field.

## Maintainer Contact
* Ed Chipman ([UndefinedOffset](https://github.com/UndefinedOffset))

## Requirements
* SilverStripe CMS 4.x | 5.x


## Installation
* Run composer require webbuilders-group/silverstripe-githubshortcode dev-master in the project folder
* Run dev/build?flush=all to regenerate the manifest


## Usage
Usage is pretty straight forward to add GitHub buttons you simply add the following:
```
[github repo="repository owner/repository name"]
```

Optionally you may add layout="stacked" to use a stacked layout (defaults to inline). As well you may also optionally add button="stars" or button="forks" (defaults to both) to only show the star gazers or forks of the repository.
```
[github repo="repository owner/repository name" layout="stacked"]

[github repo="repository owner/repository name" layout="stacked" button="stars"]
```

In 3.1 the short codes above will work as included however the updated syntax for the short code would be (of course layout and button are not required):
```
[github,repo="repository owner/repository name",layout="stacked",button="stars"]
```


#### Configuration Options
There are a few configuration options available to you:

```yml
WebbuildersGroup\GitHubShortCode\GitHubShortCode:
    CacheTime: 86400 #Cache time in seconds (default is 1 day, remember the GitHub api is rate limited)
    UseBasicAuth: false #Use GitHub authentication or not
    Username: "your username" #GitHub Username, required if using authentication
    Password: "your password" #GitHub Password, required if using authentication
    UseShortHandNumbers: true #Use short hand numbers i.e 5.6K or not
```
