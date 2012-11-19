var todoApp = angular.module('todoApp', ['ngResource']);

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



todoApp.factory('Item', function($resource) {
	var Item = $resource('http://localhost/~pasamio/ebay/jwc-demo/www/api/tasks/:task_id',  {
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
