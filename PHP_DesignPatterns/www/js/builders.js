/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

const renderLoguer = (data) => {
    const loguer = document.getElementById('loguer');
    
    Array
        .from(document.getElementsByClassName('log-entry'))
        .map(
            a => {
                a.remove()
            }
        );

    const textBox = (id, attribute, value) => {
        const boxElement = document.createElement(`div`);
        boxElement.id = `${id}-${attribute}`;
        boxElement.classList.add('d-inline','m-2','p-1');
        if (attribute === 'Status') {
            if (value === 'Sent'){
                boxElement.classList.add('bg-success');
            }
            if (value === 'Failed'){
                boxElement.classList.add('bg-danger');
            }
        }
        boxElement.innerHTML = `${value}`;
        return boxElement;                    
    }
    Object.entries(data)
        .filter(
            entry => { 
                return entry[1].length > 0;
            }
        )
        .map(
            entry => {

                const lineParams = JSON.parse(entry[1]);

                const {id, user, message, channel, status, date} = lineParams;

                const wrapper = document.createElement(`div`);
                wrapper.id = `${id}`;
                wrapper.classList.add('flex-column', 'log-entry');
                const divID = textBox(id, 'ID', id)
                wrapper.appendChild(divID);
                
                const divUser = textBox(id, 'User', user)
                wrapper.appendChild(divUser);
                
                const divMessage = textBox(id, 'Message', message)
                wrapper.appendChild(divMessage);

                const divChannels = textBox(id, 'Channels', channel)
                wrapper.appendChild(divChannels);

                const divStatus = textBox(id, 'Status', status)
                wrapper.appendChild(divStatus);
                
                const divDate = textBox(id, 'Date', date)
                wrapper.appendChild(divDate);
                
                loguer.appendChild(wrapper);
            }
        );
}


export const builder = {
    Loguer: renderLoguer
}