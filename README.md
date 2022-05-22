# API GYM
## Api to manage employees and customers of a gym

## Features
>Create an API and deploy it into any provider. Requirements for the API - Create an application for gyms that allows administrators to register, amend, and delete staff. Once user is a staff, they can add new members to the platform, design new training for them, and choose how long the new training will last. (For example, 20 days, 30 days, and 45 days). Members can log into the platform to view their training, leave comments for the user who prepared it, and mark the day's training as completed.

>Everyone can have access to it: Create endpoint for login

>Only admin users can have access to it: Create endpoint for registering staff Create endpoint for updating staff Create endpoint for deleting staff

>Only employees/admin users can have access to it: Create endpoint for registering members Create endpoint for updating members Create endpoint for deleting members Create endpoint for creating new training for the respective members Create endpoint for updating training for the respective members Create endpoint for deleting respective members

>Only members/employees can have access to it: Create endpoint for viewing the training (Members can only see their training / Staff can see all the training that they created) Create endpoint for leaving comments on training Create endpoint for marking the training done per day

>This text you see here is *actually- written in Markdown! To get a feel
for Markdown's syntax, type some text into the left window and
watch the results in the right.
## Tech
Number of open source projects to work properly:

- PHP - ^7.1.3
- Symfony - ^4.3
- Docker compose - 3.7
- Database - Mysql

## Installation

Dillinger requires [docker](https://docs.docker.com/get-docker/).

Install the dependencies and devDependencies and start the server.

```
git clone [repository]
```
```
docker-compose up --build
```
Create database 
````
docker exec -it gym_app php bin/console doctrine:schema:create
`````

Migrates...

```sh
 bin/console make:migration
 bin/console doctrine:migrations:migrate
```
```sh
| 127.0.0.1:8001 |
```
## EndPoints

Route documentation

## JWT
| Method | Route | description |
| ------ | ------ | ------ |
| POST | /login |to generate jwt access token |

## User 

endpoints to manage system users

| Method | Route | description |
| ------ | ------ | ------ |
| POST | /user |create a new user |
| PUT | /user/{id} |update a new user |
| DELETE | /user/{id} |delete a new user |
| GET | /user/{id} | search for a user by id |
| GET | /users | search all users |

Users are defined by roles ex:
ROLE_ADMIN  - full access
ROLE_STAFF- limited access
ROLE_USER - limited access

pagination is done by queryParam parameters. ex:
[{base}/{endpoint}/?page=1&itemsPerPage=10 ]

## Person
endpoints to manage customers, employees

| Method | Route | description |
| ------ | ------ | ------ |
| POST | /person/user | Create user for client with client access permission only |
| POST | /person |create a new person |
| PUT | /person/{id} |update a new person |
| DELETE | /person/{id} |delete a new person |
| GET | /person/{id} | search for a user by id |
| GET | /persons | search all users |

## Workouts
endpoints to manage customer training
| Method | Route | description |
| ------ | ------ | ------ |
| POST | /workout |create a new workouts |
| PUT | /workout/{id} |update a new workouts |
| DELETE | /workout/{id} |delete a new workouts |
| GET | /workout/{id} | search for a workouts by id |
| GET | /workouts | search all workouts |
| GET | person/workouts/{id} | search all workouts by person |

## Comments
endpoints to handle training comments for clients

| Method | Route | description |
| ------ | ------ | ------ |
| POST | /workout/comments |create a new comments |
| PUT | /workout/comments/{id} |update a new comments |
| DELETE | /workout/comments/{id} |delete a new comments |
| GET | /workout/comments/{id} | search for a comments by id |
| GET | /workouts/comments | search all comments |
| GET | /workouts/{id}/comments | search all comments by workouts  |

## CheckWorkouts
endpoints to manage people's workouts, created by their trainers
| Method | Route | description |
| ------ | ------ | ------ |
| POST | /CheckWorkout |create a new comments |
| PUT | /CheckWorkout/{id} |update a new comments |
| DELETE | /CheckWorkout/{id} |delete a new comments |
| GET | /CheckWorkout/{id} | search for a comments by id |
| GET | /CheckWorkout | search all comments |
| GET | /CheckWorkout/person/8 | search all comments by workouts  |

Questions contact nathan.frc@hotmail.com
