Doctrine Workbench Bundle
=========================

A Bundle for create Doctrine entities in your browser.

Installation
------------

### Step 1: Download the Bundle

```json
# composer.json
{
// ...
    "require-dev": {
        // ...
        "javierrodriguezcuevas/doctrine-workbench-bundle": "dev-master"
    },
    // ...
    "repositories": [
        // ...
        {
            "type": "vcs",
            "url": "https://github.com/javierrodriguezcuevas/DoctrineWorkbenchBundle.git"
        }
    ]
// ...
}

// ...
```

### Step 2: Enable the Bundle

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            // ...
            $bundles[] = new Mst\DoctrineWorkbenchBundle\MstDoctrineWorkbenchBundle();
        }
    }

    // ...
}
```

### Step 3: Load Routes

```yaml
# app/config/routing_dev.yml
_mst_doctrine_workbench:
    resource: "@MstDoctrineWorkbenchBundle/Resources/config/routing.yml"
    prefix:   /_doctrine_workbench

# ...
```

### Step 4: Update database

```cli
php bin/console doctrine:schema:update --force
```

### Step 5: Install Assets

```cli
php bin/console assets:install --symlink
```

### Step 6: Play!

```
http://127.0.0.1:8000/_doctrine_workbench/#/
```
