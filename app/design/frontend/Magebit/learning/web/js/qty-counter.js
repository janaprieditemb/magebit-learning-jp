/**
 * This file is part of the Magebit package.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magebit Faq
 * to newer versions in the future.
 *
 * @copyright Copyright (c) 2023 Magebit, Ltd. (https://magebit.com/)
 * @license   GNU General Public License ("GPL") v3.0
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
define([
    'ko',
    'uiComponent'
], function (ko, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: './input-counter.html'
        },

        initialize: function () {
            this._super();
            this.qty = ko.observable(this.defaultQty).extend(
                    this.dataValidate
            );
        },

        remove: function() {
            let newQuantity = this.qty() -1;

            if(newQuantity < 1)
            {
                newQuantity = 1
            }

            this.qty(newQuantity);
        },

        add: function() {
            let newQuantity = this.qty() + 1;
            this.qty(newQuantity);
        }
    });
});
