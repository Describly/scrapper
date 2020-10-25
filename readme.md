# Scrapper 
This is an assignment project.

#Target
To extract the following information using the curl in PHP
```json
{
  "accountType": "",
  "Traffic left today for viewing\/downloading": "",
  "Used traffic today": "",
  "Cookies": []
}
```

# Condition
Use only php5.6 and curl, use of third-party libraries/packages is not recommended.


# Running the Script [Assignment Solution]
## Step - 1 [Account Setup]
 - Create an account on https://k2s.cc/ if not already registered.
 - Go to https://k2s.cc/ and login to the website using your username and password.

## Step - 2 [Project Setup]
 - Clone the project
 - You can run this project in two ways `With Docker` or `Without Docker`
   - With Docker:
     - Install Docker in you machine
     - Update docker-compose with your username & password as below - 
        ```yaml
        version: '3.1'
        
        services:
          app:
            build:
              context: app
              dockerfile: Dockerfile
            volumes:
              - ./app:/app
            environment:
              USERNAME: 'username'
              PASSWORD: 'password'
              DEBUG: 0
        ```
       - Now run `docker-compose run app sh -c "php Main.php"` using the terminal.
       - This will download the php5.6 docker image and run the script, you will get the output printed in terminal in JSON format.
       
   - Without Docker:
     - Set the `$username` & `$password` variable value with your username and password.
     - No go to you terminal and run the below command - 
        ```shell script
        php Main.php
        ```
     - This will execute the script and print the result in JSON format in terminal.
 
 - JSON output will be as below - 
    ```json
    {
      "accountType": "free",
      "Traffic left today for viewing\/downloading": 10737418140,
      "Used traffic today": 0,
      "Cookies": [
        "__cfduid=d81479af392bb69f12c9646dcddeb6c3c1603640864; expires=Tue, 24-Nov-20 15:47:44 GMT; path=\/; domain=.k2s.cc; HttpOnly; SameSite=Lax",
        "pcId=s%3A94e1f658b5faa.To0Gg4QCS9I6VOCt9Ov5N98kBqnuYh9loDawsP72kdI; Max-Age=8640000; Domain=.k2s.cc; Path=\/; Expires=Tue, 02 Feb 2021 15:47:44 GMT; HttpOnly; Secure; SameSite=None",
        "accessToken=eyJhbGcdOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjM5MDM1MjMwLCJhdWQiOiJ1c2VyIiwidHlwZSI6ImFjY2Vzc1Rva2VuIiwiaXNzIjoiazJzIiwiY0lkIjoiNWFjZDlmYTBmYzRlMDcxYzcxNTcxYTQwIiwianRpIjoiM2M4NmQxNTNkOWJlNiIsInNnbiI6Ijg3ODRiNmI4YzUiLCJpYXQiOjE2MDM2NDA4NjQsImV4cCI6MTYwNDI0NTY2NH0.lUB28-LSZZsmAkBDuVD0xgkmTjD_KcY_sCvzGZ6dHfs; Domain=.k2s.cc; Path=\/; Expires=Sun, 01 Nov 2020 15:47:44 GMT; HttpOnly; Secure; SameSite=None",
        "refreshToken=eyJhbGci4iJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjM5MDM1MjMwLCJhdWQiOiJ1c2VyIiwidHlwZSI6InJlZnJlc2hUb2tlbiIsImlzcyI6ImsycyIsImNJZCI6IjVhY2Q5ZmEwZmM0ZTA3MWM3MTU3MWE0MCIsImp0aSI6IjA2ZTE0NzZjNzNjYzAiLCJzZ24iOiI4Nzg0YjZiOGM1IiwiaWF0IjoxNjAzNjQwODY0LCJleHAiOjE2MDYyMzI4NjR9.pdhB9eRz5FiOq1T-5MubP4aF-L35lM4t_LFGWA_wFGg; Domain=.k2s.cc; Path=\/; Expires=Tue, 24 Nov 2020 15:47:44 GMT; HttpOnly; Secure; SameSite=None"
      ]
    }
    ```

