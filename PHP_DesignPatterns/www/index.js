/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

import { dispatcher } from "./js/dispatchers.js";

document.getElementById("Sender").addEventListener("click",dispatcher.Send); 