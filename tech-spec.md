## Tech Stack & Approach

This document specifies the tech stack and approaches used in Property CRM project. First of all, I created this project as an experiment to get know about InteriaJs and its working. This project is inspired from Ping CRM.

## Basic Overview 

### Tech Stack
- VueJs3
- InertiaJs
- Laravel
- Laravel Sail
- TailwindCss
- EsLint
- Prettier
- Vite
- MySql

### Tables
- properties - for storing third party properties
- property_types - for storing property types and associated with properties

### Dir
- app - Most of the stuff(controller, models and Requests)
- resource/js - VueJs components
- app/services - Service class for fetching properties data from third party vendors
- vuejs
    - Components contain reusable components
    - Layout contain layout components
    - Pages contain components for property crud operations main files

**For some components new Composition API(VueJs3) has been used and some are build with traditional style.**

## Features

### Fetching Properties from third party vendors

A class `CraigPropertyService` has been created for fetching properties from third party vendors. Please setup env variables before using or running this to populate your database.
```
CRAIG_BASE_URL
CRAIG_PROPERTY_API_URL
CRAIG_API_KEY
```

You can access the `CraigPropertyService` under `App\Services` namespace and modify it as per your needs.
### Usage

**From Job**

```
<?php
use App\Services\CraigPropertyService;

/**
* Execute the job.
*
* @return void
*/
public function handle(CraigPropertyService $craigPropertyService)
{
    Log::info("Started running job - syncing properties from third party");
    $craigPropertyService->handleCraigProperties();
}
```

You can take also look at `FetchProperties` job, its already been created to run in background to fetch and sync. Beware to deal with the timeout issues.

**From Route**

```
<?php

use App\Services\CraigPropertyService;
Route::get('sync-properties', function (CraigPropertyService $craigPropertyService) {
    // App\Jobs\FetchProperties::dispatch(); // if using jobs, we need to increase the timeout.
    $craigPropertyService->handleCraigProperties();
    return ['ok' => true];
});
```
You can visit `api.php` file to test.

**Schedule to run on daily basis using Laravel scheduler**

```
<?php
use App\Services\CraigPropertyService;

/**
* Define the application's command schedule.
*
* @param  \Illuminate\Console\Scheduling\Schedule  $schedule
* @return void
*/
protected function schedule(Schedule $schedule)
{
    $schedule->call(function (CraigPropertyService $craigPropertyService) {
            $craigPropertyService->handleCraigProperties();
    })->daily()->runInBackground(); 
}
```
You can visit `app/Console/Kernel.php` file to take a look.

**Dealing with duplicate properties**

When we are syncing properties, there might be an issue with duplicate properties been inserted into database. To deal with it, i have created a column
`ex_property_id` in `properties` table, that uniquely identify the property id from third party vendors.

**Current Logic**

Currently demo third party api is returning updated_at column in the API response. So, I'm checking with `ex_property_id` and `updated_at`, if both of these are not matched, then we update the property other we do the insert.
So, we are upserting the records and sync into our database.

There are many ways in Laravel 9 specially to do the upsert.

```
// Update properties, if updated_at is not same.
// if we want to use upsert, need to define the unique keys,
Property::upsert(
    $finalParams,
    ['ex_property_id', 'property_updated_at'],
);

PropertyType::upsert(
    $propertyTypes,
    ['ex_property_type_id', 'type_created_at'],
);
```
or

```
// Todo: optimize in case of large data set
// there are other ways to do the upsert

collect($finalParams)->each(function (array $row) {
    Property::updateOrCreate(
        [
            'ex_property_id' => $row['ex_property_id'],
            'property_updated_at' => $row['property_updated_at']
        ],
        $row
    );
});

collect($propertyTypes)->each(function (array $row) {
    PropertyType::updateOrCreate(
        [
            'ex_property_type_id' => $row['ex_property_type_id'],
            'type_updated_at' => $row['type_updated_at']
        ],
        $row
    );
});
```

Both of these methods have there own advantages and disadvantages. I let you decide to choose better or have you own logic developed.

### CRUD for managing properties
For managing the properties from admin panel, a controller `PropertiesController` has been created. Which handle

- Incoming request
- Render VueJs components with InteriaJs Renderer
- Save Data

For validation on the data, Form Request classes are created
- `CreatePropertyRequest`
- `UpdatePropertyRequest`

For storing property main image `properties` folder is used under storage. Please don't forgot to link your storage.

**Request/Response Flow**

Boot -> Intertia -> Route -> Controller -> Return Component and its props -> Intertia Renders

There are many improvements left in terms of coding style, I use the simple one instead of repository architecture and other patterns. You can choose as per your own style.
Right now, controller is performing:

- Rendering Vue components
- Saving data







