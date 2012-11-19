var todoApp = angular.module('todoApp', ['ngResource']);

todoApp.config(function($routeProvider) {
$routeProvider.
	when('/', {controller: 'AppCtrl', templateUrl: 'partials/list.phtml'}).
	when('/about', {templateUrl: 'partials/about.phtml'}).
	when('/tasks/new', {controller: 'taskEditCtrl', templateUrl: 'partials/edit.phtml'}).
	when('/tasks/:task_id', {controller: 'taskDetailCtrl', templateUrl: 'partials/detail.phtml'}).
	when('/tasks/:task_id/edit', {controller: 'taskEditCtrl', templateUrl: 'partials/edit.phtml'}).
	otherwise({redirectTo: '/'});
});

todoApp.controller('AppCtrl', function($scope, Item) {

	var items = $scope.items = Item.query();

	// computed property
	$scope.remaining = function() {
		return items.reduce(function(count, item) {
			return item.state ? count : count + 1;
		}, 0);
	};

	// event handler
	$scope.add = function(newItem) {
		var item = new Item({name: newItem.name});
		items.push(item);
		newItem.name = '';

		// save to services
		item.$save();
	};

	$scope.update = function(updatedItem) {
		var item = new Item(updatedItem);
		item.$update();
	}

	// event handler
	$scope.archive = function() {
		items = $scope.items = items.filter(function(item) {
			if (item.state)
			{
				item.$remove();
				return false;
			}
			return true;
		});
	};
});

todoApp.controller('taskDetailCtrl', function($scope, $routeParams, Item) {
	// Retrieve this item from the service
	var item = $scope.item = Item.get({task_id:$routeParams.task_id});
});

todoApp.controller('taskEditCtrl', function($scope, $routeParams, $location, Item) {
	// Retrieve this item from the service
	var item = $scope.item = $routeParams.task_id ? Item.get({task_id:$routeParams.task_id}) : {task_id: 0};
	var location = $location;

	$scope.update = function(updatedItem) {
		var item = new Item(updatedItem);
		var redirect = function(result) {
			location.path("/tasks/" + result.task_id);
		};
		item.task_id > 0 ? item.$update(redirect) : item.$save(redirect);
	}
});

todoApp.factory('Item', function($resource) {
	var Item = $resource('http://localhost/~pasamio/ebay/jwc/www/api/tasks/:task_id',  {
		task_id: "@task_id"
	}, {
		update: {method: 'PUT'}
	});

	Item.prototype.$remove = function() {
		Item.remove({task_id: this.task_id});
	};

	Item.prototype.state = false;

	return Item;
});