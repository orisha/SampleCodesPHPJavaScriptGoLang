/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

export const setStatus = (message, color) => {
    const statusElement = document.getElementById('status');
    statusElement.className = '';
    if (color){
        statusElement.classList.add(`text-white`);
        statusElement.classList.add(`bg-${color}`);
    }
    statusElement.innerHTML = `${message}`;
}