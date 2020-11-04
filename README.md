### This is just a simple wrap of Goodreads API.

*This class does not use caching responses.*

### Available Methods

- [author.books](https://www.goodreads.com/api/index#author.books)
- [search.books](https://www.goodreads.com/api/index#search.books)
- [book.title](https://www.goodreads.com/api/index#book.title)


***Usage***

```
// initializing the api
  $api = new Goodreads(YOUR_API_KEY);

// getting response in an array(key,value)
  $api_response = $api->get_books_by_author(AUTHOR_ID);

// print with print_r

 print_r($api_response);

```

Inspired by [Goodreads-api](https://github.com/danielgwood/goodreads-api)

 More methods will be added later
