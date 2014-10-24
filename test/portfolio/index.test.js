/*global console,module,inject,it,expect,describe*/
describe('index', function () {
    "use strict";
    module('index');
    inject(function () {
        console.log('inject');
    });
    it('should be ok', function () {
        expect(1).toEqual(1);
    });
});