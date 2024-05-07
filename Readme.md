# Installation
```
composer install
```

If you don't have composer installed, you can download it [here](https://getcomposer.org/download/).

## index.php

This script serves as your postback script.

### Configuration

Before using the script, adjust the following variables:

- **$eventName**: Replace with your desired event name (e.g., "Purchase").
- **$password**: Replace with your desired password for script protection.

#### Required Parameters

| Parameter | Meaning                   |
|-----------|---------------------------|
| pwd       | Password to protect script |
| account   | Account name              |
| clickid   | Facebook Click ID         |
| ip        | User IP                   |

### Example Usage
```
https://yourhost.com/index.php?pwd=yourpassword&account=youraccount&clickid=yourclickid&ip=yourip
```

## download.php

This script downloads all pixels from a Google Sheet into a CSV file.
Before using the script, create a credentials file and place it into this folder. [Instructions here](https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api#create-a-google-project-and-configure-sheets-api).
It`s recommended to set up a cron job to run this script periodically.

### Configuration

Before using the script, adjust the following variables:

- **$password**: Replace with your desired password for script protection.
- **$sheetId**: Replace with the ID of your Google Sheet.

#### Required Parameters

| Parameter | Meaning                   |
|-----------|---------------------------|
| pwd       | Password to protect script |

### Example Usage
```
https://yourhost.com/download.php?pwd=yourpassword
```