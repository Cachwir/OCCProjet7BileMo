# OCCProjet7BileMo

Projet n°7 du parcours Développeur PHP/Symfony : développer une API sous Symfony

Version 1.0

Author: Cachwir

### how to install

- Pull the project (git clone https://github.com/Cachwir/OCCProjet7BileMo.git)
- you can rename the folder or leave it as it is.
- cd OCCProjet7BileMo or whatever you named it
- run composer install to install the dependancies
- follow this guide for permissions depending on your os : http://symfony.com/doc/current/setup/file_permissions.html (add some add some chmod -R 777) to the following folders :
   - var
- cd app/config and copy parameters.yml.dist to parameters.yml
- feel parameters.yml with your own config. Basically, you just need to fill the database part.
- create your database using ./bin/console doctrine:schema:create
- add default data using ./bin/console doctrine:fixtures:load
- configure your virtual server if you need it. It needs to point to the web folder at the root of the project.
- further documentation is available in the doc folder
- enjoy~