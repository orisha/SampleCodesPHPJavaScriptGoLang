/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

import { setStatus } from "./status.js";
import { builder } from "./builders.js";


const dispatchSend = () => {
    setStatus('');
    const categories = Array
        .from(document.getElementsByTagName('input'))
        .filter( item => {
            return item.checked;
        })
        .map( item => {
            return item.value;
        });
    if (categories.length === 0){
        setStatus('Must Pick at least one category', 'secondary');
        return;
    }
    const message = document.getElementById('message').value;
    if (!message || message.length === 0){
        setStatus('Please provide a message', 'secondary');
        return;
    }
    setStatus('Processing', 'info');
    const postData = {
        categories: categories,
        message: message,
    }                
    var request = new Request('http://localhost/api.php/send', 
        { 
            method: 'POST', 
            body: JSON.stringify(postData), 
            headers: {"Content-type": "application/json; charset=UTF-8"}
        }
    );
    
    return fetch(request) 
        .then((response) => {
            setStatus('Done', 'success');
            response.json().then(response => {
                dispatchLoguerUpdate();
            })
        })
        .catch(err => { 
            setStatus(err.message, 'danger');
        });

}

const dispatchLoguerUpdate = () => {
    
    var request = new Request(`http://localhost/api.php/get`, 
        { 
            method: 'GET', 
            headers: {"Content-type": "application/json; charset=UTF-8"}
        }
    );
    fetch(request)
    .then(response => {
        response.json().then(response => {
            builder.Loguer(response);
        });
    })
    .finally(
        () => {
            const categories = Array
                .from(document.getElementsByTagName('input'))
                .map( item => {
                    item.checked = false;
                    return item;
                });
            document.getElementById('message').value = '';
        }
    );
}

export const dispatcher = {
    Send: dispatchSend,
}