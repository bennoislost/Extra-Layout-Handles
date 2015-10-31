# Extra Layout Handles

This is a utility module that should make building Magento websites a bit easier.


Forked from [https://github.com/ho-nl/Ho_Handles](https://github.com/ho-nl/Ho_Handles). Top work chaps!


## Getting started with handles.

The following snippit will remove the product listings from direct children of category id `15`, also adds a simple text block to the top of content.

```
<CATEGORY_15_child>
    <reference name="content">
        <action method="unsetChild">
            <alias>category.products</alias>
        </action>
        <block type="core/text_list" name="test" before="-">
            <block type="core/text" name="benno" output="toHtml">
                <action method="setText">
                    <text>hello</text>
                </action>
            </block>
        </block>
    </reference>
</CATEGORY_15_child>
```

Add the above snippet to your themes `local.xml`

## Add handles to category page

If you are viewing a category: /products/dvds.html (category id: 5)

You'll get a handle by default: `CATEGORY_5`

We add the following:

### Handle: `CATEGORY_2_child_child`
Category 2 is the root category for this store, so allows for store specific configuration. If the category path is
longer it will show something like `CATEGORY_2_child_child_child`


### Handle: `CATEGORY_2_child_dvds`
This one is kinda cool, this one allows you have one layout for multiple childcategories. Imagine the following categories:
- /playgroup/books.html
- /kindergarten/books.html
- /elementary/books.html

All these categories will have the `CATEGORY_2_child_books` handle, so you can all style them the same!

### Handle: `CATEGORY_3_child`
Child of a certain category.
If the category path is longer it will show something like `CATEGORY_3_child_child`.


### Handle: `CATEGORY_3_dvds`
This one probably is kinda useless, but is the current category written in a different notation and the way the module
works this one is currenlty automatically added.

## Add category handle to product page.
The same as for the category applies, except everything is prefixed with `PRODUCT_CATEGORY_`.

## Add attribute set handle to product page

```
PRODUCT_ATTRIBUTE_SET_default
```
For styling based on the attribute set handle.

## Add handles to CMS page
If you have the page: customerservice/faq/question_one, you'll get the following handles:

```
CMS_PAGE_customerservice_child_child
CMS_PAGE_customerservice_faq_child
CMS_PAGE_customerservice_faq_question_one
```

## Thanks

[https://github.com/ho-nl/Ho_Handles](https://github.com/ho-nl/Ho_Handles)