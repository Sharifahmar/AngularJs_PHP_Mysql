(function () {
    'use strict';

    // Based on myDraggable - https://docs.angularjs.org/guide/directive

    angular.module('app', ['gm.dragDrop']).run(run);

    function run($rootScope, $filter) {
//        $rootScope.categories = [
//            {
//                items: [
//                    {name: "Item 1"}
//                ]
//            }, {
//                items: [
//                    {name: "Item 2"}
//                ]
//            }, {
//                items: [
//                    {name: "Item 3"}
//                ]
//            }
//        ];

        $rootScope.orderedItems = [
            {
                number: 1,
                order: 1,
//                value: "FieldName",
                field_id:1
            },
            {
                number: 2,
                order: 2,
                value: "Two",
                field_id:2
            },
            {
                number: 3,
                order: 3,
                value: "Three",
                field_id:3
            }
        ];

//        $rootScope.mixedZone = {
//            items: [
//                {name: "Left", zone: "left"},
//                {name: "Right", zone: "right"},
//                {name: "Left", zone: "left"},
//                {name: "Right", zone: "right"},
//                {name: "Left", zone: "left"},
//                {name: "Right", zone: "right"}
//            ]
//        };

        $rootScope.onHover = function (item) {
            return function (dragItem, mouseEvent) {
//                console.log(dragItem);
//                console.log(item);
                if (item != dragItem)
                    dragItem.order = item.order + ((mouseEvent.offsetY || -1) > 0 ? 0.5 : -0.5)
                console.log(dragItem.order);
            }
        }

        $rootScope.reorder = function reorder() {
//            console.log($rootScope.orderedItems);
            
            var _orderedItems = $filter('orderBy')($rootScope.orderedItems, 'order');
            for (var i = 0; i < _orderedItems.length; i++) {
                _orderedItems[i].number = _orderedItems[i].order = i + 1;
            }
        }

        $rootScope.reset = function reset(droppedItem) {
            droppedItem.order = droppedItem.number;
            console.log(droppedItem.order);
        }

        $rootScope.leftZone = {
            items: []
        };

        $rootScope.rightZone = {
            items: []
        };

        $rootScope.getDropHandler = function (category) {
            return function (dragOb) {
                if (category.items.indexOf(dragOb.item) < 0) {
                    dragOb.category.items.splice(dragOb.category.items.indexOf(dragOb.item), 1);
                    category.items.push(dragOb.item);
                    return true;  // Returning truthy value since we're modifying the view model
                }
            }
        }


    }


})();