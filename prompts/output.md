
17 updates can be applied immediately.
6 of these updates are standard security updates.
To see these additional updates run: apt list --upgradable

3 additional security updates can be applied with ESM Apps.
Learn more about enabling ESM Apps service at https://ubuntu.com/esm


*** System restart required ***
Last login: Fri Jun 26 10:50:10 2026 from 102.90.96.124
\ubuntu@ip-172-31-45-137:~$ docker ps
\CONTAINER ID   IMAGE                          COMMAND                  CREATED          STATUS                 PORTS
                              NAMES
34b09d64a854   atendai/evolution-api:latest   "/bin/bash -c '. ./D…"   47 minutes ago   Up 47 minutes          0.0.0.0:8080->8080/tcp, [::]:8080->8080/tcp      
                              evolution-api
f213a4efbc7d   postgres:15-alpine             "docker-entrypoint.s…"   47 minutes ago   Up 47 minutes          5432/tcp
                              evolution-postgres
52ad585926a3   nginx:latest                   "/docker-entrypoint.…"   7 weeks ago      Up 7 weeks             0.0.0.0:80->80/tcp, [::]:80->80/tcp, 0.0.0.0:443->443/tcp, [::]:443->443/tcp   projectx-nginx
c11328932822   project-x-app                  "docker-php-entrypoi…"   7 weeks ago      Up 7 weeks             0.0.0.0:9000->9000/tcp, [::]:9000->9000/tcp      
                              projectx-app
644d1fc39458   redis:7-alpine                 "docker-entrypoint.s…"   7 weeks ago      Up 7 weeks             0.0.0.0:6379->6379/tcp, [::]:6379->6379/tcp      
                              projectx-redis
aad495c30e48   mysql:8.0                      "docker-entrypoint.s…"   7 weeks ago      Up 7 weeks (healthy)   0.0.0.0:3306->3306/tcp, [::]:3306->3306/tcp, 3306\\tcp                         projectx-db
  ubuntu@ip-172-31-45-137:~$ sudo ufw status
\Status: inactive
\\ubuntu@ip-172-31-45-137:~$ 