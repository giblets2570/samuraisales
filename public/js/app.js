var app = angular.module('app', []).

controller('formController', ['$scope','$http', function(scope,http){

	scope.submit = function(){

		var fd = new FormData();
		fd.append("name",scope.inputName);
		fd.append("email",scope.inputEmail);
		fd.append("phone",scope.inputPhone);
		fd.append("availability",scope.inputAvailability);
		fd.append("months",scope.inputMonths);
		fd.append("months",scope.inputMonths);
		fd.append("CVFile",scope.CVFile);

		http({
			method: "POST",
			url: "/signup",
			cache: false,
			data: fd
		},{
	        withCredentials: true,
	        headers: {'Content-Type': undefined },
	        transformRequest: angular.identity
	    }).success(function(data){
			console.log(data);
		});
	}
}]);