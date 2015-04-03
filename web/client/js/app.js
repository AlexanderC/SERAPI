/**
 * Created by AlexanderC <self@alexanderc.me> on 4/3/15.
 */

var app = angular.module('client', ['clientRest', 'smart-table']);

app.controller('IndexCtrl', ['$scope', 'Provider', 'Rate', function($scope, Provider, Rate) {
    $scope.providers = Provider.query();
    $scope.country = 'MDA';
    $scope.currency = 'MDL';

    $scope.$watch('provider', function(provider, oldProvider) {
        if(provider && provider != oldProvider) {
            Rate.get({providerName: provider}, function(rates) {
                var ratesList = rates[$scope.country][$scope.currency];
                var ratesFlatten = [];

                for(var bank in ratesList) {
                    var localRatesList = ratesList[bank];
                    var currencyList = {};

                    for(var currency in localRatesList['buy']) {
                        if(!currencyList.hasOwnProperty(currency)) {
                            currencyList[currency] = {};
                        }

                        currencyList[currency]['buy'] = localRatesList['buy'][currency];
                    }

                    for(var currency in localRatesList['sell']) {
                        if(!currencyList.hasOwnProperty(currency)) {
                            currencyList[currency] = {};
                        }

                        currencyList[currency]['sell'] = localRatesList['sell'][currency];
                    }

                    for(var currency in currencyList) {
                        ratesFlatten.push({
                            bank: bank,
                            currency: currency,
                            buy: currencyList[currency]['buy'],
                            sell: currencyList[currency]['sell']
                        });
                    }
                }

                $scope.rates = ratesFlatten;
            });
        }
    });
}]);