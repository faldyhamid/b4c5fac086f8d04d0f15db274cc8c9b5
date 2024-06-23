This is a very basic API to send an email from a user.

Set up:
1. Fill in environment variables in docker-compose.yaml. Email Host that the API is going to use, along with credentials under their respective variable.

![image](https://github.com/faldyhamid/b4c5fac086f8d04d0f15db274cc8c9b5/assets/26078040/03c17fc9-4d70-481a-b529-8a1047d25d1e)

2. Build and run the docker container.
3. Ready to receive requests at port 8080
4. Send requests with the param endpoint (Ex: http://localhost:8080/?endpoint=user)

![image](https://github.com/faldyhamid/b4c5fac086f8d04d0f15db274cc8c9b5/assets/26078040/73a04412-900c-4332-849e-4a84bf9814ee)

Enpoints:
1. GET user - Fetches all users from the database
2. POST user - Registers a user and adds it to database. Expects username, password, e-mail address, and full name in JSON format.

![image](https://github.com/faldyhamid/b4c5fac086f8d04d0f15db274cc8c9b5/assets/26078040/233b32df-ef2e-4751-bd85-831824952a44)

3. POST login - Log in to receive a bearer token and access email functionality. Expects a username and password.

![image](https://github.com/faldyhamid/b4c5fac086f8d04d0f15db274cc8c9b5/assets/26078040/04feae7b-983b-4858-9ec5-d453264240bb)

4. POST email - Sends an email using the set host. Expects a recipient email address, recipient name, subject, and email body. Requires a valid bearer token.

![image](https://github.com/faldyhamid/b4c5fac086f8d04d0f15db274cc8c9b5/assets/26078040/e03b2592-4b9d-45af-80b1-8b28d9e0c5e5)

5. GET userEmails - Fetches all emails sent by the signed in user. Requires a valid bearer token.
