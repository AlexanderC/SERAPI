/**
 * Created by AlexanderC <self@alexanderc.me> on 4/3/15.
 */
var client = angular.module('clientRest', ['ngResource']);

var baseUrl = window.baseUrl ? window.baseUrl : '/';

client.factory('Provider', ['$resource',
    function($resource){
        return $resource(baseUrl + 'providers', {}, {
            query: {
                method: 'GET',
                params: {},
                isArray: true
            }
        });
    }]);

client.factory('Rate', ['$resource',
    function($resource){
        return $resource(baseUrl + 'providers/rates/:providerName', {}, {
            query: {
                method: 'GET',
                params: {
                    'providerName': 'providerName'
                },
                cache: false,
                isArray: true
            }
        });
    }]);