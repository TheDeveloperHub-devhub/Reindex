# DeveloperHub Reindex

This extension enables the admin to reindex the data from backend. Backend Reindex extension is a powerful tool to refresh connection data of the product, stock, inventory, etc. quickly. Admin no needs to use any command line, all the updated information is still delivered to customers as soon as possible.
Especially, admin can choose many types of reindexing to refresh data as well as reindex bulk data in seconds. Thus, owners can save time with several clicks.
With a well-organized grid, all the indexation processes and data status are visible so that admin can easily track them.


## Features
 1. Show notification about the details of rebuilt indexer. 
 2. A handy grid management
 3. Reindex bulk data in seconds
 4. Enable to check the indexation status


## Installation
Install the module as a composer requirement for environments:

```
    composer require devhub/reindex
    php bin/magento module:enable DeveloperHub_Reindex
    php bin/magento setup:upgrade
    php bin/magento setup:di:compile
    php bin/magento setup:static-content:deploy
```
