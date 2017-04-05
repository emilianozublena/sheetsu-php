# sheetsu-php

## Usage

### Instantianting the Sheetsu Client

You need to instantiate the main Sheetsu object and give the SheetID
You can find this URL on [Sheetsu Dashboard](https://sheetsu.com/your-apis).
Remember to use composer's autoload.

```php
require('vendor/autoload.php');
use Sheetsu\Sheetsu;

$sheetsu = new Sheetsu([
    'sheetId' => 'sheetId'
]);
```

If you have HTTP Basic Authentication turned on for your API, you should pass `key` and `secret` here, like:
```php
require('vendor/autoload.php');
use Sheetsu\Sheetsu;

$sheetsu = new Sheetsu([
    'sheetId'   => 'sheetId',
    'key'       => 'key',
    'secret'    => 'secret'
]);
```

### Collection-Model

The Sheetsu PHP Library comes with a small implementation of a [Collection abstract data type](https://en.wikipedia.org/wiki/Collection_(abstract_data_type)).

Models are units of Collections (in this case, each Model represents a Row of the given sheet).

Instead of giving arrays to the Sheetsu Client (for CRUD operations), you can do something like this:

```php
$collection = new Collection();
$collection->add([
    Model::create(['name' => 'John']),
    Model::create(['name' => 'Steve'])
]);
$response = $sheetsu->create($collection);
```
Collections and Models are the 2 objects that you are going to get every time you call the api too.

### Create
[Link to docs](https://sheetsu.com/docs#post)

To add data to Google Spreadsheets, send an array, an array of arrays, or more simply, work with Models or Collections ;)

```php
# Adds single row from array
$sheetsu->create(['name' => 'John']);
# Adds multiple rows from array
$sheetsu->create([
    ['name' => 'John'],
    ['name' => 'Steve'
]);
# Adds single row from Model
$sheetsu->create(Model::create(['name' => 'John']));
# Adds multiple rows from Collection
$collection = new Collection();
$collection->add([
    Model::create(['name' => 'John']),
    Model::create(['name' => 'Steve'])
]);
$response = $sheetsu->create($collection);
```

After call is made, returns a Response object.

### Read
[Link to docs](https://sheetsu.com/docs#get)

Read the whole sheet
```php
$response = $sheetsu->read();
$collection = $response->getCollection();
```

Read function allows 2 parameters
  - `limit` - limit number of results
  - `offset` - start from N first record

```php
# Get first two rows from sheet starting from the first row
$response = $sheetsu->read(2, 0);
$collection = $response->getCollection();
```

#### search
[Link to docs](https://sheetsu.com/docs#get_search)

To get rows that match search criteria, pass an array with criteria

```php
# Get all rows where column 'id' is 'foo' and column 'value' is 'bar'
$response = $sheetsu->search([
    'id'    => 'foo',
    'value' => 'bar'
]);
$collection = $response->getCollection();

# Get all rows where column 'First name' is 'Peter' and column 'Score' is '42'
$response = $sheetsu->search([
    'First name'    => 'Peter',
    'Score'         => '42'
]);
$collection = $response->getCollection();

# Get first two row where column 'First name' is 'Peter',
# column 'Score' is '42' from sheet named "Sheet3"
$response = $sheetsu->search([
    'First name'    => 'Peter',
    'Score'         => '42'
], 2, 0);
$collection = $response->getCollection();
```

### Update
[Link to docs](https://sheetsu.com/docs#patch)

To update row(s), pass column name and its value which is used to find row(s) and an array or model with the data to update.

```php
# Update all columns where 'name' is 'Peter' to have 'score' = 99 and 'last name' = 'Griffin'
$model = Model::create(['score' => '99', 'last name' => 'Griffin']);
$response = $sheetsu->update('name', 'Peter', $model);
```

By default, [PATCH request](https://sheetsu.com/docs#patch) is sent, which is updating only values which are in the hash passed to the method. To send [PUT request](https://sheetsu.com/docs#put), pass 4th argument being `true`. [Read more about the difference between PUT and PATCH in our docs](https://sheetsu.com/docs#patch).

### Delete
[Link to docs](https://sheetsu.com/docs#delete)

To delete row(s), pass column name and its value which is used to find row(s).

```php
# Delete all rows where 'name' equals 'Peter'
$response = $sheetsu->delete('name', 'Peter');
```

If success returns `:ok` symbol. If error check [errors](#errors).

### Errors
There are different styles of error handling. We choose to throw exceptions and signal failure loudly. You do not need to deal with any HTTP responses from the API calls directly. All exceptions are matching particular response code from Sheetsu API. You can [read more about it here](https://sheetsu.com/docs#statuses).

All exceptions are a subclass of `Sheetsu::SheetsuError`. The list of different error subclasses is listed below. You can choose to rescue each of them or rescue just the parent class (`Sheetsu::SheetsuError`).


```php
Sheetsu::NotFoundError
Sheetsu::ForbiddenError
Sheetsu::LimitExceedError
Sheetsu::UnauthorizedError
```

## Development

After checking out the repo, run `bin/setup` to install dependencies. You can also run `bin/console` for an interactive prompt that will allow you to experiment.

To install this gem onto your local machine, run `bundle exec rake install`. To release a new version, update the version number in `version.rb`, and then run `bundle exec rake release`, which will create a git tag for the version, push git commits and tags, and push the `.gem` file to [rubygems.org](https://rubygems.org).

Run all tests:
```
rspec
```

Run a single test:
```
rspec spec/read_spec.rb
```

## Contributing

Bug reports and pull requests are welcome on GitHub at https://github.com/sheetsu/sheetsu-ruby. This project is intended to be a safe, welcoming space for collaboration, and contributors are expected to adhere to the [Contributor Covenant](http://contributor-covenant.org) code of conduct.

### Pull Requests

- **Add tests!** Your patch won't be accepted if it doesn't have tests.

- **Create topic branches**. Please, always create a branch with meaningful name. Don't ask us to pull from your master branch.

- **One pull request per feature**. If you want to do more than one thing, please send
  multiple pull requests.

- **Send coherent history**. Make sure each individual commit in your pull
  request is meaningful. If you had to make multiple intermediate commits while
  developing, please squash them before sending them to us.

### Docs

[Sheetsu documentation sits on GitHub](https://github.com/sheetsu/docs). We would love your contributions! We want to make these docs accessible and easy to understand for everyone. Please send us Pull Requests or open issues on GitHub.

## TODO
- [ ] Make this repository work as package with Composer
- [ ] Define and implement ErrorHandler to leverage the final user from handling http status code's