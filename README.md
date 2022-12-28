Etapes de configuration de Wild Series
-----
Aprés avoir forké ou cloné le projet, veuillez effectuer les étapes suivantes :
1. Faire un Composer Install
2. Faire un yarn install
3. cp .env .env.local puis remplacer les informations de connection à la base de données par ceux de votre systéme.
4. Créer ensuite la base avec php bin/console doctrine:database:create
5. lancez les migrations avec php bin/console doctrine:migrations:migrate
6. et pour finir les fixtures sont créees avec : php bin/console doctrine:fixtures:load
7. lancez le serveur avec symfony serve -d

____
That's all folks !!!