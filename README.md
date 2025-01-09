## Webnode Test Application

This is a repository for homework from Webnode. The application consists of small Order bundle that can be used to create, read, update and delete orders. The application is built using Symfony 7.2 and PHP 8.4.

### Installation

1. Clone the repository
2. Run ```docker-compose --env-file ./docker/.env up -d``` to start the application
3. Attach to the PHP container using ```docker exec -it webnode_php sh``` (name of the container can be different on your machine)
4. Run ```cd /app``` to get to root of application
5. Run ```chmod +x init-app.sh```
6. Run `./init-app.sh` to install dependencies and run migrations automatically

##### Optional:
If you launched the application with custom .env file for docker, you have to generate it yourself, by running the following command. Please replace the variables accordingly to your .env docker file.
```bash
echo "DATABASE_URL=mysql://{USER}:{PASSWORD}@mysql:{PORT}/{DB_NAME}" > .env.local
```

### Usage
The application runs on `localhost:8080`. You can access the application by visiting http://localhost:8080 in your browser.
The easiest way to use is to install Postman and use the exported schema from `/docs/postman/Order/order.json`

The application is using PHPStan with level 9. It also uses codesniffer to check the code quality. You can run the following commands to automatically check PHPStan, fix the code with codesniffer and run the tests:
```bash
./run-tests.sh
```
Of course you have to ensure that the script has the right permissions to run and that you are running it inside the container.