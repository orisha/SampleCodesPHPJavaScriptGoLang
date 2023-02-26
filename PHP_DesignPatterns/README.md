To run : 
$ docker-compose up --build

accessible on http://localhost

Files on www folder

### This is a conceptual notification app. ###

Instead of actually send a notification, it logs on 'ProofOfConcept.txt'

The logs are been recorded on notification.json file. I really would not advice to use this schema on real world scenario.
To add a new loguer just use the Loguer contract.

The frontend is just getting all entries on logs and displaying it. Again, not good for the real world scenario. A pagination and a state control would be a better fit.

The Notifications and its logs are sent synchronously. In a production system I would stick to Laravel Queue and Workers to trigger it asynchronously.




/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */
