# tweet fetcher

A Simple Core Php & AngularJs app to search for tweets with particular hashtag.

## Installation

Clone the repo

```
git clone git@github.com:tweaktastic/tweet_fetcher.git
```

1. Configure your http-vhosts with this directory as the document root.
Thats it Access it on your local.

## Usage

Currently the input field has been kept in disabled mode but can be enabled to search for tweets other than custserv.
You can use the twitter configs added as constants in this repo, but it is recommended to create your own app on Twitter's developer console.

## Issues

1. The tweets returned by the API is not constant. It returns different number of tweets on each call as I am cleaning the tweets after fetching the data from twitter.

2. xss_clean method needs to be implemented for POST call.

3. The Loader's height needs to be managed with the increasing list of tweets(UI).
