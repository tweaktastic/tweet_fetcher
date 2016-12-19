var app = angular.module('tweetFetcher', ['ngRoute']);
app.factory("services", ['$http', function($http) {
  var serviceBase = 'services/'
    var obj = {};
    obj.getTweets = function(hashtag, max_id){
      return $http.get(serviceBase+'tweets/get?hashtag=' + hashtag + "&max_id=" + max_id).then(function(status){
        return status.data
      });
    }
    return obj;
}]);

app.controller('listCtrl', function ($rootScope, $scope, services) {
    //set default values on controller load
    $rootScope.hashtag = "custserv";
    $rootScope.result = {
      fetchedTweets: [],
      hashtag: ""
    }
    $rootScope.showBtn = false;
    $scope.max_id = "";
    $scope.resetVars = function(){
      $scope.max_id = "";
      $rootScope.result = {
        fetchedTweets: [],
        hashtag: ""
      };
    }

    $scope.getFeed = function(hashtag){
      $rootScope.showLoader = true;
      services.getTweets(hashtag, $scope.max_id).then(function(data){
          if(!data.tweets || data.tweets.length == 0){
            $rootScope.showBtn = false;
            alert('no more matching tweets were found');
          }else{
            $scope.max_id = data.max_id.toString();
            $rootScope.result.hashtag = hashtag;
            $rootScope.showBtn = true;
            var tweets = data.tweets;
            Object.keys(tweets).map(function(key){
              $rootScope.result.fetchedTweets.push(tweets[key]);
            });
          }
          $rootScope.showLoader = false;
      });
    }

    $rootScope.getTweets = function(hashtag){
      $scope.resetVars();
      $scope.getFeed(hashtag);
      return true;
    };

    $rootScope.fetchMore = function(){
      $scope.getFeed($rootScope.result.hashtag);
    }

});

app.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/', {
        title: 'Tweets',
        templateUrl: 'partials/tweet-list.html',
        controller: 'listCtrl'
      })
      .otherwise({
        redirectTo: '/'
      });
}]);
app.run(['$location', '$rootScope', function($location, $rootScope) {

  $rootScope.$on('$routeChangeStart', function (event, next, current) {
    $rootScope.showLoader = true;
  });
  $rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
      $rootScope.title = current.$$route.title;
      $rootScope.showLoader = false;
  });
}]);
