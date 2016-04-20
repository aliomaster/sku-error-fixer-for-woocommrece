WooCommerce SKU variations Cleaner SWS Plugin
=============================================

Clean old variations and fix not unique SKU numbers of WooCommerce products.


## What is the old variations?

This is a former variations of the variable products when it type have been changed to Simple or another type of product not variable. WooCommerce does not remove these variations to the case you decide to change the product type on variable again. For this case variables remain intact with all fields filled in, including the SKU field.

## What's the problem of old variations?

#### Clogging the database of unnecessary data

Old variation of products is invisible. You can change the product type with variable to another, and after some time to remove this product and forget about it, but these variations will not removed. They are stored in the database and can take a lot of space.

#### Unique SKU problem

A known problem of the uniqueness of the SKU number of the product. WooCommers allows you to assign only unique SKU for product. If you used any SKU for the product variation, and then changed the product type to another, you will not be able to use the same SKU for a different product. You will receive an error "Product SKU must be unique". WooCommerce SKU variations Cleaner plugin eliminates this problem.

## How do I use this plugin?

After installing the plugin you will need to go to the plugin settings page Woocommerce > SKU Variations Cleaner, where you can to scan your site for presence of any old variations, clean them SKU fields or removal them completely. You can also set up automatic checking and fixing not unique SKU problem when you edit a product.

## I've got an idea/fix for the plugin

If you would like to contribute to this plugin then please fork it and send a pull request. I'll merge the request if it fits into the goals for the plugin and credit you in the [changelog](https://github.com/aliowebmaster/woocommrece-sku-variations-cleaner-sws/blob/master/changelog.txt).

## This plugin is amazing! How can I ever repay you?

There's no need to credit me in your code for this template, but if you would like to buy me a treat then you can [donate here](http://www.aliowebmaster.tk/donate).
