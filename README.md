# Portfolio-api
Portfolio api using laravel 

## Getting Started

### Step 1: Set up database in .env

~~~~
DB_DATABASE=[Your DB Name]
DB_USERNAME=[Your DB Username]
DB_PASSWORD=[Your DB Password]
~~~~

## Api Authentication [Laravel JWT]

### What is JSON Web Token?
JSON Web Token (JWT) is an open standard (RFC 7519), and it represents a compact and self-contained method for securely transmitting information between parties as a JSON object. Digital signature makes the data transmission via JWT trusted and verified. JWTs built upon the secret HMAC algorithm or a public/private key pair using RSA or ECDSA.

### Why is JWT Required?

JWT is used for Authorization and information exchange between server and client. It authenticates the incoming request and provides an additional security layer to REST API, which is best for security purposes.

### How does JWT Work?
User information such as username and password is sent to the web-server using HTTP GET and POST requests. The web server identifies the user information and generates a JWT token and sends it back to the client. Client store that token into the session and also set it to the header. On the next HTTP call, that token is verified by the server, which returns the response to the client.

### JSON Web Token Structure

JSON Web Tokens contains three parts separated by dots (.) In its dense form.

~~~~
eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9
.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3RoZXNob3BseV9uZXRfYXBpXC9hcGlcL3YxXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYyMjA1MzcyMiwiZXhwIjoxOTMzMDkzNzIyLCJuYmYiOjE2MjIwNTM3MjIsImp0aSI6IlF3a25RaHRuZUU4VG85UnAiLCJzdWIiOjMzOSwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9
.qnLLmbwpDS7dh31l8U7VGBzQ3oud79fihJ_5uSsUcVY
~~~~

- Header
- Payload
- Signature

