# Simple Chat-App Backend with Slim Framework (PHP)

- SQLite is used for database.

This project contains four HTTP requests that a chat-app may include.

## Requests:

```
GET .../api/users
```
- Returns the users in database in JSON format.

```
GET .../api/messages/userid={userid}
```
- Returns the people who has any message with the User(userid) in JSON format.

```
GET .../api/messages/userid={userid}/receiverid={receiverid}
```
- Returns the users in database in JSON format.

```
POST .../api/messages/userid={userid}/receiverid={receiverid}/send
```
- Returns the users in database in JSON format.


## Examples:

URL: `http://localhost:8888/api/users` returns: 
```json
[
    {
        "id": "1",
        "fullName": "Ertugrul Yilmaz",
        "mail": "ertugrul@mail.com",
        "password": "ertugrul"
    },
    {
        "id": "2",
        "fullName": "Selim Aytac",
        "mail": "selim@mail.com",
        "password": "selim"
    },...
]
```

URL: `http://localhost:8888/api/messages/userid=1` returns: 
```json
[
    {
        "id": "2",
        "fullName": "Selim Aytac"
    },
    {
        "id": "3",
        "fullName": "Behlul Koca"
    }
]
```
URL: `http://localhost:8888/api/messages/userid=1/receiverid=2` returns: 
```json
[
    {
        "id": "1",
        "dateTime": "2023-03-03 08:42:50",
        "senderId": "1",
        "receiverId": "2",
        "content": "Hello"
    },
    {
        "id": "2",
        "dateTime": "2023-03-03 08:43:31",
        "senderId": "2",
        "receiverId": "1",
        "content": "Hi"
    },
    {
        "id": "3",
        "dateTime": "2023-03-03 08:43:49",
        "senderId": "1",
        "receiverId": "2",
        "content": "How are you?"
    }
]
```
URL: `http://localhost:8888/api/messages/userid=1/receiverid=2/send` with `{"content" : "How are you"}` returns:
```json
true
```
## Error Captures
- Here is some error capture samples:

For `http://localhost:8888/api/messages/userid=4` returns:
```json
{
    "error": {
        "text": "There is no such user or any conversation(s)."
    }
}
``` 

For `http://localhost:8888/api/messages/userid=1/receiverid=4` returns:
```json
{
    "error": {
        "text": "There is no such message or user(s);"
    }
}
``` 

For `http://localhost:8888/api/messages/userid=1/receiverid=5/send` returns:
```json
{
    "error": {
        "text": "There is no such user(s) or content is empty."
    }
}
```

## Versions
- SQLite version 3.28.0
- Slim Framework 4.9.0
- PHP 7.3.29 (cli)
- Apache Server is runned by MAMP
