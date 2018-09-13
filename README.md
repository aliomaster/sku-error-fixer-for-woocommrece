# SKU Error Fixer for WooCommerce

This plugin fixes a unique SKU error of WooCommerce products.

## Description

When you creating new products in your webshop using a WooCommerce plugin, you can get the unique SKU error for your product and SKU not being saved properly.

![SKU Error Screenshot](https://ps.w.org/sku-error-fixer-for-woocommerce/trunk/screenshot-6.png)

This is because in the database there are old variations of previously created products that have the same SKU number. WooCommerce SKU Error Fixer plugin solves this problem by cleaning and/or removing old products variables and also you can setup automatic checking and fixing not unique SKU problem when you edit a product.

## Installation

Install of "SKU Error Fixer for WooCommerce" can be done either by searching for "SKU Error Fixer for WooCommerce" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
2. Upload the ZIP file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard
3. Activate the plugin through the 'Plugins' menu in WordPress

## Screenshots

#### Settings page - search of old variations
![Search of old variations](https://ps.w.org/sku-error-fixer-for-woocommerce/trunk/screenshot-1.png)

#### Settings page - clean SKU fields of old variations
![Clean SKU fields of old variations](https://ps.w.org/sku-error-fixer-for-woocommerce/trunk/screenshot-2.png)

#### Settings page - removal old variations
![Removal old variations](https://ps.w.org/sku-error-fixer-for-woocommerce/trunk/screenshot-3.png)

![Removal old variations](https://ps.w.org/sku-error-fixer-for-woocommerce/trunk/screenshot-4.png)

![Clear SKU on the Product Page](https://ps.w.org/sku-error-fixer-for-woocommerce/trunk/screenshot-5.png)

### Frequently Asked Questions

#### What is the old variations?

These are former variations of variable products when it type have been changed to Simple or another type of product not variable. WooCommerce does not remove these variations to the case you decide to change the product type on variable again. For this case variables remain intact with all fields filled in, including the SKU field.

#### What's the problem of old variations?

##### Clogging the database of unnecessary data

Old variation of products is invisible. You can change the product type with variable to another, and after some time to remove this product and forget about it, but these variations will not be removed. They are stored in the database and it can take a lot of space.

##### Unique SKU problem

A known problem of the uniqueness of the SKU number of the product. WooCommers allows you to assign only unique SKU for product. If you used any SKU for the product variation, and then changed the product type to another, you will not be able to use the same SKU for a different product. You will receive an error "Product SKU must be unique". WooCommerce SKU Error Fixer plugin eliminates this problem.

#### How do I use this plugin?

After installing the plugin you will need to go to the plugin settings page Woocommerce > SKU Error Fixer, where you can to scan your site for presence of any old variations, clean them SKU fields or remove them completely. You can also setup automatic checking and fixing not unique SKU problem when you edit a product.

#### I've got an idea/fix for the plugin

If you would like to contribute to this plugin then please fork it and send a pull request. I'll merge the request if it fits into the goals for the plugin and credit you in the [changelog](https://github.com/almazka987/sku-error-fixer-for-woocommrece/blob/master/changelog.txt).

## Changelog

= 1.0 =
* 02.05.2016
* Initial release