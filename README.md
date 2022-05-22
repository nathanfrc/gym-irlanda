# API - GYM

Create an API and deploy it into any provider. Requirements for the API - Create an application for gyms that allows administrators to register, amend, and delete staff. Once user is a staff, they can add new members to the platform, design new training for them, and choose how long the new training will last. (For example, 20 days, 30 days, and 45 days). Members can log into the platform to view their training, leave comments for the user who prepared it, and mark the day's training as completed.

Everyone can have access to it:
Create endpoint for login

Only admin users can have access to it:
Create endpoint for registering staff
Create endpoint for updating staff
Create endpoint for deleting staff

Only employees/admin users can have access to it:
Create endpoint for registering members
Create endpoint for updating members
Create endpoint for deleting members
Create endpoint for creating new training for the respective members
Create endpoint for updating training for the respective members
Create endpoint for deleting respective members

Only members/employees can have access to it:
Create endpoint for viewing the training (Members can only see their training / Staff can see all the training that they created)
Create endpoint for leaving comments on training
Create endpoint for marking the training done per day

## Endpoints:

- POST /login: Gerar Token

## Subir os ambientes 
```
docker-compose up --build
docker exec -it gym_app php bin/console doctrine:schema:create
```
