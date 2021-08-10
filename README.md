

## Setup and Install

This project requires php 7.3, MySQL Server of basically any modern version, node version 14, and the Yarn package manager

To set up, follow these steps:
- create a .env fine in the root folder using .env.testing as the 'example' template to follow.
- Setup and run either a mariaDB or mysql server locally with a database for the project, enter credentials into .env.
- run composer install; php artisan migrate; php artisan db:seed 
- run npm install; npm run development to install
- run php artisan serve
- you should be able to see the app on your local machine.

## Endpoints

These are the endpoints the app serves
- GET /app/manage  --leads to the web application portion of the project
- GET /{tinyUrl} -- redirects to the aliased url in question
- POST /api/url_hash {POST} --accepts a URL parameter which is then converted into the tinyUrl. Returns a JSON object with the new tinyUrl and original URL
- POST /api/url_hash/popular -- returns *no more than* 100 JSON objects representing the most used tinyurls. 

## Decisions and Callenges. 

- *Architecture*
The app was originally going to be a Laravel Lumen application serving as a backend for a single page react app. Part way into building this I remembered that the requirements didn't anticipate two repos and that I was putting excess work on myself setting up two servers and building functionalty that Lumen doesn't provide by default, for no good reason. 

In a heavier use case we'd want the backend to be a really fast, headless API and have the frontend be a single page react app at app.<ourUrl>.com. We'd also want to use Redis or Memcache to speed up retrieval of heavily used links. Our server infrastructure would probably consist of multiple containers deployed across multiple time zones sitting behind a load balancer for optimal response time. 

- *User Interface*
I decided to go with Laravel Mix since it allows me to blend the simplicity of a server-side rendered web app with the power of React, particularly for forms. I chose bootstrap as the CSS framework since I know it and it's relatively easy to get working. I also made both the form and the table in react since I wanted to apply a use case to the 'popular' links endpoint I had just made. 

Generally, the challenges with the interface involved some analysis-paralysis on how the system should look, what exactly validation should say, and what packages should be used or not used for the form and table elements, as well as some bits of configuration that I had learned to set up and then promptly forgotten in previous projects. 

Given more time with the user interface I would probably add Yup Validation or a similar package for client-side validation instead of relying solely on the server. I'd also atomize things more by splitting form elements,  submission handlers, and type definitions into their own component and utility files respectively. As mentioned above, I'd probably make it a full single-page react app. 

- *General Usability*

While the requirements didn't mention it explicitly, a better feature would be to account support to associate a tiny url with a person. This could be for billing or validation of some sort. 

