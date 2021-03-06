=== WC Fields Factory ===
Contributors: mycholan
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=U3ENPZS5CYMH4
Tags: wc fields factory, custom product fields, customize woocommerce product page, add custom fields to woocommerce product page, custom fields validations, custom fields grouping, 
Requires at least: 3.5
Tested up to: 4.3.1
Stable tag: 1.2.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

It allows you to add custom fields to your woocommerce product page, to get additional information from customers while adding products to cart.

== Description ==

If you ever wanted to get additional informations from customers while adding woocommerce products to cart, This is the plugin all you need. It allows you to add custom fields to your woocommerce product page. You can add custom fields and validations without tweaking any of your theme's code & templates, It also allows you to group the fields and add them to particular products or for particular product categories.

= How it Works =
* Create a fields group
* Add fields to the group
* Assign fields group to whatever products or products category you want

= Features =
* Powerful interface to create your custom fields
* Validation and custom messages
* Grouping custom fields
* Assign groups to particular product or to particular product categories
* Cloning custom fields ( fields per product quantity )
* Automatically embeds custom fields meta into cart page, checkout page, order page and order confirmation emails
* Powerful API to customize the appearance of custom fields

= Supported Field Types =
* Text 
* Number
* Email
* Text Area 
* Checkbox
* Radio 
* Select
* Date Picker
* Color Picker
* File

= Documentation =
* [Getting Started](http://sarkware.com/wc-fields-factory-a-wordpress-plugin-to-add-custom-fields-to-woocommerce-product-page/)
* [Customize Rendering Behavior](http://sarkware.com/how-to-change-wc-fields-factory-custom-product-fields-rendering-behavior/)
* [Overriding Product Prices](http://sarkware.com/woocommerce-change-product-price-dynamically-while-adding-to-cart-without-using-plugins/#override-price-wc-fields-factory)

== Installation ==
1. Ensure you have latest version of WooCommerce plugin installed ( 2.2 or above )
2. Unzip and upload contents of the plugin to your /wp-content/plugins/ directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Use the "Add New" button from "Fields Factory" menu in the wp-admin to create custom fields for woocommerce product page

== Screenshots ==
1. Wccpf product custom fields list
2. Wccpf fields factory
3. Wccpf rules
4. Wccpf settings

== Changelog ==

= 1.0.0 =
* First Public Release.

= 1.0.1 =
* "wccpf/before/field/rendering" and "wccpf/after/field/rendering" actions has been added to customize wccpf fields rendering

= 1.0.2 =
* Issue fixing with "ACF" meta key namespace collition. 

= 1.0.3 =
* Hiding empty fields from cart table, checkout order review table and order meta.

= 1.0.4 =
* Validation issue fixed.
* Issue fixed ( warning log for each non mandatory custom fields ).
* Some css changes ( only class name ) to avoid collision with Bootstrap. 

= 1.1.0 =
* Date picker field type added

= 1.1.1 =
* Color picker field type added

= 1.1.2 =
* Removed unnecessary hooks ( 'woocommerce_add_to_cart', 'woocommerce_cart_item_name' and 'woocommerce_checkout_cart_item_quantity' ) 
  yes they no longer required.
* Now custom fields data has been saved in session through 'woocommerce_add_cart_item_data' hook
* Custom fields rendered on cart & checkout page using 'woocommerce_get_item_data' ( actually rendered via 'cart-item-data.php' template )

= 1.1.3 =
* Order meta ( as well as email ) not added Issue fixed   

= 1.1.4 =
* utf-8 encoding issue fixed
* Internationalization support.

= 1.1.5 =
* Select field with variable product - issue fixed
* Order conflict while updating fields - issue fixed
* Newline character ( for select, checkbox and radio ) - issue fixed 

= 1.1.6 =
* fixed "Missing argument" error log warning message    

= 1.2.0 =
* Woocommerce 2.4.X compatible 
* File upload field type added
* Internationalization ( i18n ) support added

= 1.2.1 =
* Add to cart validation issue fixed

= 1.2.2 =
* Fields cloning option added ( Fields per count, If customer increase product count custom fields also cloned )
* Visibility of custom meta can be set ( show or hide on cart & checkout page )

* Setting page added
* Visibility Option - you can set custom data visibility globally ( applicable for all custom fields - created by this plugin )
* Field Location - you can specifiy where the custom fields should be included.
* Enable or Disbale - fields cloning option.
* Grouping the meta on cart & checkout page, option added.
* Grouping custom fields on cart & checkout page, option added.
* Set label for fields group
* Option to disable past or future dates
* Option to disbale particular week days
* Read only option added for Datepicker textbox ( usefull for mobile view )
* heigher value z-index applied for datepickers
* Pallete option added to color picker
* Option to show only palette or along with color picker
* Color format option added

= 1.2.3 =
* Multiple colour pickers issue fix
* wccpf_init_color_pickers undefined issue fix
* Group title index will be hidden if product count is 1
* Minimum product quantity issue fix
* File type validation issue fix
* "Zero fields message" while deleting custom fields ( on wp-admin )