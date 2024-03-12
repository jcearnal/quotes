# INF653 Midterm Project API

This API provides access to a quotes database, allowing you to retrieve, create, update, and delete quotes, authors, and categories.

## Base URL
`http://quotesdb-app-ddx5u.ondigitalocean.app/api/` 

## Endpoints

### Quotes

- **GET /api/quotes/**
  - Returns an array of all quotes including their id, quote text, author, and category.

- **GET /api/quotes/?id={quoteId}**
  - Returns a single quote by its id.

- **GET /api/quotes/?author_id={authorId}**
  - Returns all quotes from a specific author.

- **GET /api/quotes/?category_id={categoryId}**
  - Returns all quotes in a specific category.

- **GET /api/quotes/?author_id={authorId}&category_id={categoryId}**
  - Returns all quotes from a specific author in a specific category.

- **POST /api/quotes/**
  - Creates a new quote. Requires JSON body with `quote`, `author_id`, and `category_id`.

- **PUT /api/quotes/**
  - Updates an existing quote. Requires JSON body with `id`, `quote`, `author_id`, and `category_id`.

- **DELETE /api/quotes/**
  - Deletes a quote by its id. Requires JSON body with `id`.

### Authors

- **GET /api/authors/**
  - Returns an array of all authors including their id and name.

- **GET /api/authors/?id={authorId}**
  - Returns a single author by its id.

- **POST /api/authors/**
  - Creates a new author. Requires JSON body with `author` name.

- **PUT /api/authors/**
  - Updates an existing author. Requires JSON body with `id` and `author` name.

- **DELETE /api/authors/**
  - Deletes an author by its id. Requires JSON body with `id`.

### Categories

- **GET /api/categories/**
  - Returns an array of all categories including their id and name.

- **GET /api/categories/?id={categoryId}**
  - Returns a single category by its id.

- **POST /api/categories/**
  - Creates a new category. Requires JSON body with `category` name.

- **PUT /api/categories/**
  - Updates an existing category. Requires JSON body with `id` and `category` name.

- **DELETE /api/categories/**
  - Deletes a category by its id. Requires JSON body with `id`.
