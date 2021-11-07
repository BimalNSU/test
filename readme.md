<h1 align="center">Assignment-1</h1>

## About assignment

Suppose you have a list of products(items) in your store, Each product has a price. Which is already add to the database. Now you will create sales with one or more items.
On create sales you can set customer name, phone, and address. Also add one or more items. To add item their will have a dropdown to select item and when select item price will be loadded from item which already set with product/item.
If you want you can change price.
When you will input quantity line total will be calculated automatically. Line total=unit price*quantity.
In summary section, Total price(sum of line total) of sales will be calculated from line total. Total price will be a readonly field.
You can also input paid amount, when input paid amount Due amount will be calculated automatically and display in a readonly field.
On click create button sales will be created and display sales lit.
From sales list you can view, edit, delete sales.

Task:
- Sales create with items.
- Sales list.
- Sales view.
- Sales edit.
- Sales delete.


## API endpoints:

- get: ``/``
- get: ``sales/create``
- get: ``sales/``
- POST: ``sales/``
- get: ``sales/{sales_id}/view``
- get: ``sales/{sales_id}/edit``
- PUT: ``sales/{sales_id}``
- DELETE: ``sales/{sales_id}``

- get: ``products/create``
- get: ``products/``
- POST: ``products/``
- get: ``product/{product_id}``
- get: ``product/{product_id}/view``
- get: ``product/{product_id}/edit``
- PUT: ``product/{product_id}``
- DELETE: ``products/{product_id}``

## Demo pages:

- **Sales create dashboard**
![alt text](https://github.com/BimalNSU/test/blob/master/Resourse_n_diagrams/page1.jpg?raw=true)

- **Sales List**
![alt text](https://github.com/BimalNSU/test/blob/master/Resourse_n_diagrams/page2.jpg?raw=true)

- **Product list**
![alt text](https://github.com/BimalNSU/test/blob/master/Resourse_n_diagrams/page3.jpg?raw=true)

## Database Diagrams
![alt text](https://github.com/BimalNSU/test/blob/master/Resourse_n_diagrams/db_design.jpg?raw=true)

**DB credentials:**
- DB name: test,
- DB user: root,
- DB password: 
- exported sql file: ![alt text](https://github.com/BimalNSU/test/blob/master/Resourse_n_diagrams/test.sql?raw=true)
